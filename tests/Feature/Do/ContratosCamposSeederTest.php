<?php

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use Database\Seeders\ContratosCamposSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('carga el catálogo completo del archivo de contratos', function () {
    $this->seed(ContratosCamposSeeder::class);

    expect(Contrato::count())->toBe(20)
        ->and(Campo::count())->toBe(63);
});

it('no duplica registros al reaplicarse', function () {
    $this->seed(ContratosCamposSeeder::class);
    $this->seed(ContratosCamposSeeder::class);

    expect(Contrato::count())->toBe(20)
        ->and(Campo::count())->toBe(63);
});

it('asigna cada campo a su contrato y zona', function () {
    $this->seed(ContratosCamposSeeder::class);

    $granTierra = Contrato::where('nombre', 'GRAN TIERRA')->firstOrFail();

    expect($granTierra->zona)->toBe('Zona 1')
        ->and($granTierra->campos)->toHaveCount(17)
        ->and($granTierra->campos->pluck('nombre'))->toContain('MONOARAÑA - VMM');
});

it('agrupa los contratos en las cuatro zonas', function () {
    $this->seed(ContratosCamposSeeder::class);

    expect(Contrato::distinct()->pluck('zona')->sort()->values()->all())
        ->toBe(['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4']);
});

it('deja todos los registros activos y con código único', function () {
    $this->seed(ContratosCamposSeeder::class);

    expect(Contrato::where('activo', true)->count())->toBe(20)
        ->and(Contrato::distinct()->count('codigo'))->toBe(20);
});
