<?php

use App\Filament\Widgets\CompromisosProximosWidget;
use App\Models\Compromiso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('el widget de compromisos proximos renderiza correctamente', function () {
    Compromiso::factory()->create(['nombre' => 'Entrega de indicadores']);

    Livewire::test(CompromisosProximosWidget::class)
        ->assertSuccessful()
        ->assertSee('Entrega de indicadores');
});

test('no muestra compromisos ya cumplidos', function () {
    Compromiso::factory()->cumplido()->create(['nombre' => 'Compromiso cumplido']);
    Compromiso::factory()->create(['nombre' => 'Compromiso pendiente']);

    Livewire::test(CompromisosProximosWidget::class)
        ->assertSuccessful()
        ->assertSee('Compromiso pendiente')
        ->assertDontSee('Compromiso cumplido');
});

test('el estado visual distingue pendiente de vencido', function () {
    Compromiso::factory()->vencido()->create(['nombre' => 'Compromiso vencido']);

    Livewire::test(CompromisosProximosWidget::class)
        ->assertSuccessful()
        ->assertSee('Vencido');
});
