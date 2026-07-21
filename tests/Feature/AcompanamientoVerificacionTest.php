<?php

use App\Enums\ClasificacionOpt;
use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\InspeccionGerencial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

// ── Cálculo del checklist (Sub-Total, 70% del cumplimiento) ─────────────────

test('sin ninguna pregunta respondida el subtotal es null', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create();

    expect($acompanamiento->subTotalChecklist())->toBeNull();
});

test('11 preguntas en si dan un subtotal de 70,07', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(11)->create();

    expect($acompanamiento->subTotalChecklist())->toBe(70.07);
});

test('0 preguntas en si dan un subtotal de 0', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(0)->create();

    expect($acompanamiento->subTotalChecklist())->toBe(0.0);
});

test('9 preguntas en si dan un subtotal de 57,33', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(9)->create();

    expect($acompanamiento->subTotalChecklist())->toBe(57.33);
});

// ── Pregunta 12: coincidencia de pasos (30% del cumplimiento) ───────────────

test('sin ambos conteos de pasos coincidenPasos es null', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'pasos_segun_procedimiento' => null,
        'pasos_en_observacion' => null,
    ]);

    expect($acompanamiento->coincidenPasos())->toBeNull();
});

test('pasos iguales coinciden', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'pasos_segun_procedimiento' => 10,
        'pasos_en_observacion' => 10,
    ]);

    expect($acompanamiento->coincidenPasos())->toBeTrue();
});

test('pasos distintos no coinciden', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'pasos_segun_procedimiento' => 10,
        'pasos_en_observacion' => 8,
    ]);

    expect($acompanamiento->coincidenPasos())->toBeFalse();
});

// ── Total % Cumplimiento y clasificación automática ─────────────────────────

test('11 preguntas en si mas pasos coincidentes da 100,07 y clasifica excelente', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(11, true)->create();

    expect($acompanamiento->puntaje_opt_calculado)->toBe(100.07)
        ->and($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Excelente);
});

test('9 preguntas en si mas pasos coincidentes da 87,33 y clasifica bueno', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(9, true)->create();

    expect($acompanamiento->puntaje_opt_calculado)->toBe(87.33)
        ->and($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Bueno);
});

test('11 preguntas en si pero pasos no coincidentes da 70,07 y clasifica regular', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(11, false)->create();

    expect($acompanamiento->puntaje_opt_calculado)->toBe(70.07)
        ->and($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Regular);
});

test('3 preguntas en si y pasos no coincidentes da 19,11 y clasifica deficiente', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(3, false)->create();

    expect($acompanamiento->puntaje_opt_calculado)->toBe(19.11)
        ->and($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Deficiente);
});

test('sin checklist completo el puntaje calculado queda null', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create();

    expect($acompanamiento->puntaje_opt_calculado)->toBeNull()
        ->and($acompanamiento->clasificacion_opt)->toBeNull();
});

test('actualizar el checklist recalcula el puntaje', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(11, true)->create();

    expect($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Excelente);

    $acompanamiento->update([
        'q1_procedimiento_disponible' => false,
        'q2_usa_epp_correctamente' => false,
        'q3_identifica_peligros_riesgos' => false,
        'pasos_segun_procedimiento' => 10,
        'pasos_en_observacion' => 5,
    ]);

    expect($acompanamiento->fresh()->clasificacion_opt)->toBe(ClasificacionOpt::Deficiente);
});

test('solo la clasificacion excelente no requiere plan de accion', function () {
    expect(ClasificacionOpt::Excelente->requierePlanDeAccion())->toBeFalse()
        ->and(ClasificacionOpt::Bueno->requierePlanDeAccion())->toBeTrue()
        ->and(ClasificacionOpt::Regular->requierePlanDeAccion())->toBeTrue()
        ->and(ClasificacionOpt::Deficiente->requierePlanDeAccion())->toBeTrue();
});

// ── Observador y acompañante ─────────────────────────────────────────────────

test('no se puede crear un acompanamiento sin observador', function () {
    $actividad = Actividad::factory()->create();

    expect(fn () => AcompanamientoVerificacion::create([
        'actividad_id' => $actividad->id,
        'fecha_ejecucion' => now(),
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
    ]))->toThrow(ValidationException::class);
});

test('se puede crear un acompanamiento sin acompanante (es opcional)', function () {
    $actividad = Actividad::factory()->create();
    $observador = User::factory()->create();

    $acompanamiento = AcompanamientoVerificacion::create([
        'actividad_id' => $actividad->id,
        'fecha_ejecucion' => now(),
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
        'observador_id' => $observador->id,
    ]);

    expect($acompanamiento)->not->toBeNull()
        ->and($acompanamiento->acompanante_id)->toBeNull();
});

test('el observador y el acompanante deben ser personas distintas', function () {
    $actividad = Actividad::factory()->create();
    $persona = User::factory()->create();

    expect(fn () => AcompanamientoVerificacion::create([
        'actividad_id' => $actividad->id,
        'fecha_ejecucion' => now(),
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
        'observador_id' => $persona->id,
        'acompanante_id' => $persona->id,
    ]))->toThrow(ValidationException::class);
});

// ── Detención de actividad por riesgo crítico ───────────────────────────────

test('un acompanamiento puede marcar la actividad como detenida independientemente del puntaje', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->conChecklist(11, true)->create([
        'actividad_detenida' => true,
        'motivo_detencion' => 'Riesgo eléctrico no controlado detectado en sitio.',
    ]);

    expect($acompanamiento->clasificacion_opt)->toBe(ClasificacionOpt::Excelente)
        ->and($acompanamiento->actividad_detenida)->toBeTrue();
});

// ── Relaciones ───────────────────────────────────────────────────────────────

test('un acompanamiento pertenece a una actividad y esta expone sus acompanamientos', function () {
    $actividad = Actividad::factory()->create();
    AcompanamientoVerificacion::factory()->count(2)->create(['actividad_id' => $actividad->id]);

    expect($actividad->acompanamientos()->count())->toBe(2);
});

// ── Creación automática de la Parte 2 (Inspección Gerencial) ────────────────

test('al marcar inspeccion gerencial caminar la planta se crea la inspeccion con sus 12 reglas', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $inspeccion = $acompanamiento->fresh()->inspeccionGerencial;

    expect($inspeccion)->toBeInstanceOf(InspeccionGerencial::class)
        ->and($inspeccion->reglas()->count())->toBe(12);
});

test('una verificacion cumplimiento do no crea inspeccion gerencial', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
    ]);

    expect($acompanamiento->fresh()->inspeccionGerencial)->toBeNull();
});
