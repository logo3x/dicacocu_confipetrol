<?php

use App\Enums\PrioridadAmenaza;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

// ── Clasificación de prioridad por rangos límite ────────────────────────────

test('valoracion 100 clasifica como prioridad bajo', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 100]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Bajo);
});

test('valoracion 80 (límite inferior de bajo) clasifica como bajo', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 80]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Bajo);
});

test('valoracion 79 (justo debajo de bajo) clasifica como medio', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 79]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Medio);
});

test('valoracion 60 (límite inferior de medio) clasifica como medio', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 60]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Medio);
});

test('valoracion 59 (justo debajo de medio) clasifica como alto', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 59]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Alto);
});

test('valoracion 0 clasifica como prioridad alto', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => 0]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Alto);
});

test('sin valoracion de amenaza no se asigna prioridad ni plazos', function () {
    $actividad = Actividad::factory()->create(['valoracion_amenaza' => null]);

    expect($actividad->prioridad_amenaza)->toBeNull()
        ->and($actividad->fecha_limite_estandarizacion)->toBeNull()
        ->and($actividad->fecha_limite_verificacion)->toBeNull();
});

// ── Plazos automáticos de estandarización (Etapa 2) ─────────────────────────

test('prioridad bajo otorga 4 meses de plazo de estandarizacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 90,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_estandarizacion->toDateString())->toBe('2026-05-15');
});

test('prioridad medio otorga 2 meses de plazo de estandarizacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 70,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_estandarizacion->toDateString())->toBe('2026-03-15');
});

test('prioridad alto otorga 1 mes de plazo de estandarizacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 30,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_estandarizacion->toDateString())->toBe('2026-02-15');
});

// ── Frecuencia automática de verificación (Etapa 4) ─────────────────────────

test('prioridad bajo otorga 12 meses de frecuencia de verificacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 90,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_verificacion->toDateString())->toBe('2027-01-15');
});

test('prioridad medio otorga 6 meses de frecuencia de verificacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 70,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_verificacion->toDateString())->toBe('2026-07-15');
});

test('prioridad alto otorga 3 meses de frecuencia de verificacion', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 30,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->fecha_limite_verificacion->toDateString())->toBe('2026-04-15');
});

// ── Recalculo al actualizar ──────────────────────────────────────────────────

test('actualizar la valoracion de amenaza recalcula prioridad y plazos', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 90,
        'fecha_identificacion' => '2026-01-15',
    ]);

    expect($actividad->prioridad_amenaza)->toBe(PrioridadAmenaza::Bajo);

    $actividad->update(['valoracion_amenaza' => 20]);

    expect($actividad->fresh()->prioridad_amenaza)->toBe(PrioridadAmenaza::Alto)
        ->and($actividad->fresh()->fecha_limite_estandarizacion->toDateString())->toBe('2026-02-15');
});

// ── Estado de estandarización ────────────────────────────────────────────────

test('una actividad sin documento vinculado no esta estandarizada', function () {
    $actividad = Actividad::factory()->create(['documento_id' => null]);

    expect($actividad->estaEstandarizada())->toBeFalse();
});

test('una actividad de prioridad alta vencida sin documento se marca como vencida', function () {
    $actividad = Actividad::factory()->create([
        'valoracion_amenaza' => 30,
        'fecha_identificacion' => now()->subMonths(2)->toDateString(),
        'documento_id' => null,
    ]);

    expect($actividad->estandarizacionVencida())->toBeTrue();
});
