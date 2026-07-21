<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\Tables;

use App\Enums\ClasificacionOpt;
use App\Filament\Resources\AcompanamientosVerificacion\AcompanamientoVerificacionResource;
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

class AcompanamientosVerificacionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('actividad.nombre')
                    ->label('Actividad')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->actividad?->nombre),

                TextColumn::make('fecha_ejecucion')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('tipo_verificacion')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'verificacion_cumplimiento_do' => 'Verificación DO',
                        'inspeccion_gerencial_caminar_planta' => 'Caminar la planta',
                        default => $state,
                    })
                    ->toggleable(),

                TextColumn::make('observador.name')
                    ->label('Observador')
                    ->toggleable(),

                TextColumn::make('acompanante.name')
                    ->label('Acompañante')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('puntaje_opt_calculado')
                    ->label('% Cumplimiento')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('—'),

                TextColumn::make('clasificacion_opt')
                    ->label('Clasificación')
                    ->badge()
                    ->formatStateUsing(fn (?ClasificacionOpt $state): string => $state?->label() ?? 'Sin evaluar')
                    ->color(fn (?ClasificacionOpt $state): string => $state?->color() ?? 'gray'),

                IconColumn::make('actividad_detenida')
                    ->label('Detenida')
                    ->boolean()
                    ->trueColor('danger')
                    ->alignCenter(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha_ejecucion', 'desc')
            ->filters([
                SelectFilter::make('clasificacion_opt')
                    ->label('Clasificación')
                    ->options([
                        ClasificacionOpt::Excelente->value => ClasificacionOpt::Excelente->label(),
                        ClasificacionOpt::Bueno->value => ClasificacionOpt::Bueno->label(),
                        ClasificacionOpt::Regular->value => ClasificacionOpt::Regular->label(),
                        ClasificacionOpt::Deficiente->value => ClasificacionOpt::Deficiente->label(),
                    ]),

                SelectFilter::make('tipo_verificacion')
                    ->label('Tipo')
                    ->options([
                        'verificacion_cumplimiento_do' => 'Verificación DO',
                        'inspeccion_gerencial_caminar_planta' => 'Caminar la planta',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordUrl(fn ($record) => AcompanamientoVerificacionResource::getUrl('view', ['record' => $record]))
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
