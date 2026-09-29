<?php

namespace App\Filament\Resources\Do\Campos;

use App\Filament\Resources\Do\Campos\Pages\ListCampos;
use App\Models\Do\Campo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CampoResource extends Resource
{
    protected static ?string $model = Campo::class;

    protected static ?string $slug = 'do-campos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Campos';

    protected static string|\UnitEnum|null $navigationGroup = 'Procedimientos DICACOCU';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Campo';

    protected static ?string $pluralModelLabel = 'Campos';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('contrato_id')
                ->label('Contrato')
                ->relationship('contrato', 'nombre')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('nombre')->label('Nombre')->required()->maxLength(191),
            TextInput::make('codigo')->label('Código')->maxLength(191),
            TextInput::make('ubicacion')->label('Ubicación')->maxLength(191),
            Toggle::make('activo')->label('Activo')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contrato.zona')->label('Zona')->badge()->sortable()->toggleable(),
                TextColumn::make('contrato.nombre')->label('Contrato')->searchable()->sortable(),
                TextColumn::make('nombre')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('codigo')->label('Código')->searchable()->toggleable(),
                TextColumn::make('ubicacion')->label('Ubicación')->toggleable(),
                TextColumn::make('procedimientos_count')->label('Procedimientos')->counts('procedimientos'),
                IconColumn::make('activo')->label('Activo')->boolean(),
            ])
            ->filters([
                SelectFilter::make('contrato_id')
                    ->label('Contrato')
                    ->relationship('contrato', 'nombre')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('activo')->label('Activo'),
            ])
            ->headerActions([
                CreateAction::make()->label('Nuevo campo'),
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

    public static function getPages(): array
    {
        return [
            'index' => ListCampos::route('/'),
        ];
    }
}
