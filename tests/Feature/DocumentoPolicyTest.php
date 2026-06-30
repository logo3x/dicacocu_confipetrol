<?php

use App\Models\Documento;
use App\Models\User;
use App\Policies\DocumentoPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'gestor_documental', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'acceder panel admin', 'guard_name' => 'web']);
});

// ── viewAny ───────────────────────────────────────────────────────────────────

test('usuario activo puede ver listado de documentos', function () {
    $user = User::factory()->create(['is_active' => true]);
    $policy = new DocumentoPolicy;

    expect($policy->viewAny($user))->toBeTrue();
});

test('usuario inactivo no puede ver listado', function () {
    $user = User::factory()->create(['is_active' => false]);
    $policy = new DocumentoPolicy;

    expect($policy->viewAny($user))->toBeFalse();
});

// ── view / confidencial ───────────────────────────────────────────────────────

test('documento no confidencial es visible para cualquier usuario activo', function () {
    $user = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['confidencial' => false]);
    $policy = new DocumentoPolicy;

    expect($policy->view($user, $doc))->toBeTrue();
});

test('documento confidencial solo lo ve el creador', function () {
    $creador = User::factory()->create(['is_active' => true]);
    $otro = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['confidencial' => true, 'created_by' => $creador->id]);
    $policy = new DocumentoPolicy;

    expect($policy->view($creador, $doc))->toBeTrue();
    expect($policy->view($otro, $doc))->toBeFalse();
});

test('admin puede ver documentos confidenciales', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $doc = Documento::factory()->create(['confidencial' => true]);
    $policy = new DocumentoPolicy;

    expect($policy->view($admin, $doc))->toBeTrue();
});

// ── create ────────────────────────────────────────────────────────────────────

test('gestor documental puede crear documentos', function () {
    $gestor = User::factory()->create(['is_active' => true]);
    $gestor->assignRole('gestor_documental');
    $policy = new DocumentoPolicy;

    expect($policy->create($gestor))->toBeTrue();
});

test('usuario sin rol no puede crear documentos', function () {
    $user = User::factory()->create(['is_active' => true]);
    $policy = new DocumentoPolicy;

    expect($policy->create($user))->toBeFalse();
});

// ── update ────────────────────────────────────────────────────────────────────

test('creador puede editar su documento en borrador', function () {
    $creador = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['estado' => 'borrador', 'created_by' => $creador->id]);
    $policy = new DocumentoPolicy;

    expect($policy->update($creador, $doc))->toBeTrue();
});

test('otro usuario no puede editar borrador ajeno', function () {
    $otro = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['estado' => 'borrador']);
    $policy = new DocumentoPolicy;

    expect($policy->update($otro, $doc))->toBeFalse();
});

test('aprobador puede editar documento en revisión', function () {
    $aprobador = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create([
        'estado' => 'en_revision',
        'aprobador_id' => $aprobador->id,
    ]);
    $policy = new DocumentoPolicy;

    expect($policy->update($aprobador, $doc))->toBeTrue();
});

test('usuario sin rol no puede editar documento aprobado', function () {
    $user = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['estado' => 'aprobado']);
    $policy = new DocumentoPolicy;

    expect($policy->update($user, $doc))->toBeFalse();
});

// ── delete ────────────────────────────────────────────────────────────────────

test('creador puede eliminar su borrador', function () {
    $creador = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['estado' => 'borrador', 'created_by' => $creador->id]);
    $policy = new DocumentoPolicy;

    expect($policy->delete($creador, $doc))->toBeTrue();
});

test('nadie sin rol admin puede eliminar documento divulgado', function () {
    $user = User::factory()->create(['is_active' => true]);
    $doc = Documento::factory()->create(['estado' => 'divulgado']);
    $policy = new DocumentoPolicy;

    expect($policy->delete($user, $doc))->toBeFalse();
});

test('admin puede eliminar cualquier documento', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $doc = Documento::factory()->create(['estado' => 'divulgado']);
    $policy = new DocumentoPolicy;

    expect($policy->delete($admin, $doc))->toBeTrue();
});

// ── forceDelete ───────────────────────────────────────────────────────────────

test('solo super_admin puede eliminar permanentemente', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $superAdmin = User::factory()->create(['is_active' => true]);
    $superAdmin->assignRole('super_admin');

    $doc = Documento::factory()->create();
    $policy = new DocumentoPolicy;

    expect($policy->forceDelete($admin, $doc))->toBeFalse();
    expect($policy->forceDelete($superAdmin, $doc))->toBeTrue();
});
