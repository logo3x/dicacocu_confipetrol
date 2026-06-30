<?php

namespace App\Filament\Resources\Carpetas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CarpetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('codigo'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->relationship('parent', 'id'),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('color')
                    ->required()
                    ->default('#0050A0'),
                TextInput::make('icono')
                    ->required()
                    ->default('fa-folder'),
                Toggle::make('is_public')
                    ->required(),
                TextInput::make('orden')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
