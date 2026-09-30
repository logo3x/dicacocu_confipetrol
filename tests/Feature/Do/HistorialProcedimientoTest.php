<?php

use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra la creación del procedimiento', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    $registro = $procedimiento->historial()->latest('id')->first();

    expect($registro)->not->toBeNull()
        ->and($registro->description)->toBe('Creó el procedimiento');
});

it('registra qué cambió al editar', function () {
    $procedimiento = ProcedimientoDo::factory()->create(['nombre_actividad' => 'Original']);

    $procedimiento->update(['nombre_actividad' => 'Corregido']);

    $registro = $procedimiento->historial()->latest('id')->first();

    expect($registro->description)->toBe('Editó el procedimiento')
        ->and($registro->properties['attributes']['nombre_actividad'])->toBe('Corregido')
        ->and($registro->properties['old']['nombre_actividad'])->toBe('Original');
});

it('registra la eliminación', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    $procedimiento->delete();

    expect($procedimiento->historial()->latest('id')->first()->description)
        ->toBe('Eliminó el procedimiento');
});

it('deja constancia de quién hizo el cambio', function () {
    $usuario = User::factory()->create();
    $this->actingAs($usuario);

    $procedimiento = ProcedimientoDo::factory()->create();

    expect($procedimiento->historial()->latest('id')->first()->causer_id)
        ->toBe($usuario->id);
});

it('no deja rastro cuando se guarda sin cambios', function () {
    $procedimiento = ProcedimientoDo::factory()->create();
    $registrosIniciales = $procedimiento->historial()->count();

    $procedimiento->save();

    expect($procedimiento->historial()->count())->toBe($registrosIniciales);
});
