<?php

namespace App\Filament\Resources\Actividades\Schemas;

use App\Enums\PrioridadAmenaza;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ActividadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación de la actividad')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nombre')
                            ->label('Actividad')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),

                        TextEntry::make('contrato')
                            ->label('Contrato'),

                        TextEntry::make('campo')
                            ->label('Campo')
                            ->placeholder('—'),

                        TextEntry::make('personal_expuesto')
                            ->label('Personal expuesto')
                            ->numeric(),

                        TextEntry::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->placeholder('Sin descripción'),
                    ]),

                Section::make('Valoración de amenaza y plazos')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('valoracion_amenaza')
                            ->label('Valoración (0-100)')
                            ->numeric()
                            ->placeholder('Sin valorar'),

                        TextEntry::make('prioridad_amenaza')
                            ->label('Prioridad')
                            ->badge()
                            ->formatStateUsing(fn (?PrioridadAmenaza $state): string => $state?->label() ?? 'Sin valorar')
                            ->color(fn (?PrioridadAmenaza $state): string => $state?->color() ?? 'gray'),

                        IconEntry::make('estaEstandarizada')
                            ->label('Estandarizada')
                            ->state(fn ($record) => $record->estaEstandarizada())
                            ->boolean(),

                        TextEntry::make('fecha_identificacion')
                            ->label('Fecha de identificación')
                            ->date('d/m/Y')
                            ->placeholder('—'),

                        TextEntry::make('fecha_limite_estandarizacion')
                            ->label('Plazo de estandarización')
                            ->date('d/m/Y')
                            ->placeholder('—')
                            ->color(fn ($record) => $record->estandarizacionVencida() ? 'danger' : null),

                        TextEntry::make('fecha_limite_verificacion')
                            ->label('Próxima verificación')
                            ->date('d/m/Y')
                            ->placeholder('—'),
                    ]),

                Section::make('Responsables')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('creador.name')
                            ->label('Identificada por'),

                        TextEntry::make('responsable.name')
                            ->label('Responsable')
                            ->placeholder('Sin asignar'),

                        TextEntry::make('documento.titulo')
                            ->label('Procedimiento estandarizado')
                            ->placeholder('Aún no codificado'),
                    ]),
            ]);
    }
}
