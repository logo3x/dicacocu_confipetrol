<?php

use App\Enums\ClasificacionOpt;
use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use App\Filament\Resources\AcompanamientosVerificacion\AcompanamientoVerificacionResource;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\CreateAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\EditAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\ListAcompanamientosVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\ViewAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\RelationManagers\AccionesInspeccionRelationManager;
use App\Filament\Resources\AcompanamientosVerificacion\RelationManagers\ReglasSalvaVidasRelationManager;
use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
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

test('la lista de acompanamientos renderiza correctamente', function () {
    AcompanamientoVerificacion::factory()->count(3)->create();

    Livewire::test(ListAcompanamientosVerificacion::class)
        ->assertSuccessful();
});

test('la vista de creacion de acompanamiento renderiza correctamente', function () {
    Livewire::test(CreateAcompanamientoVerificacion::class)
        ->assertSuccessful();
});

test('se puede crear un acompanamiento con checklist desde el formulario', function () {
    $actividad = Actividad::factory()->create();
    $observador = User::factory()->create();

    $checklist = collect(AcompanamientoVerificacion::PREGUNTAS_CHECKLIST)
        ->mapWithKeys(fn (string $campo) => [$campo => true])
        ->all();

    Livewire::test(CreateAcompanamientoVerificacion::class)
        ->fillForm([
            'actividad_id' => $actividad->id,
            'fecha_ejecucion' => now()->toDateString(),
            'tipo_verificacion' => 'verificacion_cumplimiento_do',
            'observador_id' => $observador->id,
            ...$checklist,
            'pasos_segun_procedimiento' => 10,
            'pasos_en_observacion' => 10,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $acompanamiento = AcompanamientoVerificacion::where('actividad_id', $actividad->id)->first();

    expect($acompanamiento)->not->toBeNull()
        ->clasificacion_opt->toBe(ClasificacionOpt::Excelente);
});

test('la vista de detalle de acompanamiento renderiza correctamente', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create();

    Livewire::test(ViewAcompanamientoVerificacion::class, ['record' => $acompanamiento->getRouteKey()])
        ->assertSuccessful();
});

test('la vista de edicion de acompanamiento renderiza correctamente', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create();

    Livewire::test(EditAcompanamientoVerificacion::class, ['record' => $acompanamiento->getRouteKey()])
        ->assertSuccessful();
});

test('la url del recurso usa el slug acompanamientos-verificacion', function () {
    expect(AcompanamientoVerificacionResource::getUrl('index'))
        ->toContain('/admin/acompanamientos-verificacion');
});

// ── RelationManagers de la Parte 2 (Inspección Gerencial) ───────────────────

test('el relation manager de reglas no aparece para una verificacion cumplimiento do', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
    ]);

    expect(ReglasSalvaVidasRelationManager::canViewForRecord($acompanamiento, ViewAcompanamientoVerificacion::class))
        ->toBeFalse();
});

test('el relation manager de reglas renderiza con las 12 reglas para una inspeccion gerencial', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    expect(ReglasSalvaVidasRelationManager::canViewForRecord($acompanamiento->fresh(), ViewAcompanamientoVerificacion::class))
        ->toBeTrue();

    Livewire::test(ReglasSalvaVidasRelationManager::class, [
        'ownerRecord' => $acompanamiento->fresh(),
        'pageClass' => ViewAcompanamientoVerificacion::class,
    ])->assertSuccessful();
});

test('se puede editar el cumplimiento de una regla desde el relation manager', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    $regla = $acompanamiento->fresh()->reglasSalvaVidas()
        ->where('numero_regla', ReglaSalvaVidas::UsoDeEpps->value)
        ->first();

    Livewire::test(ReglasSalvaVidasRelationManager::class, [
        'ownerRecord' => $acompanamiento->fresh(),
        'pageClass' => ViewAcompanamientoVerificacion::class,
    ])
        ->callAction(TestAction::make('edit')->table($regla), data: ['cumple' => CumpleRegla::No->value])
        ->assertHasNoActionErrors();

    expect($regla->fresh()->cumple)->toBe(CumpleRegla::No);
});

test('el relation manager de acciones renderiza y permite crear una accion', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);
    $responsable = User::factory()->create();

    Livewire::test(AccionesInspeccionRelationManager::class, [
        'ownerRecord' => $acompanamiento->fresh(),
        'pageClass' => ViewAcompanamientoVerificacion::class,
    ])
        ->assertSuccessful()
        ->callAction(TestAction::make('create')->table(), data: [
            'accion' => 'Instalar señalización de advertencia',
            'responsable_id' => $responsable->id,
            'fecha_cierre' => now()->addWeek()->toDateString(),
        ])
        ->assertHasNoActionErrors();

    expect($acompanamiento->fresh()->accionesInspeccion()->count())->toBe(1)
        ->and($acompanamiento->fresh()->accionesInspeccion()->first()->responsable_id)->toBe($responsable->id);
});

test('la accion editar parte 2 actualiza hallazgos positivos y desvios', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
    ]);

    Livewire::test(ViewAcompanamientoVerificacion::class, ['record' => $acompanamiento->fresh()->getRouteKey()])
        ->callAction('editarParte2', data: [
            'hallazgos_positivos' => 'Buen uso de EPP en toda el área.',
            'desvios_oportunidades_mejora' => 'Falta señalización en zona de trabajo en caliente.',
        ])
        ->assertHasNoActionErrors();

    expect($acompanamiento->fresh()->inspeccionGerencial->hallazgos_positivos)->toBe('Buen uso de EPP en toda el área.')
        ->and($acompanamiento->fresh()->inspeccionGerencial->desvios_oportunidades_mejora)->toBe('Falta señalización en zona de trabajo en caliente.');
});

test('la accion editar parte 2 no aparece para una verificacion cumplimiento do', function () {
    $acompanamiento = AcompanamientoVerificacion::factory()->create([
        'tipo_verificacion' => 'verificacion_cumplimiento_do',
    ]);

    Livewire::test(ViewAcompanamientoVerificacion::class, ['record' => $acompanamiento->fresh()->getRouteKey()])
        ->assertActionHidden('editarParte2');
});
