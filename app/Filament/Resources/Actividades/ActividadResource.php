<?php

namespace App\Filament\Resources\Actividades;

use App\Filament\Resources\Actividades\Pages\CreateActividad;
use App\Filament\Resources\Actividades\Pages\EditActividad;
use App\Filament\Resources\Actividades\Pages\ListActividades;
use App\Filament\Resources\Actividades\Pages\ViewActividad;
use App\Filament\Resources\Actividades\RelationManagers\PersonalExpuestoRelationManager;
use App\Filament\Resources\Actividades\Schemas\ActividadForm;
use App\Filament\Resources\Actividades\Schemas\ActividadInfolist;
use App\Filament\Resources\Actividades\Tables\ActividadesTable;
use App\Models\Actividad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActividadResource extends Resource
{
    protected static ?string $model = Actividad::class;

    protected static ?string $slug = 'actividades';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Actividades';

    protected static string|\UnitEnum|null $navigationGroup = 'Disciplina Operativa';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Actividad';

    protected static ?string $pluralModelLabel = 'Actividades';

    public static function form(Schema $schema): Schema
    {
        return ActividadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActividadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActividadesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PersonalExpuestoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActividades::route('/'),
            'create' => CreateActividad::route('/create'),
            'view' => ViewActividad::route('/{record}'),
            'edit' => EditActividad::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
