<?php

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Carpeta;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\Documento;
use App\Models\User;
use App\Policies\Do\EvaluacionF14Policy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    foreach ([
        'acceder panel admin', 'ver usuarios', 'crear usuarios', 'editar usuarios',
        'eliminar usuarios', 'ver carpetas', 'crear carpetas', 'editar carpetas',
        'eliminar carpetas', 'ver documentos', 'ver documentos confidenciales',
        'ver procedimientos do', 'evaluar f14',
    ] as $permiso) {
        Permission::findOrCreate($permiso, 'web');
    }

    Role::findOrCreate('personal_tecnico', 'web')
        ->syncPermissions(['acceder panel admin', 'ver documentos', 'ver procedimientos do', 'evaluar f14']);

    Role::findOrCreate('admin', 'web')->syncPermissions(Permission::all());
});

function tecnico(?int $contratoId = null): User
{
    $usuario = User::factory()->create(['is_active' => true, 'contrato_id' => $contratoId]);
    $usuario->assignRole('personal_tecnico');

    return $usuario->fresh();
}

// ── Escalada de privilegios ───────────────────────────────────────────────────

it('impide que un tecnico entre a la gestion de usuarios', function () {
    $this->actingAs(tecnico());

    expect(UserResource::canViewAny())->toBeFalse()
        ->and(UserResource::canCreate())->toBeFalse();
});

it('impide que un tecnico edite cuentas ajenas o la propia', function () {
    $usuario = tecnico();
    $otro = User::factory()->create(['is_active' => true]);

    $this->actingAs($usuario);

    expect($usuario->can('update', $otro))->toBeFalse()
        ->and($usuario->can('update', $usuario))->toBeFalse();
});

it('no deja que nadie se borre a si mismo', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $this->actingAs($admin->fresh());

    expect($admin->can('delete', $admin))->toBeFalse()
        ->and($admin->can('delete', User::factory()->create()))->toBeTrue();
});

it('impide que un tecnico administre carpetas', function () {
    $usuario = tecnico();
    $carpeta = Carpeta::factory()->create();

    $this->actingAs($usuario);

    expect($usuario->can('create', Carpeta::class))->toBeFalse()
        ->and($usuario->can('update', $carpeta))->toBeFalse()
        ->and($usuario->can('delete', $carpeta))->toBeFalse();
});

// ── Documentos confidenciales ─────────────────────────────────────────────────

it('oculta los documentos confidenciales ajenos en los listados', function () {
    $jefe = User::factory()->create(['is_active' => true]);

    $secreto = Documento::factory()->create([
        'confidencial' => true,
        'created_by' => $jefe->id,
        'responsable_id' => $jefe->id,
        'aprobador_id' => $jefe->id,
    ]);
    $normal = Documento::factory()->create(['confidencial' => false]);

    $this->actingAs(tecnico());

    $visibles = DocumentoResource::getEloquentQuery()->pluck('id');

    expect($visibles)->toContain($normal->id)
        ->and($visibles)->not->toContain($secreto->id);
});

it('deja ver el confidencial propio a quien es su responsable', function () {
    $usuario = tecnico();

    $propio = Documento::factory()->create([
        'confidencial' => true,
        'responsable_id' => $usuario->id,
    ]);

    $this->actingAs($usuario);

    expect(DocumentoResource::getEloquentQuery()->pluck('id'))->toContain($propio->id);
});

it('deja ver todos los confidenciales a quien tiene el permiso', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $secreto = Documento::factory()->create(['confidencial' => true]);

    $this->actingAs($admin->fresh());

    expect(DocumentoResource::getEloquentQuery()->pluck('id'))->toContain($secreto->id);
});

it('no muestra confidenciales a quien no ha iniciado sesion', function () {
    Documento::factory()->create(['confidencial' => true]);
    $publico = Documento::factory()->create(['confidencial' => false]);

    $visibles = Documento::query()->visiblePara(null)->pluck('id');

    expect($visibles)->toContain($publico->id)
        ->and($visibles)->toHaveCount(1);
});

// ── Alcance por contrato en las evaluaciones F-14 ─────────────────────────────

it('impide tocar evaluaciones F-14 de otro contrato', function () {
    $propio = ProcedimientoDo::factory()->create();
    $ajeno = ProcedimientoDo::factory()->create();

    $evaluacionPropia = EvaluacionF14::factory()->create(['procedimiento_id' => $propio->id]);
    $evaluacionAjena = EvaluacionF14::factory()->create(['procedimiento_id' => $ajeno->id]);

    $usuario = tecnico($propio->contrato_id);
    $policy = new EvaluacionF14Policy;

    expect($policy->view($usuario, $evaluacionPropia))->toBeTrue()
        ->and($policy->update($usuario, $evaluacionPropia))->toBeTrue()
        ->and($policy->view($usuario, $evaluacionAjena))->toBeFalse()
        ->and($policy->update($usuario, $evaluacionAjena))->toBeFalse()
        ->and($policy->delete($usuario, $evaluacionAjena))->toBeFalse();
});

it('deja a los administradores ver las evaluaciones de cualquier contrato', function () {
    $admin = User::factory()->create(['is_active' => true, 'contrato_id' => null]);
    $admin->assignRole('admin');

    $evaluacion = EvaluacionF14::factory()->create();

    expect((new EvaluacionF14Policy)->view($admin->fresh(), $evaluacion))->toBeTrue();
});
