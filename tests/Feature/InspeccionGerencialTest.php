<?php

use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use App\Models\AcompanamientoVerificacion;
use App\Models\InspeccionGerencialAccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('las 12 reglas se crean automaticamente con el numero correcto', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $numeros = $acompanamiento->fresh()->inspeccionGerencial->reglas()->pluck('numero_regla')
        ->map(fn (ReglaSalvaVidas $r) => $r->value)
        ->sort()
        ->values();

    expect($numeros->all())->toBe(range(1, 12));
});

test('las reglas inician sin respuesta de cumplimiento', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $regla = $acompanamiento->fresh()->inspeccionGerencial->reglas()->first();

    expect($regla->cumple)->toBeNull();
});

test('contarIncumplimientos cuenta solo las reglas marcadas como no', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $inspeccion = $acompanamiento->fresh()->inspeccionGerencial;
    $inspeccion->reglas()->where('numero_regla', ReglaSalvaVidas::UsoDeEpps->value)->update(['cumple' => CumpleRegla::No->value]);
    $inspeccion->reglas()->where('numero_regla', ReglaSalvaVidas::TrabajoEnAlturas->value)->update(['cumple' => CumpleRegla::No->value]);
    $inspeccion->reglas()->where('numero_regla', ReglaSalvaVidas::UsoDeEpps->value + 1)->update(['cumple' => CumpleRegla::Si->value]);

    expect($inspeccion->contarIncumplimientos())->toBe(2);
});

test('inicializarReglas no duplica filas si ya existen', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $inspeccion = $acompanamiento->fresh()->inspeccionGerencial;
    $inspeccion->inicializarReglas();
    $inspeccion->inicializarReglas();

    expect($inspeccion->reglas()->count())->toBe(12);
});

test('se pueden registrar hallazgos positivos y desvios', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $inspeccion = $acompanamiento->fresh()->inspeccionGerencial;
    $inspeccion->update([
        'hallazgos_positivos' => 'Buen uso de EPP en toda el área.',
        'desvios_oportunidades_mejora' => 'Falta señalización en zona de trabajo en caliente.',
    ]);

    expect($inspeccion->fresh()->hallazgos_positivos)->toBe('Buen uso de EPP en toda el área.')
        ->and($inspeccion->fresh()->desvios_oportunidades_mejora)->toBe('Falta señalización en zona de trabajo en caliente.');
});

test('se pueden registrar acciones con responsable y fecha de cierre', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $inspeccion = $acompanamiento->fresh()->inspeccionGerencial;
    $responsable = User::factory()->create();

    $inspeccion->acciones()->create([
        'accion' => 'Instalar señalización de advertencia',
        'responsable_id' => $responsable->id,
        'fecha_cierre' => now()->addWeek(),
    ]);

    expect($inspeccion->acciones()->count())->toBe(1)
        ->and(InspeccionGerencialAccion::first()->responsable->id)->toBe($responsable->id);
});

test('el acompanamiento puede acceder a las reglas y acciones de su inspeccion mediante hasManyThrough', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $acompanamiento->fresh()->inspeccionGerencial->acciones()->create(['accion' => 'Prueba']);

    expect($acompanamiento->fresh()->reglasSalvaVidas()->count())->toBe(12)
        ->and($acompanamiento->fresh()->accionesInspeccion()->count())->toBe(1);
});
