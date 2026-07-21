<?php

namespace App\Filament\Resources\AcompanamientosVerificacion;

use App\Filament\Resources\AcompanamientosVerificacion\Pages\CreateAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\EditAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\ListAcompanamientosVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\Pages\ViewAcompanamientoVerificacion;
use App\Filament\Resources\AcompanamientosVerificacion\RelationManagers\AccionesInspeccionRelationManager;
use App\Filament\Resources\AcompanamientosVerificacion\RelationManagers\ReglasSalvaVidasRelationManager;
use App\Filament\Resources\AcompanamientosVerificacion\Schemas\AcompanamientoVerificacionForm;
use App\Filament\Resources\AcompanamientosVerificacion\Schemas\AcompanamientoVerificacionInfolist;
use App\Filament\Resources\AcompanamientosVerificacion\Tables\AcompanamientosVerificacionTable;
use App\Models\AcompanamientoVerificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcompanamientoVerificacionResource extends Resource
{
    protected static ?string $model = AcompanamientoVerificacion::class;

    protected static ?string $slug = 'acompanamientos-verificacion';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Acompañamientos F-14';

    protected static string|\UnitEnum|null $navigationGroup = 'Disciplina Operativa';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Acompañamiento y verificación';

    protected static ?string $pluralModelLabel = 'Acompañamientos y verificación';

    public static function form(Schema $schema): Schema
    {
        return AcompanamientoVerificacionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AcompanamientoVerificacionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcompanamientosVerificacionTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ReglasSalvaVidasRelationManager::class,
            AccionesInspeccionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcompanamientosVerificacion::route('/'),
            'create' => CreateAcompanamientoVerificacion::route('/create'),
            'view' => ViewAcompanamientoVerificacion::route('/{record}'),
            'edit' => EditAcompanamientoVerificacion::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
