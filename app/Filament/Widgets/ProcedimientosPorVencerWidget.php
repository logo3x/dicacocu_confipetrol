<?php

namespace App\Filament\Widgets;

use App\Enums\Do\PrioridadDo;
use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Models\Do\ProcedimientoDo;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Procedimientos cuyo plazo de estandarización o de verificación está por
 * cumplirse, para actuar antes de incumplirlo.
 */
class ProcedimientosPorVencerWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::user()?->can('ver procedimientos do') ?? false;
    }

    public function table(Table $table): Table
    {
        $limite = now()->addDays(30);

        return $table
            ->heading('Plazos por cumplirse (próximos 30 días)')
            ->description('Procedimientos cuyo plazo de estandarización o de verificación vence pronto.')
            ->query(
                ProcedimientoDoResource::getEloquentQuery()
                    ->with(['contrato:id,nombre', 'campo:id,nombre'])
                    ->where(function (Builder $consulta) use ($limite) {
                        $consulta
                            // Aún sin codificar y con la fecha límite encima.
                            ->where(fn (Builder $q) => $q
                                ->whereNull('codigo_asignado')
                                ->whereNotNull('fecha_limite_estandarizacion')
                                ->where('fecha_limite_estandarizacion', '<=', $limite))
                            // Ya estandarizado y con la verificación programada.
                            ->orWhere(fn (Builder $q) => $q
                                ->whereNotNull('codigo_asignado')
                                ->whereNotNull('fecha_programada_verificacion')
                                ->where('fecha_programada_verificacion', '<=', $limite));
                    })
                    ->orderByRaw('COALESCE(fecha_limite_estandarizacion, fecha_programada_verificacion)')
            )
            ->columns([
                TextColumn::make('nombre_actividad')
                    ->label('Procedimiento')
                    ->limit(45)
                    ->tooltip(fn (ProcedimientoDo $registro) => $registro->nombre_actividad)
                    ->searchable(),

                TextColumn::make('contrato.nombre')
                    ->label('Contrato')
                    ->toggleable(),

                TextColumn::make('prioridad')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn (PrioridadDo $state) => $state->label())
                    ->color(fn (PrioridadDo $state) => $state->color()),

                TextColumn::make('pendiente')
                    ->label('Pendiente')
                    ->state(fn (ProcedimientoDo $registro): string => $registro->estaEstandarizado()
                        ? 'Verificación en campo'
                        : 'Estandarización'),

                TextColumn::make('fecha')
                    ->label('Fecha límite')
                    ->state(fn (ProcedimientoDo $registro): ?Carbon => $registro->estaEstandarizado()
                        ? $registro->fecha_programada_verificacion
                        : $registro->fecha_limite_estandarizacion)
                    ->date('d/m/Y')
                    ->color(fn (ProcedimientoDo $registro): string => self::estaVencido($registro) ? 'danger' : 'warning')
                    ->description(fn (ProcedimientoDo $registro): string => self::estaVencido($registro) ? 'Vencido' : 'Por vencer'),
            ])
            ->recordActions([
                Action::make('abrir')
                    ->label('Abrir')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (ProcedimientoDo $registro): string => ProcedimientoDoResource::getUrl('edit', ['record' => $registro])),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Sin plazos próximos')
            ->emptyStateDescription('Ningún procedimiento vence en los siguientes 30 días.');
    }

    private static function estaVencido(ProcedimientoDo $registro): bool
    {
        $fecha = $registro->estaEstandarizado()
            ? $registro->fecha_programada_verificacion
            : $registro->fecha_limite_estandarizacion;

        return $fecha !== null && $fecha->isPast();
    }
}
