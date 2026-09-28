<?php

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use App\Models\Do\ProcedimientoDo;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('relaciona campos con su contrato', function () {
    $contrato = Contrato::factory()->create(['nombre' => 'Ecopetrol Cusiana']);
    $campo = Campo::factory()->create(['contrato_id' => $contrato->id, 'nombre' => 'Cusiana']);

    expect($campo->contrato->nombre)->toBe('Ecopetrol Cusiana')
        ->and($contrato->campos)->toHaveCount(1);
});

it('no permite dos campos con el mismo nombre en un contrato', function () {
    $contrato = Contrato::factory()->create();
    Campo::factory()->create(['contrato_id' => $contrato->id, 'nombre' => 'Cusiana']);

    expect(fn () => Campo::factory()->create(['contrato_id' => $contrato->id, 'nombre' => 'Cusiana']))
        ->toThrow(UniqueConstraintViolationException::class);
});

it('permite el mismo nombre de campo en contratos distintos', function () {
    $primero = Contrato::factory()->create();
    $segundo = Contrato::factory()->create();

    Campo::factory()->create(['contrato_id' => $primero->id, 'nombre' => 'Cusiana']);
    $otro = Campo::factory()->create(['contrato_id' => $segundo->id, 'nombre' => 'Cusiana']);

    expect($otro->exists)->toBeTrue();
});

it('filtra solo los catálogos activos', function () {
    Contrato::factory()->create(['activo' => true]);
    Contrato::factory()->inactivo()->create();

    expect(Contrato::activos()->count())->toBe(1);
});

it('vincula el procedimiento con su contrato y campo', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    expect($procedimiento->contrato)->toBeInstanceOf(Contrato::class)
        ->and($procedimiento->campo)->toBeInstanceOf(Campo::class)
        ->and($procedimiento->campo->contrato_id)->toBe($procedimiento->contrato_id);
});

it('impide borrar un contrato que tiene procedimientos', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    expect(fn () => $procedimiento->contrato->forceDelete())
        ->toThrow(QueryException::class);
});

it('conserva el contrato al eliminarlo de forma lógica', function () {
    $contrato = Contrato::factory()->create();
    $contrato->delete();

    expect(Contrato::count())->toBe(0)
        ->and(Contrato::withTrashed()->count())->toBe(1);
});
