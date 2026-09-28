<?php

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Services\Do\IndicadoresDoService;
use App\Support\Do\IndicadoresDo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('devuelve ceros cuando no hay procedimientos', function () {
    $indicadores = IndicadoresDoService::calcular();

    expect($indicadores->disponibilidad)->toBe(0.0)
        ->and($indicadores->calidad)->toBe(0.0)
        ->and($indicadores->comunicacion)->toBe(0.0)
        ->and($indicadores->cumplimiento)->toBe(0.0)
        ->and($indicadores->global())->toBe(0.0)
        ->and($indicadores->totalProcedimientos)->toBe(0);
});

it('calcula la disponibilidad como estandarizados sobre el total', function () {
    ProcedimientoDo::factory()->estandarizado()->create();
    ProcedimientoDo::factory()->create();

    expect(IndicadoresDoService::calcular()->disponibilidad)->toBe(50.0);
});

it('filtra por contrato sin romper la consulta', function () {
    $contrato = Contrato::factory()->create();
    $campo = Campo::factory()->create(['contrato_id' => $contrato->id]);

    ProcedimientoDo::factory()->estandarizado()->create([
        'contrato_id' => $contrato->id,
        'campo_id' => $campo->id,
    ]);
    ProcedimientoDo::factory()->create();

    $indicadores = IndicadoresDoService::calcular(contratoId: $contrato->id);

    expect($indicadores->totalProcedimientos)->toBe(1)
        ->and($indicadores->disponibilidad)->toBe(100.0);
});

it('filtra por año del ciclo', function () {
    ProcedimientoDo::factory()->create(['anio_ciclo' => 2025]);
    ProcedimientoDo::factory()->create(['anio_ciclo' => 2026]);

    expect(IndicadoresDoService::calcular(anioCiclo: 2026)->totalProcedimientos)->toBe(1);
});

it('promedia los puntajes OPT en el indicador de cumplimiento', function () {
    $procedimiento = ProcedimientoDo::factory()->estandarizado()->create();

    EvaluacionF14::factory()->completa()->create([
        'procedimiento_id' => $procedimiento->id,
        'pasos_segun_procedimiento' => 4,
        'pasos_en_observacion' => 4,
    ]);

    expect(IndicadoresDoService::calcular()->cumplimiento)->toBe(100.07);
});

it('pondera el cumplimiento global al 25% por indicador', function () {
    $indicadores = new IndicadoresDo(
        disponibilidad: 100.0,
        calidad: 100.0,
        comunicacion: 100.0,
        cumplimiento: 100.0,
    );

    expect($indicadores->global())->toBe(100.0);
});
