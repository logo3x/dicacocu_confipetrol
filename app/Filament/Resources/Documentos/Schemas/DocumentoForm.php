<?php

namespace App\Filament\Resources\Documentos\Schemas;

use App\Models\Carpeta;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('codigo')
                            ->label('Código')
                            ->maxLength(50)
                            ->placeholder('Ej: PRO-0001'),

                        Select::make('tipo_documento')
                            ->label('Tipo de documento')
                            ->required()
                            ->options([
                                'procedimiento' => 'Procedimiento',
                                'instructivo'   => 'Instructivo',
                                'formato'       => 'Formato',
                                'manual'        => 'Manual',
                                'politica'      => 'Política',
                                'norma'         => 'Norma',
                                'reglamento'    => 'Reglamento',
                            ])
                            ->default('procedimiento'),

                        Select::make('fase_dicacocu')
                            ->label('Fase DICACOCU')
                            ->options([
                                'D' => 'D — Disponibilidad',
                                'I' => 'I — Integridad',
                                'C' => 'C — Calidad',
                                'A' => 'A — Acceso',
                                'O' => 'O — Operación',
                                'U' => 'U — Uso',
                            ]),

                        Select::make('carpeta_id')
                            ->label('Carpeta')
                            ->relationship('carpeta', 'nombre')
                            ->searchable()
                            ->preload(),

                        Select::make('estado')
                            ->label('Estado')
                            ->required()
                            ->options([
                                'borrador'    => 'Borrador',
                                'en_revision' => 'En revisión',
                                'aprobado'    => 'Aprobado',
                                'divulgado'   => 'Divulgado',
                                'verificado'  => 'Verificado',
                                'rechazado'   => 'Rechazado',
                            ])
                            ->default('borrador'),

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Responsables')
                    ->columns(2)
                    ->schema([
                        Select::make('responsable_id')
                            ->label('Responsable')
                            ->relationship('responsable', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('aprobador_id')
                            ->label('Aprobador')
                            ->relationship('aprobador', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Fechas y Versión')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('fecha_emision')
                            ->label('Fecha de emisión')
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('fecha_revision')
                            ->label('Próxima revisión')
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('fecha_vencimiento')
                            ->label('Vencimiento')
                            ->displayFormat('d/m/Y'),
                    ]),

                Section::make('Clasificación')
                    ->columns(2)
                    ->schema([
                        TagsInput::make('tags')
                            ->label('Etiquetas')
                            ->columnSpanFull(),

                        Toggle::make('requiere_firma')
                            ->label('Requiere firma'),

                        Toggle::make('confidencial')
                            ->label('Documento confidencial'),
                    ]),
            ]);
    }
}
