<?php

use App\Models\AcompanamientoVerificacion;
use App\Models\User;
use App\Policies\AcompanamientoVerificacionPolicy;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesPermissionsSeeder::class);
});

test('personal_tecnico puede crear un acompanamiento (evaluador operativo)', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('personal_tecnico');
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->create($user))->toBeTrue();
});

test('responsable_hseq puede crear un acompanamiento (evaluador hseq)', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('responsable_hseq');
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->create($user))->toBeTrue();
});

test('lider_om no puede crear un acompanamiento (no es evaluador)', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('lider_om');
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->create($user))->toBeFalse();
});

test('el creador puede editar su acompanamiento mientras no este cerrado', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('personal_tecnico');
    $acompanamiento = AcompanamientoVerificacion::factory()->create(['created_by' => $user->id]);
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->update($user, $acompanamiento))->toBeTrue();
});

test('nadie puede editar un acompanamiento ya cerrado excepto forzando otra via', function () {
    $user = User::factory()->create(['is_active' => true]);
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'created_by' => $user->id,
        'cerrado_at' => now(),
    ]);
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->update($user, $acompanamiento))->toBeFalse();
});

test('calidad_corporativa puede editar acompanamientos de otros usuarios mientras no esten cerrados', function () {
    $otro = User::factory()->create(['is_active' => true]);
    $acompanamiento = AcompanamientoVerificacion::factory()->create(['created_by' => $otro->id]);

    $calidad = User::factory()->create(['is_active' => true]);
    $calidad->assignRole('calidad_corporativa');
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->update($calidad, $acompanamiento))->toBeTrue();
});

test('un evaluador comun no puede editar el acompanamiento de otro usuario', function () {
    $otro = User::factory()->create(['is_active' => true]);
    $acompanamiento = AcompanamientoVerificacion::factory()->create(['created_by' => $otro->id]);

    $tecnico = User::factory()->create(['is_active' => true]);
    $tecnico->assignRole('personal_tecnico');
    $policy = new AcompanamientoVerificacionPolicy;

    expect($policy->update($tecnico, $acompanamiento))->toBeFalse();
});
