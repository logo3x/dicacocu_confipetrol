<?php

use App\Models\Do\Campo;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use App\Policies\Do\ProcedimientoDoPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function usuarioDeContrato(?int $contratoId, string $rol = 'operativo'): User
{
    foreach (['ver procedimientos do', 'editar procedimientos do', 'eliminar procedimientos do'] as $permiso) {
        Permission::findOrCreate($permiso, 'web');
    }

    Role::findOrCreate($rol, 'web')->givePermissionTo([
        'ver procedimientos do',
        'editar procedimientos do',
        'eliminar procedimientos do',
    ]);

    $usuario = User::factory()->create(['contrato_id' => $contratoId, 'is_active' => true]);
    $usuario->assignRole($rol);

    return $usuario->fresh();
}

it('permite trabajar sobre los procedimientos del propio contrato', function () {
    $procedimiento = ProcedimientoDo::factory()->create();
    $usuario = usuarioDeContrato($procedimiento->contrato_id);

    $policy = new ProcedimientoDoPolicy;

    expect($policy->view($usuario, $procedimiento))->toBeTrue()
        ->and($policy->update($usuario, $procedimiento))->toBeTrue()
        ->and($policy->delete($usuario, $procedimiento))->toBeTrue();
});

it('impide trabajar sobre procedimientos de otro contrato', function () {
    $ajeno = ProcedimientoDo::factory()->create();
    $otroCampo = Campo::factory()->create();
    $usuario = usuarioDeContrato($otroCampo->contrato_id);

    $policy = new ProcedimientoDoPolicy;

    expect($policy->view($usuario, $ajeno))->toBeFalse()
        ->and($policy->update($usuario, $ajeno))->toBeFalse()
        ->and($policy->delete($usuario, $ajeno))->toBeFalse();
});

it('no muestra nada a quien no tiene contrato asignado', function () {
    $procedimiento = ProcedimientoDo::factory()->create();
    $usuario = usuarioDeContrato(null);

    expect((new ProcedimientoDoPolicy)->view($usuario, $procedimiento))->toBeFalse();
});

it('deja a los administradores ver todos los contratos', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    Permission::findOrCreate('ver procedimientos do', 'web');
    Role::findOrCreate('admin', 'web')->givePermissionTo('ver procedimientos do');

    $admin = User::factory()->create(['contrato_id' => null]);
    $admin->assignRole('admin');

    expect($admin->fresh()->veTodosLosContratos())->toBeTrue()
        ->and((new ProcedimientoDoPolicy)->view($admin->fresh(), $procedimiento))->toBeTrue();
});

it('relaciona al usuario con su contrato y campo', function () {
    $campo = Campo::factory()->create();

    $usuario = User::factory()->create([
        'contrato_id' => $campo->contrato_id,
        'campo_id' => $campo->id,
    ]);

    expect($usuario->contrato->id)->toBe($campo->contrato_id)
        ->and($usuario->campo->nombre)->toBe($campo->nombre);
});
