<?php

namespace App\Filament\Resources\Do\Contratos;

use App\Filament\Resources\Do\Contratos\Pages\CreateContrato;
use App\Filament\Resources\Do\Contratos\Pages\EditContrato;
use App\Filament\Resources\Do\Contratos\Pages\ListContratos;
use App\Filament\Resources\Do\Contratos\RelationManagers\CamposRelationManager;
use App\Models\Do\Contrato;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContratoResource extends Resource
{
    protected static ?string $model = Contrato::class;

    protected static ?string $slug = 'do-contratos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $navigationLabel = 'Contratos';

    protected static string|\UnitEnum|null $navigationGroup = 'Procedimientos DICACOCU';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Contrato';

    protected static ?string $pluralModelLabel = 'Contratos';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del contrato')
                ->columns(2)
                ->schema([
                    TextInput::make('codigo')
                        ->label('Código')
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),
                    TextInput::make('nombre')->label('Nombre')->required()->maxLength(191),
                    Select::make('zona')
                        ->label('Zona')
                        ->options(fn () => Contrato::query()
                            ->whereNotNull('zona')
                            ->distinct()
                            ->orderBy('zona')
                            ->pluck('zona', 'zona')
                            ->all())
                        ->searchable()
                        ->native(false)
                        ->helperText('Escriba para buscar o agregar una zona nueva'),
                    TextInput::make('cliente')->label('Cliente')->maxLength(191),
                    Toggle::make('activo')->label('Activo')->default(true),
                    Textarea::make('descripcion')->label('Descripción')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')->label('Código')->searchable()->sortable(),
                TextColumn::make('nombre')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('zona')->label('Zona')->badge()->searchable()->sortable(),
                TextColumn::make('cliente')->label('Cliente')->searchable()->toggleable(),
                TextColumn::make('campos_count')->label('Campos')->counts('campos'),
                TextColumn::make('procedimientos_count')->label('Procedimientos')->counts('procedimientos'),
                IconColumn::make('activo')->label('Activo')->boolean(),
            ])
            ->filters([
                SelectFilter::make('zona')
                    ->label('Zona')
                    ->options(fn () => Contrato::query()
                        ->whereNotNull('zona')
                        ->distinct()
                        ->orderBy('zona')
                        ->pluck('zona', 'zona')
                        ->all()),
                TernaryFilter::make('activo')->label('Activo'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nombre');
    }

    public static function getRelations(): array
    {
        return [
            CamposRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContratos::route('/'),
            'create' => CreateContrato::route('/create'),
            'edit' => EditContrato::route('/{record}/edit'),
        ];
    }
}
