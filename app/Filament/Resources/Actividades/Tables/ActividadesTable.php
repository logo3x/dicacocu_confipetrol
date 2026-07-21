<?php

namespace App\Filament\Resources\Actividades\Tables;

use App\Enums\PrioridadAmenaza;
use App\Filament\Resources\Actividades\ActividadResource;
use App\Models\Actividad;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ActividadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Actividad')
                    ->searchable()
                    ->limit(55)
                    ->tooltip(fn ($record) => $record->nombre),

                TextColumn::make('contrato')
                    ->label('Contrato')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('campo')
                    ->label('Campo')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('valoracion_amenaza')
                    ->label('Amenaza')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('—'),

                TextColumn::make('prioridad_amenaza')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn (?PrioridadAmenaza $state): string => $state?->label() ?? 'Sin valorar')
                    ->color(fn (?PrioridadAmenaza $state): string => $state?->color() ?? 'gray'),

                TextColumn::make('personal_expuesto')
                    ->label('Pers. exp.')
                    ->numeric()
                    ->alignCenter()
                    ->toggleable(),

                IconColumn::make('estaEstandarizada')
                    ->label('Estandarizada')
                    ->boolean()
                    ->state(fn ($record) => $record->estaEstandarizada())
                    ->alignCenter(),

                TextColumn::make('fecha_limite_estandarizacion')
                    ->label('Plazo estandarización')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->estandarizacionVencida() ? 'danger' : null)
                    ->placeholder('—'),

                TextColumn::make('fecha_limite_verificacion')
                    ->label('Próxima verificación')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('prioridad_amenaza')
                    ->label('Prioridad')
                    ->options([
                        PrioridadAmenaza::Bajo->value => PrioridadAmenaza::Bajo->label(),
                        PrioridadAmenaza::Medio->value => PrioridadAmenaza::Medio->label(),
                        PrioridadAmenaza::Alto->value => PrioridadAmenaza::Alto->label(),
                    ]),

                SelectFilter::make('contrato')
                    ->label('Contrato')
                    ->options(fn () => Actividad::query()
                        ->distinct()
                        ->pluck('contrato', 'contrato')
                        ->toArray()),

                TrashedFilter::make(),
            ])
            ->recordUrl(fn ($record) => ActividadResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
