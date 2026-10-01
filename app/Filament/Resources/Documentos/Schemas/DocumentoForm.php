<?php

namespace App\Filament\Resources\Documentos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentoForm
{
    /**
     * El documento y su archivo ocupan la columna ancha; lo administrativo
     * (estado, responsables, fechas) acompaña en una barra lateral.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(2)
                    ->columns(1)
                    ->schema(self::columnaPrincipal()),

                Group::make()
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema(self::barraLateral()),
            ]);
    }

    /** @return array<int, mixed> */
    private static function columnaPrincipal(): array
    {
        return [
            Section::make('Información general')
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
                        ->options(self::tiposDeDocumento())
                        ->default('procedimiento'),

                    Select::make('carpeta_id')
                        ->label('Carpeta')
                        ->relationship('carpeta', 'nombre')
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),

                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Archivo del documento')
                ->description('PDF, Word, Excel o PowerPoint. Se guarda como una versión del documento.')
                ->schema([
                    FileUpload::make('archivo_principal')
                        ->hiddenLabel()
                        ->disk('local')
                        ->directory('documentos')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        ])
                        ->maxSize(51200)
                        ->downloadable()
                        ->openable()
                        ->previewable(false)
                        ->columnSpanFull()
                        ->helperText('Máximo 50 MB.'),
                ]),

            Section::make('Adjuntos adicionales')
                ->description('Anexos de apoyo: soportes, planos, registros.')
                ->collapsed()
                ->schema([
                    FileUpload::make('adjuntos')
                        ->hiddenLabel()
                        ->multiple()
                        ->disk('local')
                        ->directory('adjuntos')
                        ->visibility('private')
                        ->maxSize(20480)
                        ->maxFiles(10)
                        ->downloadable()
                        ->previewable(false)
                        ->columnSpanFull()
                        ->helperText('Hasta 10 archivos de 20 MB cada uno.'),
                ]),
        ];
    }

    /** @return array<int, mixed> */
    private static function barraLateral(): array
    {
        return [
            Section::make('Estado')
                ->schema([
                    Select::make('estado')
                        ->hiddenLabel()
                        ->required()
                        ->options(self::estadosDelDocumento())
                        ->default('borrador')
                        ->columnSpanFull(),
                ]),

            Section::make('Responsables')
                ->schema([
                    Select::make('responsable_id')
                        ->label('Responsable')
                        ->relationship('responsable', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),

                    Select::make('aprobador_id')
                        ->label('Aprobador')
                        ->relationship('aprobador', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),

            Section::make('Fechas')
                ->schema([
                    DatePicker::make('fecha_emision')
                        ->label('Emisión')
                        ->displayFormat('d/m/Y')
                        ->columnSpanFull(),

                    DatePicker::make('fecha_revision')
                        ->label('Próxima revisión')
                        ->displayFormat('d/m/Y')
                        ->columnSpanFull(),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Vencimiento')
                        ->displayFormat('d/m/Y')
                        ->columnSpanFull(),
                ]),

            Section::make('Clasificación')
                ->schema([
                    TagsInput::make('tags')
                        ->label('Etiquetas')
                        ->placeholder('Escriba y pulse Enter')
                        ->columnSpanFull(),

                    Toggle::make('requiere_firma')
                        ->label('Requiere firma')
                        ->inline(false)
                        ->columnSpanFull(),

                    Toggle::make('confidencial')
                        ->label('Documento confidencial')
                        ->helperText('Restringe quién puede consultarlo.')
                        ->inline(false)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /** @return array<string, string> */
    public static function tiposDeDocumento(): array
    {
        return [
            'procedimiento' => 'Procedimiento',
            'instructivo' => 'Instructivo',
            'formato' => 'Formato',
            'manual' => 'Manual',
            'politica' => 'Política',
            'norma' => 'Norma',
            'reglamento' => 'Reglamento',
        ];
    }

    /** @return array<string, string> */
    public static function estadosDelDocumento(): array
    {
        return [
            'borrador' => 'Borrador',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobado',
            'divulgado' => 'Divulgado',
            'verificado' => 'Verificado',
            'rechazado' => 'Rechazado',
        ];
    }
}
