<?php

namespace App\Filament\Resources\Documentos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;

class DocumentoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('titulo')
                            ->label('Título')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),

                        TextEntry::make('codigo')
                            ->label('Código')
                            ->fontFamily(FontFamily::Mono)
                            ->copyable(),

                        TextEntry::make('tipo_documento')
                            ->label('Tipo')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'procedimiento' => 'primary',
                                'instructivo' => 'info',
                                'formato' => 'gray',
                                'manual' => 'warning',
                                'politica' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'procedimiento' => 'Procedimiento',
                                'instructivo' => 'Instructivo',
                                'formato' => 'Formato',
                                'manual' => 'Manual',
                                'politica' => 'Política',
                                'norma' => 'Norma',
                                'reglamento' => 'Reglamento',
                                default => $state,
                            }),

                        TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'borrador' => 'gray',
                                'en_revision' => 'warning',
                                'aprobado' => 'success',
                                'divulgado' => 'primary',
                                'verificado' => 'info',
                                'rechazado' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'borrador' => 'Borrador',
                                'en_revision' => 'En revisión',
                                'aprobado' => 'Aprobado',
                                'divulgado' => 'Divulgado',
                                'verificado' => 'Verificado',
                                'rechazado' => 'Rechazado',
                                default => $state,
                            }),

                        TextEntry::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->placeholder('Sin descripción'),
                    ]),

                Section::make('Responsables')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('creador.name')
                            ->label('Creado por'),

                        TextEntry::make('responsable.name')
                            ->label('Responsable')
                            ->placeholder('Sin asignar'),

                        TextEntry::make('aprobador.name')
                            ->label('Aprobador')
                            ->placeholder('Sin asignar'),

                        TextEntry::make('carpeta.nombre')
                            ->label('Carpeta')
                            ->placeholder('Sin carpeta'),
                    ]),

                Section::make('Fechas y Control de versiones')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('fecha_emision')
                            ->label('Fecha de emisión')
                            ->date('d/m/Y')
                            ->placeholder('—'),

                        TextEntry::make('fecha_revision')
                            ->label('Próxima revisión')
                            ->date('d/m/Y')
                            ->placeholder('—'),

                        TextEntry::make('fecha_vencimiento')
                            ->label('Vencimiento')
                            ->date('d/m/Y')
                            ->placeholder('—')
                            ->color(fn ($record) => $record?->estaVencido() ? 'danger' : null),

                        TextEntry::make('version_actual')
                            ->label('Versión actual')
                            ->prefix('v')
                            ->numeric(),

                        TextEntry::make('visitas')
                            ->label('Consultas')
                            ->numeric(),

                        TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i'),
                    ]),

                Section::make('Clasificación')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tags')
                            ->label('Etiquetas')
                            ->badge()
                            ->separator(','),

                        IconEntry::make('requiere_firma')
                            ->label('Requiere firma')
                            ->boolean(),

                        IconEntry::make('confidencial')
                            ->label('Confidencial')
                            ->boolean()
                            ->trueColor('danger')
                            ->falseColor('success'),
                    ]),
            ]);
    }
}
