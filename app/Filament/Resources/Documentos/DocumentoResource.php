<?php

namespace App\Filament\Resources\Documentos;

use App\Filament\Resources\Documentos\Pages\CreateDocumento;
use App\Filament\Resources\Documentos\Pages\EditDocumento;
use App\Filament\Resources\Documentos\Pages\ListDocumentos;
use App\Filament\Resources\Documentos\Pages\ViewDocumento;
use App\Filament\Resources\Documentos\RelationManagers\VersionesRelationManager;
use App\Filament\Resources\Documentos\Schemas\DocumentoForm;
use App\Filament\Resources\Documentos\Schemas\DocumentoInfolist;
use App\Filament\Resources\Documentos\Tables\DocumentosTable;
use App\Models\Documento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static ?string $navigationLabel = 'Documentos';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión Documental';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Documento';

    protected static ?string $pluralModelLabel = 'Documentos';

    /**
     * Un documento confidencial no debe asomar en el listado aunque no se
     * pueda abrir: el título, el código y la descripción ya dicen demasiado.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visiblePara(Auth::user());
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VersionesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentos::route('/'),
            'create' => CreateDocumento::route('/create'),
            'view' => ViewDocumento::route('/{record}'),
            'edit' => EditDocumento::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
