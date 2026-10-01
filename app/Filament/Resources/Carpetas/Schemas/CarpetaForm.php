<?php

namespace App\Filament\Resources\Carpetas\Schemas;

use App\Enums\IconoCarpeta;
use App\Models\Carpeta;
use App\Models\User;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarpetaForm
{
    /**
     * Lo que identifica la carpeta va primero; la apariencia y el orden
     * quedan aparte porque se tocan una vez y no vuelven a mirarse.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
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
                            ->label('Carpeta superior')
                            ->options(fn (?Carpeta $record) => self::carpetasDisponibles($record))
                            ->searchable()
                            ->placeholder('(ninguna — carpeta raíz)')
                            ->native(false)
                            ->helperText('Déjela vacía para crear una carpeta de primer nivel.'),

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
                            ->default(fn () => auth()->id())
                            ->columnSpanFull(),
                    ]),

                Section::make('Apariencia y orden')
                    ->description('Cómo se identifica la carpeta en los listados.')
                    ->collapsed()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('icono')
                            ->label('Ícono')
                            ->options(IconoCarpeta::opciones())
                            ->default(IconoCarpeta::Carpeta->value)
                            ->required()
                            ->native(false)
                            ->selectablePlaceholder(false),

                        ColorPicker::make('color')
                            ->label('Color de identificación')
                            ->required()
                            ->default('#0050A0'),

                        TextInput::make('orden')
                            ->label('Orden de visualización')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Las carpetas con menor número aparecen primero.'),

                        Toggle::make('is_public')
                            ->label('Visible sin iniciar sesión')
                            ->helperText('Cualquier persona podrá ver su contenido desde la página pública.')
                            ->inline(false),
                    ]),
            ]);
    }

    /**
     * Carpetas que pueden ser padre. Se excluye la propia carpeta que se
     * edita para que no quede colgando de sí misma.
     *
     * @return array<int, string>
     */
    private static function carpetasDisponibles(?Carpeta $carpeta): array
    {
        return Carpeta::query()
            ->when($carpeta?->exists, fn ($consulta) => $consulta->whereKeyNot($carpeta->getKey()))
            ->with('parent')
            ->orderBy('nombre')
            ->get()
            ->mapWithKeys(fn (Carpeta $opcion) => [$opcion->getKey() => $opcion->rutaCompleta()])
            ->all();
    }
}
