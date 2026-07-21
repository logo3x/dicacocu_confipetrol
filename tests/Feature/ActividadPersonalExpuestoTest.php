<?php

use App\Enums\EstadoSocializacion;
use App\Models\Actividad;
use App\Models\ActividadPersonalExpuesto;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

// ── Estado de socialización ──────────────────────────────────────────────────

test('sin fecha de socializacion el estado es pendiente', function () {
    $registro = ActividadPersonalExpuesto::factory()->create();

    expect($registro->estado())->toBe(EstadoSocializacion::Pendiente);
});

test('socializado hace menos de un año el estado es vigente', function () {
    $registro = ActividadPersonalExpuesto::factory()->socializada()->create();

    expect($registro->estado())->toBe(EstadoSocializacion::Vigente);
});

test('socializado hace mas de un año el estado es vencida', function () {
    $registro = ActividadPersonalExpuesto::factory()->vencida()->create();

    expect($registro->estado())->toBe(EstadoSocializacion::Vencida);
});

// ── Acción de socializar ─────────────────────────────────────────────────────

test('socializar establece fecha de socializacion, vencimiento a un año y responsable', function () {
    $registro = ActividadPersonalExpuesto::factory()->create();
    $socializador = User::factory()->create();
    $fecha = Carbon::parse('2026-03-10');

    $registro->socializar($socializador, $fecha);

    expect($registro->fresh()->fecha_socializacion->toDateString())->toBe('2026-03-10')
        ->and($registro->fresh()->fecha_vencimiento->toDateString())->toBe('2027-03-10')
        ->and($registro->fresh()->socializado_por)->toBe($socializador->id)
        ->and($registro->fresh()->estado())->toBe(EstadoSocializacion::Vigente);
});

test('resocializar una actividad vencida la vuelve a poner vigente', function () {
    $registro = ActividadPersonalExpuesto::factory()->vencida()->create();
    $socializador = User::factory()->create();

    expect($registro->estado())->toBe(EstadoSocializacion::Vencida);

    $registro->socializar($socializador);

    expect($registro->fresh()->estado())->toBe(EstadoSocializacion::Vigente);
});

// ── Cobertura de socialización (RF-3.2) ──────────────────────────────────────

test('cobertura es 0 cuando no hay personal expuesto registrado', function () {
    $actividad = Actividad::factory()->create();

    expect($actividad->coberturaSocializacion())->toBe(0.0);
});

test('cobertura cuenta solo el personal con socializacion vigente', function () {
    $actividad = Actividad::factory()->create();

    ActividadPersonalExpuesto::factory()->socializada()->count(3)->create(['actividad_id' => $actividad->id]);
    ActividadPersonalExpuesto::factory()->vencida()->count(1)->create(['actividad_id' => $actividad->id]);
    ActividadPersonalExpuesto::factory()->count(1)->create(['actividad_id' => $actividad->id]); // pendiente

    expect($actividad->coberturaSocializacion())->toBe(60.0);
});

test('cobertura es 100 cuando todo el personal expuesto esta vigente', function () {
    $actividad = Actividad::factory()->create();

    ActividadPersonalExpuesto::factory()->socializada()->count(4)->create(['actividad_id' => $actividad->id]);

    expect($actividad->coberturaSocializacion())->toBe(100.0);
});

// ── Unicidad por actividad + persona ─────────────────────────────────────────

test('no se puede registrar dos veces a la misma persona en la misma actividad', function () {
    $actividad = Actividad::factory()->create();
    $persona = User::factory()->create();

    ActividadPersonalExpuesto::factory()->create(['actividad_id' => $actividad->id, 'user_id' => $persona->id]);

    expect(fn () => ActividadPersonalExpuesto::factory()->create([
        'actividad_id' => $actividad->id,
        'user_id' => $persona->id,
    ]))->toThrow(QueryException::class);
});
