<?php

use App\Filament\Resources\Documentos\Pages\CreateDocumento;
use App\Filament\Resources\Documentos\Pages\ListDocumentos;
use App\Filament\Resources\Documentos\Schemas\DocumentoForm;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function administradorDocumental(): User
{
    Permission::findOrCreate('acceder panel admin', 'web');
    Role::findOrCreate('super_admin', 'web')->givePermissionTo('acceder panel admin');

    $usuario = User::factory()->create(['is_active' => true]);
    $usuario->assignRole('super_admin');

    return $usuario->fresh();
}

it('muestra el formulario con sus secciones repartidas', function () {
    $this->actingAs(administradorDocumental());

    Livewire::test(CreateDocumento::class)
        ->assertSchemaStateSet([
            'estado' => 'borrador',
            'tipo_documento' => 'procedimiento',
        ])
        ->assertSee('Información general')
        ->assertSee('Archivo del documento')
        ->assertSee('Responsables')
        ->assertOk();
});

it('guarda un documento desde el formulario reorganizado', function () {
    $this->actingAs(administradorDocumental());

    Livewire::test(CreateDocumento::class)
        ->fillForm([
            'titulo' => 'Procedimiento de izaje de cargas',
            'codigo' => 'HSEQ-IZA-P-01',
            'tipo_documento' => 'procedimiento',
            'estado' => 'borrador',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Documento::where('codigo', 'HSEQ-IZA-P-01')->exists())->toBeTrue();
});

it('lista los documentos con el titulo y su carpeta', function () {
    $documento = Documento::factory()->create(['titulo' => 'Plan de emergencias']);

    $this->actingAs(administradorDocumental());

    Livewire::test(ListDocumentos::class)
        ->assertCanSeeTableRecords([$documento])
        ->assertSee('Plan de emergencias');
});

it('ofrece en los filtros el mismo catalogo que el formulario', function () {
    // Antes el filtro de tipo omitia "Reglamento": ahora comparten la fuente.
    expect(DocumentoForm::tiposDeDocumento())->toHaveKey('reglamento')
        ->and(DocumentoForm::estadosDelDocumento())->toHaveCount(6);
});
