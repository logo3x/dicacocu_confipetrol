<?php

use App\Enums\EstadoCompromiso;
use App\Models\Compromiso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('un compromiso con fecha limite futura y sin cumplir esta pendiente', function () {
    $compromiso = Compromiso::factory()->create(['fecha_limite' => now()->addMonth()]);

    expect($compromiso->estado())->toBe(EstadoCompromiso::Pendiente);
});

test('un compromiso con fecha limite pasada y sin cumplir esta vencido', function () {
    $compromiso = Compromiso::factory()->vencido()->create();

    expect($compromiso->estado())->toBe(EstadoCompromiso::Vencido);
});

test('un compromiso marcado como cumplido lo esta aunque la fecha limite haya pasado', function () {
    $compromiso = Compromiso::factory()->vencido()->cumplido()->create();

    expect($compromiso->estado())->toBe(EstadoCompromiso::Cumplido);
});

test('marcarCumplido registra la fecha y el usuario que lo cumplio', function () {
    $compromiso = Compromiso::factory()->create();
    $usuario = User::factory()->create();

    expect($compromiso->estado())->toBe(EstadoCompromiso::Pendiente);

    $compromiso->marcarCumplido($usuario);

    expect($compromiso->fresh()->estado())->toBe(EstadoCompromiso::Cumplido)
        ->and($compromiso->fresh()->cumplido_por)->toBe($usuario->id)
        ->and($compromiso->fresh()->cumplido_at)->not->toBeNull();
});

test('created_by se asigna automaticamente al usuario autenticado', function () {
    $usuarioActual = auth()->user();

    $compromiso = Compromiso::create([
        'nombre' => 'Compromiso de prueba',
        'fecha_limite' => now()->addWeek(),
    ]);

    expect($compromiso->created_by)->toBe($usuarioActual->id);
});
