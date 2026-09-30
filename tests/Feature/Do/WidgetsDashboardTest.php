<?php

use App\Filament\Widgets\ProcedimientosPorVencerWidget;
use App\Filament\Widgets\ResumenProcedimientosWidget;
use App\Filament\Widgets\VerificacionesRecientesWidget;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

/** Usuario con acceso de lectura al modulo, opcionalmente atado a un contrato. */
function usuarioConAccesoDo(?int $contratoId = null, string $rol = 'personal_tecnico'): User
{
    Permission::findOrCreate('ver procedimientos do', 'web');
    Role::findOrCreate($rol, 'web')->givePermissionTo('ver procedimientos do');

    $usuario = User::factory()->create([
        'contrato_id' => $contratoId,
        'is_active' => true,
    ]);
    $usuario->assignRole($rol);

    return $usuario->fresh();
}

it('cuenta los procedimientos por prioridad y los que faltan por estandarizar', function () {
    $usuario = usuarioConAccesoDo();
    $usuario->assignRole(Role::findOrCreate('admin', 'web'));

    ProcedimientoDo::factory()->prioridadAlta()->create();
    ProcedimientoDo::factory()->prioridadMedia()->estandarizado()->create();

    $this->actingAs($usuario);

    Livewire::test(ResumenProcedimientosWidget::class)
        ->assertSee('Procedimientos registrados')
        ->assertOk();
});

it('lista los plazos que vencen dentro de los proximos 30 dias', function () {
    $usuario = usuarioConAccesoDo();
    $usuario->assignRole(Role::findOrCreate('admin', 'web'));

    $porVencer = ProcedimientoDo::factory()->prioridadAlta()->create([
        'nombre_actividad' => 'Mantenimiento de bomba de crudo',
    ]);

    $lejano = ProcedimientoDo::factory()->prioridadAlta()->estandarizado()->create([
        'nombre_actividad' => 'Actividad sin plazo cercano',
        'fecha_programada_verificacion' => now()->addMonths(8),
    ]);

    $this->actingAs($usuario);

    Livewire::test(ProcedimientosPorVencerWidget::class)
        ->assertCanSeeTableRecords([$porVencer])
        ->assertCanNotSeeTableRecords([$lejano]);
});

it('muestra las verificaciones F-14 con su puntaje y criterio', function () {
    $usuario = usuarioConAccesoDo();
    $usuario->assignRole(Role::findOrCreate('admin', 'web'));

    $evaluacion = EvaluacionF14::factory()->completa()->create();

    $this->actingAs($usuario);

    Livewire::test(VerificacionesRecientesWidget::class)
        ->assertCanSeeTableRecords([$evaluacion])
        ->assertOk();
});

it('limita cada widget a los procedimientos del contrato del usuario', function () {
    $propio = ProcedimientoDo::factory()->prioridadAlta()->create();
    $ajeno = ProcedimientoDo::factory()->prioridadAlta()->create();

    $usuario = usuarioConAccesoDo($propio->contrato_id);

    $this->actingAs($usuario);

    Livewire::test(ProcedimientosPorVencerWidget::class)
        ->assertCanSeeTableRecords([$propio])
        ->assertCanNotSeeTableRecords([$ajeno]);
});

it('oculta los widgets a quien no puede ver procedimientos', function () {
    $usuario = User::factory()->create(['is_active' => true]);

    $this->actingAs($usuario);

    expect(ResumenProcedimientosWidget::canView())->toBeFalse()
        ->and(ProcedimientosPorVencerWidget::canView())->toBeFalse()
        ->and(VerificacionesRecientesWidget::canView())->toBeFalse();
});
