<?php

use App\Enums\EstadoCompromiso;
use App\Filament\Resources\Compromisos\CompromisoResource;
use App\Filament\Resources\Compromisos\Pages\CreateCompromiso;
use App\Filament\Resources\Compromisos\Pages\EditCompromiso;
use App\Filament\Resources\Compromisos\Pages\ListCompromisos;
use App\Models\Compromiso;
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

test('la lista de compromisos renderiza correctamente', function () {
    Compromiso::factory()->count(3)->create();

    Livewire::test(ListCompromisos::class)
        ->assertSuccessful();
});

test('la vista de creacion de compromiso renderiza correctamente', function () {
    Livewire::test(CreateCompromiso::class)
        ->assertSuccessful();
});

test('se puede crear un compromiso desde el formulario', function () {
    $responsable = User::factory()->create();

    Livewire::test(CreateCompromiso::class)
        ->fillForm([
            'nombre' => 'Entrega de indicadores mensuales',
            'fecha_limite' => now()->addMonth()->toDateString(),
            'responsable_id' => $responsable->id,
            'rol_responsable' => 'calidad_corporativa',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Compromiso::where('nombre', 'Entrega de indicadores mensuales')->first())->not->toBeNull();
});

test('la vista de edicion de compromiso renderiza correctamente', function () {
    $compromiso = Compromiso::factory()->create();

    Livewire::test(EditCompromiso::class, ['record' => $compromiso->getRouteKey()])
        ->assertSuccessful();
});

test('la accion marcar cumplido actualiza el estado del compromiso', function () {
    $compromiso = Compromiso::factory()->create();

    expect($compromiso->estado())->toBe(EstadoCompromiso::Pendiente);

    Livewire::test(ListCompromisos::class)
        ->callTableAction('marcarCumplido', $compromiso)
        ->assertSuccessful();

    expect($compromiso->fresh()->estado())->toBe(EstadoCompromiso::Cumplido);
});

test('la url del recurso usa el slug compromisos', function () {
    expect(CompromisoResource::getUrl('index'))->toContain('/admin/compromisos');
});
