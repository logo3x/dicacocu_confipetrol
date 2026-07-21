<?php

use App\Models\Compromiso;
use App\Models\User;
use App\Policies\CompromisoPolicy;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesPermissionsSeeder::class);
});

test('calidad_corporativa puede crear compromisos', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('calidad_corporativa');
    $policy = new CompromisoPolicy;

    expect($policy->create($user))->toBeTrue();
});

test('personal_tecnico no puede crear compromisos', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('personal_tecnico');
    $policy = new CompromisoPolicy;

    expect($policy->create($user))->toBeFalse();
});

test('el responsable asignado puede actualizar su propio compromiso aunque no tenga el permiso general', function () {
    $responsable = User::factory()->create(['is_active' => true]);
    $responsable->assignRole('personal_tecnico');
    $compromiso = Compromiso::factory()->create(['responsable_id' => $responsable->id]);
    $policy = new CompromisoPolicy;

    expect($policy->update($responsable, $compromiso))->toBeTrue();
});

test('un usuario que no es responsable ni tiene el permiso no puede actualizar el compromiso', function () {
    $otro = User::factory()->create(['is_active' => true]);
    $otro->assignRole('personal_tecnico');
    $compromiso = Compromiso::factory()->create();
    $policy = new CompromisoPolicy;

    expect($policy->update($otro, $compromiso))->toBeFalse();
});

test('lider_om puede eliminar compromisos', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('lider_om');
    $compromiso = Compromiso::factory()->create();
    $policy = new CompromisoPolicy;

    expect($policy->delete($user, $compromiso))->toBeTrue();
});
