<?php

namespace App\Filament\Resources\Carpetas;

use App\Filament\Resources\Carpetas\Pages\CreateCarpeta;
use App\Filament\Resources\Carpetas\Pages\EditCarpeta;
use App\Filament\Resources\Carpetas\Pages\ListCarpetas;
use App\Filament\Resources\Carpetas\Schemas\CarpetaForm;
use App\Filament\Resources\Carpetas\Tables\CarpetasTable;
use App\Models\Carpeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarpetaResource extends Resource
{
    protected static ?string $model = Carpeta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Carpetas';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestión Documental';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Carpeta';

    protected static ?string $pluralModelLabel = 'Carpetas';

    public static function form(Schema $schema): Schema
    {
        return CarpetaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarpetasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarpetas::route('/'),
            'create' => CreateCarpeta::route('/create'),
            'edit' => EditCarpeta::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
