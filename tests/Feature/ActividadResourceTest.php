<?php

use App\Enums\EstadoSocializacion;
use App\Enums\PrioridadAmenaza;
use App\Filament\Resources\Actividades\ActividadResource;
use App\Filament\Resources\Actividades\Pages\CreateActividad;
use App\Filament\Resources\Actividades\Pages\EditActividad;
use App\Filament\Resources\Actividades\Pages\ListActividades;
use App\Filament\Resources\Actividades\Pages\ViewActividad;
use App\Filament\Resources\Actividades\RelationManagers\PersonalExpuestoRelationManager;
use App\Models\Actividad;
use App\Models\ActividadPersonalExpuesto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'acceder panel admin', 'guard_name' => 'web']);

    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole($role);

    $this->actingAs($user);
});

test('la lista de actividades renderiza correctamente', function () {
    Actividad::factory()->count(3)->create();

    Livewire::test(ListActividades::class)
        ->assertSuccessful();
});

test('la vista de creacion de actividad renderiza correctamente', function () {
    Livewire::test(CreateActividad::class)
        ->assertSuccessful();
});

test('se puede crear una actividad desde el formulario', function () {
    Livewire::test(CreateActividad::class)
        ->fillForm([
            'nombre' => 'Mantenimiento de válvulas de seguridad',
            'contrato' => 'CONT-0001',
            'campo' => 'Cusiana',
            'personal_expuesto' => 8,
            'valoracion_amenaza' => 45,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Actividad::where('nombre', 'Mantenimiento de válvulas de seguridad')->first())
        ->not->toBeNull()
        ->prioridad_amenaza->toBe(PrioridadAmenaza::Alto);
});

test('la vista de detalle de actividad renderiza correctamente', function () {
    $actividad = Actividad::factory()->create();

    Livewire::test(ViewActividad::class, ['record' => $actividad->getRouteKey()])
        ->assertSuccessful();
});

test('la vista de edicion de actividad renderiza correctamente', function () {
    $actividad = Actividad::factory()->create();

    Livewire::test(EditActividad::class, ['record' => $actividad->getRouteKey()])
        ->assertSuccessful();
});

test('la url del recurso usa el slug actividades sin duplicacion', function () {
    expect(ActividadResource::getUrl('index'))->toContain('/admin/actividades')
        ->and(ActividadResource::getUrl('index'))->not->toContain('actividads');
});

test('el relation manager de personal expuesto renderiza dentro de la actividad', function () {
    $actividad = Actividad::factory()->create();
    ActividadPersonalExpuesto::factory()->create(['actividad_id' => $actividad->id]);

    Livewire::test(PersonalExpuestoRelationManager::class, [
        'ownerRecord' => $actividad,
        'pageClass' => ViewActividad::class,
    ])->assertSuccessful();
});

test('la accion socializar ahora actualiza el estado del personal expuesto', function () {
    $actividad = Actividad::factory()->create();
    $registro = ActividadPersonalExpuesto::factory()->create(['actividad_id' => $actividad->id]);

    expect($registro->estado())->toBe(EstadoSocializacion::Pendiente);

    Livewire::test(PersonalExpuestoRelationManager::class, [
        'ownerRecord' => $actividad,
        'pageClass' => ViewActividad::class,
    ])
        ->callTableAction('socializar', $registro)
        ->assertSuccessful();

    expect($registro->fresh()->estado())->toBe(EstadoSocializacion::Vigente);
});
