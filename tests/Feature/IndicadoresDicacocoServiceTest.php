<?php

use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\ActividadPersonalExpuesto;
use App\Models\Documento;
use App\Models\User;
use App\Services\IndicadoresDicacocoService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('sin actividades todos los indicadores son 0', function () {
    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->disponibilidad)->toBe(0.0)
        ->and($indicadores->calidad)->toBe(0.0)
        ->and($indicadores->comunicacion)->toBe(0.0)
        ->and($indicadores->cumplimiento)->toBe(0.0);
});

// ── DI — Disponibilidad ──────────────────────────────────────────────────────

test('disponibilidad es el porcentaje de actividades estandarizadas sobre el total', function () {
    $doc = Documento::factory()->create();
    Actividad::factory()->create(['documento_id' => $doc->id]);
    Actividad::factory()->create(['documento_id' => $doc->id]);
    Actividad::factory()->create(['documento_id' => null]);
    Actividad::factory()->create(['documento_id' => null]);

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->disponibilidad)->toBe(50.0);
});

// ── CA — Calidad ──────────────────────────────────────────────────────────────

test('calidad es el porcentaje de actividades estandarizadas con al menos una verificacion', function () {
    $doc = Documento::factory()->create();
    $verificada = Actividad::factory()->create(['documento_id' => $doc->id]);
    $sinVerificar = Actividad::factory()->create(['documento_id' => $doc->id]);

    AcompanamientoVerificacion::factory()->create([
        'actividad_id' => $verificada->id,
        'fecha_ejecucion' => now(),
    ]);

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->calidad)->toBe(50.0);
});

test('calidad es 0 cuando no hay actividades estandarizadas', function () {
    Actividad::factory()->create(['documento_id' => null]);

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->calidad)->toBe(0.0);
});

// ── CO — Comunicación ─────────────────────────────────────────────────────────

test('comunicacion es el porcentaje de actividades estandarizadas con socializacion registrada', function () {
    $doc = Documento::factory()->create();
    $socializada = Actividad::factory()->create(['documento_id' => $doc->id]);
    $sinSocializar = Actividad::factory()->create(['documento_id' => $doc->id]);

    ActividadPersonalExpuesto::factory()->socializada()->create(['actividad_id' => $socializada->id]);
    ActividadPersonalExpuesto::factory()->create(['actividad_id' => $sinSocializar->id]); // pendiente

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->comunicacion)->toBe(50.0);
});

// ── CU — Cumplimiento ─────────────────────────────────────────────────────────

test('cumplimiento es el promedio de los puntajes opt ejecutados', function () {
    $a1 = Actividad::factory()->create();
    $a2 = Actividad::factory()->create();

    AcompanamientoVerificacion::factory()->conChecklist(11, true)->create(['actividad_id' => $a1->id, 'fecha_ejecucion' => now()]);
    AcompanamientoVerificacion::factory()->conChecklist(11, false)->create(['actividad_id' => $a2->id, 'fecha_ejecucion' => now()]);

    $indicadores = IndicadoresDicacocoService::calcular();

    // (100.07 + 70.07) / 2 = 85.07
    expect($indicadores->cumplimiento)->toBe(85.07);
});

test('cumplimiento ignora evaluaciones sin puntaje aun', function () {
    $a1 = Actividad::factory()->create();

    AcompanamientoVerificacion::factory()->create(['actividad_id' => $a1->id, 'fecha_ejecucion' => now()]);

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->cumplimiento)->toBe(0.0);
});

// ── Filtro por contrato ───────────────────────────────────────────────────────

test('los indicadores se pueden filtrar por contrato', function () {
    $doc = Documento::factory()->create();
    Actividad::factory()->create(['contrato' => 'CONT-A', 'documento_id' => $doc->id]);
    Actividad::factory()->create(['contrato' => 'CONT-A', 'documento_id' => null]);
    Actividad::factory()->create(['contrato' => 'CONT-B', 'documento_id' => $doc->id]);

    $indicadoresA = IndicadoresDicacocoService::calcular('CONT-A');
    $indicadoresB = IndicadoresDicacocoService::calcular('CONT-B');

    expect($indicadoresA->disponibilidad)->toBe(50.0)
        ->and($indicadoresB->disponibilidad)->toBe(100.0);
});

// ── Metas ──────────────────────────────────────────────────────────────────────

test('cumpleDisponibilidad es true solo cuando alcanza la meta del 90 por ciento', function () {
    $doc = Documento::factory()->create();
    Actividad::factory()->count(9)->create(['documento_id' => $doc->id]);
    Actividad::factory()->create(['documento_id' => null]);

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->disponibilidad)->toBe(90.0)
        ->and($indicadores->cumpleDisponibilidad())->toBeTrue();
});

test('cumpleComunicacion usa la meta del 80 por ciento, distinta a las demas', function () {
    $doc = Documento::factory()->create();
    $actividades = Actividad::factory()->count(5)->create(['documento_id' => $doc->id]);

    $actividades->take(4)->each(
        fn (Actividad $a) => ActividadPersonalExpuesto::factory()->socializada()->create(['actividad_id' => $a->id])
    );

    $indicadores = IndicadoresDicacocoService::calcular();

    expect($indicadores->comunicacion)->toBe(80.0)
        ->and($indicadores->cumpleComunicacion())->toBeTrue();
});
