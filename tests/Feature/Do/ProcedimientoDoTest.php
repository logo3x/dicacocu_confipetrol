<?php

use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('calcula prioridad, plazos y fecha límite al guardar', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'fecha_identificacion' => '2026-01-10',
        'amenaza_riesgo_critico' => true,
        'amenaza_equipos_criticos' => true,
        'amenaza_impacto_ambiental' => true,
        'amenaza_antecedentes' => true,
        'amenaza_afecta_servicio' => true,
    ]);

    expect($procedimiento->puntaje_prioridad)->toBe(90)
        ->and($procedimiento->prioridad)->toBe(PrioridadDo::Alto)
        ->and($procedimiento->plazo_estandarizacion_meses)->toBe(1)
        ->and($procedimiento->frecuencia_verificacion_meses)->toBe(6)
        ->and($procedimiento->fecha_limite_estandarizacion->format('Y-m-d'))->toBe('2026-02-10');
});

it('usa el personal involucrado como valor por defecto de socializados', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'personas_involucradas' => 20,
        'personas_socializadas' => null,
    ]);

    // Se sugiere el total de involucrados, pero mientras no haya divulgación
    // la cobertura es 0: si no, un procedimiento recién inventariado nacería
    // al 100 % e inflaría el indicador CO.
    expect($procedimiento->personas_socializadas)->toBe(20)
        ->and((float) $procedimiento->cobertura_socializacion)->toBe(0.0);
});

it('cuenta la cobertura desde que se registra la divulgación', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'personas_involucradas' => 20,
        'personas_socializadas' => 20,
        'fecha_ultima_divulgacion' => null,
    ]);

    expect((float) $procedimiento->cobertura_socializacion)->toBe(0.0);

    $procedimiento->update(['fecha_ultima_divulgacion' => now()]);

    expect((float) $procedimiento->fresh()->cobertura_socializacion)->toBe(100.0);
});

it('respeta que se vacíe el personal socializado al editar', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'personas_involucradas' => 20,
        'personas_socializadas' => 20,
    ]);

    // Corregir a "aún no se socializó a nadie" no debe reponer el valor de la Etapa 1.
    $procedimiento->update(['personas_socializadas' => null]);

    expect($procedimiento->fresh()->personas_socializadas)->toBeNull()
        ->and((float) $procedimiento->fresh()->cobertura_socializacion)->toBe(0.0);
});

it('respeta un cero explícito en el personal socializado', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'personas_involucradas' => 10,
        'personas_socializadas' => 0,
    ]);

    expect($procedimiento->personas_socializadas)->toBe(0)
        ->and((float) $procedimiento->cobertura_socializacion)->toBe(0.0);
});

it('recalcula el promedio OPT al editar una evaluación existente', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    $evaluacion = EvaluacionF14::factory()->completa()->create([
        'procedimiento_id' => $procedimiento->id,
        'pasos_segun_procedimiento' => 5,
        'pasos_en_observacion' => 5,
    ]);

    expect((float) $procedimiento->fresh()->puntaje_opt)->toBe(100.07);

    $evaluacion->update(['pasos_en_observacion' => 3]);

    expect((float) $procedimiento->fresh()->puntaje_opt)->toBe(70.07);
});

it('recalcula la cobertura cuando se edita el personal socializado', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'personas_involucradas' => 20,
        'personas_socializadas' => 20,
        'fecha_ultima_divulgacion' => now(),
    ]);

    $procedimiento->update(['personas_socializadas' => 15]);

    expect((float) $procedimiento->fresh()->cobertura_socializacion)->toBe(75.0);
});

it('recalcula la prioridad al cambiar los criterios de amenaza', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    expect($procedimiento->prioridad)->toBe(PrioridadDo::Bajo);

    $procedimiento->update([
        'amenaza_riesgo_critico' => true,
        'amenaza_antecedentes' => true,
        'amenaza_afecta_servicio' => true,
    ]);

    expect($procedimiento->fresh()->puntaje_prioridad)->toBe(60)
        ->and($procedimiento->fresh()->prioridad)->toBe(PrioridadDo::Medio);
});

it('calcula el puntaje OPT de una evaluación F-14 al guardar', function () {
    $evaluacion = EvaluacionF14::factory()->completa()->create([
        'pasos_segun_procedimiento' => 5,
        'pasos_en_observacion' => 5,
    ]);

    expect((float) $evaluacion->subtotal)->toBe(70.07)
        ->and((float) $evaluacion->puntaje_opt)->toBe(100.07)
        ->and($evaluacion->criterio_opt)->toBe(CriterioOpt::Excelente);
});

it('propaga el promedio OPT al procedimiento padre', function () {
    $procedimiento = ProcedimientoDo::factory()->create();
    $observador = User::factory()->create();

    EvaluacionF14::factory()->completa()->create([
        'procedimiento_id' => $procedimiento->id,
        'observador_id' => $observador->id,
        'pasos_segun_procedimiento' => 5,
        'pasos_en_observacion' => 5,
    ]);

    // Segunda evaluación sin coincidencia de pasos: 70,07
    EvaluacionF14::factory()->completa()->create([
        'procedimiento_id' => $procedimiento->id,
        'observador_id' => $observador->id,
        'pasos_segun_procedimiento' => 5,
        'pasos_en_observacion' => 3,
    ]);

    $procedimiento->refresh();

    expect((float) $procedimiento->puntaje_opt)->toBe(85.07)
        ->and($procedimiento->criterio_opt)->toBe(CriterioOpt::Bueno);
});

it('deja el procedimiento sin datos cuando se elimina su única evaluación', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    $evaluacion = EvaluacionF14::factory()->completa()->create([
        'procedimiento_id' => $procedimiento->id,
    ]);

    expect((float) $procedimiento->fresh()->puntaje_opt)->toBeGreaterThan(0);

    $evaluacion->forceDelete();

    expect($procedimiento->fresh()->criterio_opt)->toBe(CriterioOpt::SinDatos);
});

it('no permite dos procedimientos con el mismo código asignado', function () {
    ProcedimientoDo::factory()->create(['codigo_asignado' => 'HSEQ-GCA1-P-01']);

    expect(fn () => ProcedimientoDo::factory()->create(['codigo_asignado' => 'HSEQ-GCA1-P-01']))
        ->toThrow(UniqueConstraintViolationException::class);
});

it('registra el procedimiento con sus cuatro etapas', function () {
    $procedimiento = ProcedimientoDo::factory()->estandarizado()->create([
        'nombre_actividad' => 'Mantenimiento de bomba centrífuga',
        'personas_involucradas' => 12,
    ]);

    assertDatabaseHas('do_procedimientos', [
        'id' => $procedimiento->id,
        'nombre_actividad' => 'Mantenimiento de bomba centrífuga',
        'codificado' => true,
    ]);

    expect($procedimiento->estaEstandarizado())->toBeTrue();
});
