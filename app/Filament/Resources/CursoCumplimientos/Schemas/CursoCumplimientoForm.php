<?php

namespace App\Filament\Resources\CursoCumplimientos\Schemas;

use App\Models\Documento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CursoCumplimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Curso')
                    ->columns(2)
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título del curso')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('fase_dicacocu')
                            ->label('Fase DICACOCU asociada')
                            ->options([
                                'D' => 'D — Disponibilidad',
                                'I' => 'I — Integridad',
                                'C' => 'C — Calidad',
                                'A' => 'A — Acceso',
                                'O' => 'O — Operación',
                                'U' => 'U — Uso',
                            ]),

                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activo' => 'Activo',
                                'inactivo' => 'Inactivo',
                                'borrador' => 'Borrador',
                            ])
                            ->default('activo')
                            ->required(),

                        TextInput::make('nota_aprobacion')
                            ->label('Nota mínima de aprobación (%)')
                            ->numeric()
                            ->default(70)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),

                        DatePicker::make('fecha_limite')
                            ->label('Fecha límite de completado')
                            ->displayFormat('d/m/Y'),

                        Toggle::make('certificado_activo')
                            ->label('Emitir certificado al completar')
                            ->columnSpanFull(),
                    ]),

                Section::make('Documentos del Curso')
                    ->description('Seleccione los documentos que forman parte de este curso.')
                    ->schema([
                        Select::make('documentos_ids')
                            ->label('Documentos incluidos')
                            ->multiple()
                            ->options(
                                Documento::whereIn('estado', ['aprobado', 'divulgado'])
                                    ->orderBy('titulo')
                                    ->pluck('titulo', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Section::make('Preguntas de Evaluación')
                    ->description('Agregue preguntas de opción múltiple para evaluar la comprensión del curso.')
                    ->schema([
                        Repeater::make('preguntas')
                            ->label('Preguntas')
                            ->schema([
                                TextInput::make('pregunta')
                                    ->label('Pregunta')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('opcion_a')
                                    ->label('Opción A')
                                    ->required(),

                                TextInput::make('opcion_b')
                                    ->label('Opción B')
                                    ->required(),

                                TextInput::make('opcion_c')
                                    ->label('Opción C'),

                                TextInput::make('opcion_d')
                                    ->label('Opción D'),

                                Select::make('respuesta_correcta')
                                    ->label('Respuesta correcta')
                                    ->options([
                                        'a' => 'Opción A',
                                        'b' => 'Opción B',
                                        'c' => 'Opción C',
                                        'd' => 'Opción D',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Agregar pregunta')
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
