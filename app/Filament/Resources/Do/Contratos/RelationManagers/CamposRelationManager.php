<?php

namespace App\Filament\Resources\Do\Contratos\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CamposRelationManager extends RelationManager
{
    protected static string $relationship = 'campos';

    protected static ?string $title = 'Campos';

    protected static ?string $modelLabel = 'Campo';

    protected static ?string $pluralModelLabel = 'Campos';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->label('Nombre')->required()->maxLength(191),
            TextInput::make('codigo')->label('Código')->maxLength(191),
            TextInput::make('ubicacion')->label('Ubicación')->maxLength(191),
            Toggle::make('activo')->label('Activo')->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('codigo')->label('Código')->searchable()->toggleable(),
                TextColumn::make('ubicacion')->label('Ubicación')->toggleable(),
                TextColumn::make('procedimientos_count')->label('Procedimientos')->counts('procedimientos'),
                IconColumn::make('activo')->label('Activo')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Nuevo campo'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('nombre');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
