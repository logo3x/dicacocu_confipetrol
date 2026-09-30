<?php

namespace App\Filament\Widgets;

use App\Enums\Do\CriterioOpt;
use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Models\Do\EvaluacionF14;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

/**
 * Últimas verificaciones en campo con su puntaje OPT.
 */
class VerificacionesRecientesWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::user()?->can('ver procedimientos do') ?? false;
    }

    public function table(Table $table): Table
    {
        $usuario = Auth::user();

        return $table
            ->heading('Últimas verificaciones en campo (F-14)')
            ->description('Evaluaciones del formato F-14 con su puntaje OPT.')
            ->query(
                EvaluacionF14::query()
                    ->with(['procedimiento:id,nombre_actividad,contrato_id', 'observador:id,name'])
                    // Cada quien ve las verificaciones de su contrato.
                    ->when(
                        $usuario && ! $usuario->veTodosLosContratos(),
                        fn ($consulta) => $consulta->whereHas(
                            'procedimiento',
                            fn ($p) => $p->where('contrato_id', $usuario->contrato_id),
                        ),
                    )
                    ->latest('fecha_ejecucion')
            )
            ->columns([
                TextColumn::make('fecha_ejecucion')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('procedimiento.nombre_actividad')
                    ->label('Procedimiento')
                    ->limit(40)
                    ->placeholder('—')
                    ->tooltip(fn (EvaluacionF14 $registro) => $registro->procedimiento?->nombre_actividad),

                TextColumn::make('observador.name')
                    ->label('Observador')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('puntaje_opt')
                    ->label('Puntaje OPT')
                    ->suffix('%')
                    ->alignEnd()
                    ->fontFamily('mono'),

                TextColumn::make('criterio_opt')
                    ->label('Criterio')
                    ->badge()
                    ->formatStateUsing(fn (?CriterioOpt $state) => $state?->label() ?? 'Sin datos')
                    ->color(fn (?CriterioOpt $state) => $state?->color() ?? 'gray'),
            ])
            ->recordActions([
                Action::make('abrir')
                    ->label('Abrir')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->visible(fn (EvaluacionF14 $registro): bool => $registro->procedimiento !== null)
                    ->url(fn (EvaluacionF14 $registro): string => ProcedimientoDoResource::getUrl('edit', ['record' => $registro->procedimiento])),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Sin verificaciones registradas')
            ->emptyStateDescription('Las evaluaciones del formato F-14 aparecerán aquí.');
    }
}
