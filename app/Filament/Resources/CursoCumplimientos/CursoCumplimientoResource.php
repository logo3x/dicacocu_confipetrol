<?php

namespace App\Filament\Resources\CursoCumplimientos;

use App\Filament\Resources\CursoCumplimientos\Pages\CreateCursoCumplimiento;
use App\Filament\Resources\CursoCumplimientos\Pages\EditCursoCumplimiento;
use App\Filament\Resources\CursoCumplimientos\Pages\ListCursoCumplimientos;
use App\Filament\Resources\CursoCumplimientos\Schemas\CursoCumplimientoForm;
use App\Filament\Resources\CursoCumplimientos\Tables\CursoCumplimientosTable;
use App\Models\CursoCumplimiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CursoCumplimientoResource extends Resource
{
    protected static ?string $model = CursoCumplimiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Cursos de Cumplimiento';

    protected static string|\UnitEnum|null $navigationGroup = 'Ciclo DICACOCU';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Curso';

    protected static ?string $pluralModelLabel = 'Cursos de Cumplimiento';

    public static function form(Schema $schema): Schema
    {
        return CursoCumplimientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CursoCumplimientosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCursoCumplimientos::route('/'),
            'create' => CreateCursoCumplimiento::route('/create'),
            'edit' => EditCursoCumplimiento::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
