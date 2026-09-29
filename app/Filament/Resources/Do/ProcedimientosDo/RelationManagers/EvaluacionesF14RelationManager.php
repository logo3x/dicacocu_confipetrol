<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\RelationManagers;

use App\Enums\Do\CriterioOpt;
use App\Filament\Resources\Do\ProcedimientosDo\Schemas\EvaluacionF14Form;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EvaluacionesF14RelationManager extends RelationManager
{
    protected static string $relationship = 'evaluaciones';

    protected static ?string $title = 'Evaluaciones F-14';

    protected static ?string $modelLabel = 'Evaluación F-14';

    protected static ?string $pluralModelLabel = 'Evaluaciones F-14';

    public function form(Schema $schema): Schema
    {
        return EvaluacionF14Form::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('observador:id,name'))
            ->recordTitleAttribute('nombre_actividad_observada')
            ->columns([
                TextColumn::make('fecha_ejecucion')->label('Fecha')->date('d/m/Y')->sortable(),
                TextColumn::make('nombre_actividad_observada')->label('Actividad observada')->wrap()->limit(50),
                TextColumn::make('observador.name')->label('Observador'),
                TextColumn::make('subtotal')->label('Sub-total')->suffix('%'),
                TextColumn::make('puntaje_opt')->label('Total OPT')->suffix('%')->sortable(),
                TextColumn::make('criterio_opt')
                    ->label('Criterio')
                    ->badge()
                    ->formatStateUsing(fn (CriterioOpt $state) => $state->label())
                    ->color(fn (CriterioOpt $state) => $state->color()),
                IconColumn::make('aplica_inspeccion_gerencial')
                    ->label('Insp. gerencial')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Formato de Acompañamiento y Verificación de Actividades (F-14)')
                    ->modalWidth(Width::SevenExtraLarge)
                    ->createAnother(false)
                    ->mutateDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                ViewAction::make()->modalWidth(Width::SevenExtraLarge),
                EditAction::make()->modalWidth(Width::SevenExtraLarge),
                DeleteAction::make(),
            ])
            ->defaultSort('fecha_ejecucion', 'desc');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
