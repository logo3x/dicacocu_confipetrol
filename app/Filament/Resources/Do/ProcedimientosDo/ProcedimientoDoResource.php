<?php

namespace App\Filament\Resources\Do\ProcedimientosDo;

use App\Filament\Resources\Do\ProcedimientosDo\Pages\CreateProcedimientoDo;
use App\Filament\Resources\Do\ProcedimientosDo\Pages\EditProcedimientoDo;
use App\Filament\Resources\Do\ProcedimientosDo\Pages\ListProcedimientosDo;
use App\Filament\Resources\Do\ProcedimientosDo\RelationManagers\EvaluacionesF14RelationManager;
use App\Filament\Resources\Do\ProcedimientosDo\Schemas\ProcedimientoDoForm;
use App\Filament\Resources\Do\ProcedimientosDo\Tables\ProcedimientosDoTable;
use App\Models\Do\ProcedimientoDo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedimientoDoResource extends Resource
{
    protected static ?string $model = ProcedimientoDo::class;

    protected static ?string $slug = 'procedimientos-do';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Procedimientos';

    protected static string|\UnitEnum|null $navigationGroup = 'Procedimientos DICACOCU';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Procedimiento';

    protected static ?string $pluralModelLabel = 'Procedimientos';

    public static function form(Schema $schema): Schema
    {
        return ProcedimientoDoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcedimientosDoTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EvaluacionesF14RelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProcedimientosDo::route('/'),
            'create' => CreateProcedimientoDo::route('/create'),
            'edit' => EditProcedimientoDo::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
