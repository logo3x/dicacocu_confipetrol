<?php

use App\Filament\Widgets\IndicadoresDicacocoWidget;
use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('el widget de indicadores dicacoco renderiza correctamente', function () {
    $doc = Documento::factory()->create();
    $actividad = Actividad::factory()->create(['documento_id' => $doc->id]);
    AcompanamientoVerificacion::factory()->conChecklist(9, true)->create(['actividad_id' => $actividad->id, 'fecha_ejecucion' => now()]);

    Livewire::test(IndicadoresDicacocoWidget::class)
        ->assertSuccessful()
        ->assertSee('DI — Disponibilidad')
        ->assertSee('CA — Calidad')
        ->assertSee('CO — Comunicación')
        ->assertSee('CU — Cumplimiento');
});
