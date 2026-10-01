<?php

use App\Models\Do\ProcedimientoDo;
use App\Models\Documento;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('enlaza el procedimiento con un documento del repositorio', function () {
    $documento = Documento::factory()->create([
        'codigo' => 'HSEQ-SST-P-8',
        'titulo' => 'Procedimiento para Trabajo en Alturas',
    ]);

    $procedimiento = ProcedimientoDo::factory()->create(['documento_id' => $documento->id]);

    expect($procedimiento->documento->codigo)->toBe('HSEQ-SST-P-8')
        ->and($documento->procedimientosDo)->toHaveCount(1);
});

it('deja el procedimiento sin enlace cuando se borra el documento', function () {
    $documento = Documento::factory()->create();
    $procedimiento = ProcedimientoDo::factory()->create(['documento_id' => $documento->id]);

    // El procedimiento es el registro de la matriz: no desaparece porque el
    // documento se elimine del repositorio, solo pierde la referencia.
    $documento->forceDelete();

    expect($procedimiento->fresh())->not->toBeNull()
        ->and($procedimiento->fresh()->documento_id)->toBeNull();
});

it('admite procedimientos que aun no estan en el repositorio', function () {
    $procedimiento = ProcedimientoDo::factory()->create([
        'documento_id' => null,
        'ubicacion_acceso' => 'SharePoint / HSEQ / Procedimientos',
    ]);

    expect($procedimiento->documento)->toBeNull()
        ->and($procedimiento->ubicacion_acceso)->toBe('SharePoint / HSEQ / Procedimientos');
});

it('registra el enlace en el historial de cambios', function () {
    $procedimiento = ProcedimientoDo::factory()->create();
    $documento = Documento::factory()->create();

    $procedimiento->update(['documento_id' => $documento->id]);

    $ultimo = $procedimiento->historial()->latest('id')->first();

    expect($ultimo->properties['attributes'])->toHaveKey('documento_id');
});
