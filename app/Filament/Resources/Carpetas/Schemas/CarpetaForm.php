<?php

namespace App\Filament\Resources\Carpetas\Schemas;

use App\Models\User;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CarpetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('codigo')
                    ->label('Código')
                    ->maxLength(50)
                    ->placeholder('ej. HSEQ-PROC'),

                Select::make('parent_id')
                    ->label('Carpeta padre')
                    ->relationship('parent', 'nombre')
                    ->searchable()
                    ->preload()
                    ->placeholder('(ninguna — carpeta raíz)')
                    ->native(false),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('created_by')
                    ->label('Responsable')
                    ->options(fn () => User::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->native(false)
                    ->default(fn () => auth()->id()),

                ColorPicker::make('color')
                    ->label('Color de identificación')
                    ->required()
                    ->default('#0050A0'),

                TextInput::make('icono')
                    ->label('Ícono (Font Awesome)')
                    ->required()
                    ->default('fa-folder')
                    ->placeholder('fa-folder'),

                Toggle::make('is_public')
                    ->label('Visible públicamente')
                    ->helperText('Si está activo, el contenido es visible sin autenticación.')
                    ->inline(false),

                TextInput::make('orden')
                    ->label('Orden de visualización')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}
