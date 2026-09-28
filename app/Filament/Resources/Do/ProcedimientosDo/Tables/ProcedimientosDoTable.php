<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Tables;

use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;
use App\Models\Do\ProcedimientoDo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProcedimientosDoTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['contrato:id,nombre', 'campo:id,nombre']))
            ->columns([
                TextColumn::make('anio_ciclo')->label('Año')->sortable(),
                TextColumn::make('nombre_actividad')
                    ->label('Actividad')
                    ->searchable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('contrato.nombre')->label('Contrato')->searchable()->sortable()->toggleable(),
                TextColumn::make('campo.nombre')->label('Campo')->searchable()->sortable()->toggleable(),
                TextColumn::make('puntaje_prioridad')->label('Puntaje')->sortable(),
                TextColumn::make('prioridad')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn (PrioridadDo $state) => $state->label())
                    ->color(fn (PrioridadDo $state) => $state->color())
                    ->sortable(),
                TextColumn::make('codigo_asignado')
                    ->label('Código')
                    ->searchable()
                    ->placeholder('Sin codificar'),
                TextColumn::make('version_actual')->label('Versión')->toggleable(),
                TextColumn::make('cobertura_socializacion')
                    ->label('Cobertura CO')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('puntaje_opt')
                    ->label('OPT')
                    ->suffix('%')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('criterio_opt')
                    ->label('Criterio OPT')
                    ->badge()
                    ->formatStateUsing(fn (?CriterioOpt $state) => $state?->label() ?? 'Sin datos')
                    ->color(fn (?CriterioOpt $state) => $state?->color() ?? 'gray'),
                TextColumn::make('fecha_limite_estandarizacion')
                    ->label('Límite estandarización')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('prioridad')
                    ->label('Prioridad')
                    ->options(fn () => collect(PrioridadDo::cases())
                        ->mapWithKeys(fn (PrioridadDo $p) => [$p->value => $p->label()])
                        ->all()),
                SelectFilter::make('contrato_id')
                    ->label('Contrato')
                    ->relationship('contrato', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('anio_ciclo')
                    ->label('Año del ciclo')
                    ->options(fn () => ProcedimientoDo::query()
                        ->distinct()
                        ->orderByDesc('anio_ciclo')
                        ->pluck('anio_ciclo', 'anio_ciclo')
                        ->all()),
                TernaryFilter::make('codificado')->label('Codificado'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('puntaje_prioridad', 'desc');
    }
}
