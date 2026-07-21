<?php

namespace App\Filament\Resources\Actividades\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActividadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación de la actividad')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre de la actividad')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('contrato')
                            ->label('Contrato')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('campo')
                            ->label('Campo')
                            ->maxLength(255),

                        TextInput::make('personal_expuesto')
                            ->label('Personal expuesto')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),

                        DatePicker::make('fecha_identificacion')
                            ->label('Fecha de identificación')
                            ->displayFormat('d/m/Y')
                            ->default(now()),

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Valoración de la amenaza')
                    ->description('0-100. Rangos: 80-100 = prioridad baja, 60-79 = media, 0-59 = alta. La prioridad y los plazos de estandarización/verificación se calculan automáticamente.')
                    ->columns(2)
                    ->schema([
                        Slider::make('valoracion_amenaza')
                            ->label('Valoración de amenaza')
                            ->range(0, 100)
                            ->columnSpanFull(),

                        Select::make('responsable_id')
                            ->label('Responsable')
                            ->relationship('responsable', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('documento_id')
                            ->label('Documento / procedimiento estandarizado')
                            ->relationship('documento', 'titulo')
                            ->searchable()
                            ->preload()
                            ->helperText('Vincular una vez el procedimiento haya sido codificado (Etapa 2).'),
                    ]),
            ]);
    }
}
