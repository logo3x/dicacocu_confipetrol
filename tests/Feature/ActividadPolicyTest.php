<?php

use App\Models\Actividad;
use App\Models\User;
use App\Policies\ActividadPolicy;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesPermissionsSeeder::class);
});

test('personal_tecnico puede ver actividades pero no crearlas', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('personal_tecnico');
    $policy = new ActividadPolicy;

    expect($policy->viewAny($user))->toBeTrue()
        ->and($policy->create($user))->toBeFalse();
});

test('lider_om puede crear y editar actividades', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('lider_om');
    $policy = new ActividadPolicy;
    $actividad = Actividad::factory()->create();

    expect($policy->create($user))->toBeTrue()
        ->and($policy->update($user, $actividad))->toBeTrue()
        ->and($policy->delete($user, $actividad))->toBeFalse();
});

test('responsable_hseq puede valorar amenazas y evaluar como hseq en F-14', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('responsable_hseq');

    expect($user->can('valorar amenaza actividad'))->toBeTrue()
        ->and($user->can('evaluar actividad hseq'))->toBeTrue()
        ->and($user->can('evaluar actividad operativo'))->toBeFalse();
});

test('personal_tecnico puede evaluar como operativo pero no como hseq en F-14', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('personal_tecnico');

    expect($user->can('evaluar actividad operativo'))->toBeTrue()
        ->and($user->can('evaluar actividad hseq'))->toBeFalse();
});

test('calidad_corporativa puede consolidar indicadores y gestionar capacitaciones', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('calidad_corporativa');

    expect($user->can('consolidar indicadores do'))->toBeTrue()
        ->and($user->can('gestionar capacitaciones do'))->toBeTrue();
});

test('usuario sin ningun rol de disciplina operativa no puede ver actividades', function () {
    $user = User::factory()->create(['is_active' => true]);
    $policy = new ActividadPolicy;

    expect($policy->viewAny($user))->toBeFalse();
});

test('usuario inactivo no puede ver actividades aunque tenga el rol', function () {
    $user = User::factory()->create(['is_active' => false]);
    $user->assignRole('calidad_corporativa');
    $policy = new ActividadPolicy;

    expect($policy->viewAny($user))->toBeFalse();
});

test('solo super_admin puede eliminar permanentemente una actividad', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('calidad_corporativa');
    $policy = new ActividadPolicy;
    $actividad = Actividad::factory()->create();

    expect($policy->forceDelete($user, $actividad))->toBeFalse();

    $superAdmin = User::factory()->create(['is_active' => true]);
    $superAdmin->assignRole('super_admin');

    expect($policy->forceDelete($superAdmin, $actividad))->toBeTrue();
});

test('un usuario puede combinar rol tecnico y rol de negocio de disciplina operativa', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole(['gestor_documental', 'responsable_hseq']);

    expect($user->can('aprobar documentos'))->toBeTrue()
        ->and($user->can('evaluar actividad hseq'))->toBeTrue();
});
