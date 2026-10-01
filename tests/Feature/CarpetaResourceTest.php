<?php

use App\Enums\IconoCarpeta;
use App\Filament\Resources\Carpetas\Pages\CreateCarpeta;
use App\Filament\Resources\Carpetas\Pages\EditCarpeta;
use App\Filament\Resources\Carpetas\Pages\ListCarpetas;
use App\Models\Carpeta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function gestorDocumental(): User
{
    Permission::findOrCreate('acceder panel admin', 'web');
    Role::findOrCreate('super_admin', 'web')->givePermissionTo('acceder panel admin');

    $usuario = User::factory()->create(['is_active' => true]);
    $usuario->assignRole('super_admin');

    return $usuario->fresh();
}

it('calcula la profundidad de cada carpeta', function () {
    $raiz = Carpeta::factory()->create(['parent_id' => null]);
    $hija = Carpeta::factory()->create(['parent_id' => $raiz->id]);
    $nieta = Carpeta::factory()->create(['parent_id' => $hija->id]);

    expect($raiz->profundidad())->toBe(0)
        ->and($hija->profundidad())->toBe(1)
        ->and($nieta->fresh()->profundidad())->toBe(2);
});

it('arma la ruta completa desde la raiz', function () {
    $raiz = Carpeta::factory()->create(['nombre' => 'HSEQ', 'parent_id' => null]);
    $hija = Carpeta::factory()->create(['nombre' => 'Procedimientos', 'parent_id' => $raiz->id]);

    expect($hija->rutaCompleta())->toBe('HSEQ / Procedimientos');
});

it('muestra las subcarpetas debajo de su carpeta superior', function () {
    $raiz = Carpeta::factory()->create(['nombre' => 'Operaciones', 'parent_id' => null, 'orden' => 1]);
    $hija = Carpeta::factory()->create(['nombre' => 'Mantenimiento', 'parent_id' => $raiz->id, 'orden' => 1]);

    $this->actingAs(gestorDocumental());

    Livewire::test(ListCarpetas::class)
        ->assertCanSeeTableRecords([$raiz, $hija])
        ->assertOk();
});

it('crea una carpeta con el icono elegido de la lista', function () {
    $usuario = gestorDocumental();
    $this->actingAs($usuario);

    Livewire::test(CreateCarpeta::class)
        ->fillForm([
            'nombre' => 'Procedimientos de izaje',
            'codigo' => 'HSEQ-IZA',
            'created_by' => $usuario->id,
            'icono' => IconoCarpeta::Seguridad->value,
            'color' => '#0050A0',
            'orden' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Carpeta::where('codigo', 'HSEQ-IZA')->first()->icono)
        ->toBe(IconoCarpeta::Seguridad->value);
});

it('traduce los iconos de Font Awesome que quedaron guardados', function () {
    // El panel nunca cargo Font Awesome, asi que esos valores no se veian.
    expect(IconoCarpeta::desdeValor('fa-shield-halved'))->toBe(IconoCarpeta::Seguridad)
        ->and(IconoCarpeta::desdeValor('fa-gavel'))->toBe(IconoCarpeta::Legal)
        ->and(IconoCarpeta::desdeValor(null))->toBe(IconoCarpeta::Carpeta)
        ->and(IconoCarpeta::desdeValor('algo-raro'))->toBe(IconoCarpeta::Carpeta);
});

it('no se ofrece a si misma como carpeta superior', function () {
    $carpeta = Carpeta::factory()->create(['nombre' => 'Sola']);

    $this->actingAs(gestorDocumental());

    Livewire::test(EditCarpeta::class, [
        'record' => $carpeta->getKey(),
    ])->assertOk();
});
