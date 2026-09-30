<?php

namespace App\Filament\Widgets;

use App\Enums\Do\PrioridadDo;
use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

/**
 * Reparto de los procedimientos por prioridad y avance de la estandarización.
 */
class ResumenProcedimientosWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        return Auth::user()?->can('ver procedimientos do') ?? false;
    }

    protected function getHeading(): ?string
    {
        return 'Procedimientos registrados';
    }

    protected function getStats(): array
    {
        $base = fn () => ProcedimientoDoResource::getEloquentQuery();

        $total = $base()->count();
        $sinEstandarizar = $base()->whereNull('codigo_asignado')->count();

        $vencidos = $base()
            ->whereNull('codigo_asignado')
            ->whereNotNull('fecha_limite_estandarizacion')
            ->where('fecha_limite_estandarizacion', '<', now())
            ->count();

        $porPrioridad = fn (PrioridadDo $prioridad): int => $base()
            ->where('prioridad', $prioridad->value)
            ->count();

        $alta = $porPrioridad(PrioridadDo::Alto);

        return [
            Stat::make('Total', $total)
                ->description('Procedimientos de su alcance')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Prioridad alta', $alta)
                ->description($this->proporcion($alta, $total).' del total')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($alta > 0 ? 'danger' : 'gray'),

            Stat::make('Prioridad media', $porPrioridad(PrioridadDo::Medio))
                ->description('Plazo de estandarización: 2 meses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sin estandarizar', $sinEstandarizar)
                ->description($vencidos > 0
                    ? $vencidos.' con el plazo vencido'
                    : 'Ninguno con el plazo vencido')
                ->descriptionIcon($vencidos > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($vencidos > 0 ? 'danger' : 'success'),
        ];
    }

    private function proporcion(int $parte, int $total): string
    {
        return $total > 0
            ? round($parte / $total * 100).'%'
            : '0%';
    }
}
