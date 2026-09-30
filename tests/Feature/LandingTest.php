<?php

use App\Models\Do\Contrato;
use App\Models\Do\ProcedimientoDo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(fn () => Cache::forget('landing_cifras'));

it('muestra la página pública con sus cuatro secciones', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Disciplina Operativa')
        ->assertSee('id="dicacocu"', false)
        ->assertSee('id="sistema"', false)
        ->assertSee('id="contacto"', false);
});

it('presenta las cuatro etapas del ciclo', function () {
    $respuesta = $this->get('/');

    foreach (['Disponibilidad', 'Calidad', 'Comunicación', 'Cumplimiento'] as $etapa) {
        $respuesta->assertSee($etapa);
    }
});

it('nombra los formatos oficiales', function () {
    $this->get('/')
        ->assertSee('HSEQ-GCA1-F-17')
        ->assertSee('HSEQ-GCA1-F-14');
});

it('publica cifras tomadas del sistema', function () {
    $contrato = Contrato::factory()->create(['activo' => true]);
    ProcedimientoDo::factory()->estandarizado()->create(['contrato_id' => $contrato->id]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Procedimientos registrados')
        ->assertSee('Contratos activos');
});

it('lleva las páginas anteriores a su sección correspondiente', function (string $ruta, string $ancla) {
    $this->get($ruta)->assertRedirectContains($ancla);
})->with([
    ['/sistema-sgd', 'sistema'],
    ['/dicacocu', 'dicacocu'],
    ['/contacto', 'contacto'],
]);
