<?php

use App\Enums\PrioridadAmenaza;
use App\Filament\Widgets\ActividadesResumenWidget;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('el widget de resumen de actividades renderiza correctamente', function () {
    Actividad::factory()->create(['valoracion_amenaza' => 30]);

    Livewire::test(ActividadesResumenWidget::class)
        ->assertSuccessful()
        ->assertSee('Actividades identificadas')
        ->assertSee('Prioridad alta')
        ->assertSee('Sin estandarizar')
        ->assertSee('Plazo vencido');
});

test('cuenta correctamente las actividades de prioridad alta', function () {
    Actividad::factory()->create(['valoracion_amenaza' => 30]); // alto
    Actividad::factory()->create(['valoracion_amenaza' => 70]); // medio
    Actividad::factory()->create(['valoracion_amenaza' => 95]); // bajo

    Livewire::test(ActividadesResumenWidget::class)
        ->assertSuccessful();

    expect(Actividad::where('prioridad_amenaza', PrioridadAmenaza::Alto->value)->count())->toBe(1);
});
