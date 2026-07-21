<?php

namespace App\Filament\Widgets;

use App\Enums\PrioridadAmenaza;
use App\Models\Actividad;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class ActividadesResumenWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected static ?int $sort = 3;

    protected function getHeading(): ?string
    {
        return 'Actividades — Disciplina Operativa';
    }

    protected function getStats(): array
    {
        $stats = Cache::remember('do_actividades_resumen', 60, function () {
            return [
                'total' => Actividad::count(),
                'alto' => Actividad::where('prioridad_amenaza', PrioridadAmenaza::Alto->value)->count(),
                'sin_estandarizar' => Actividad::whereNull('documento_id')->count(),
                'vencidas' => Actividad::whereNull('documento_id')
                    ->whereNotNull('fecha_limite_estandarizacion')
                    ->where('fecha_limite_estandarizacion', '<', now())
                    ->count(),
            ];
        });

        return [
            Stat::make('Actividades identificadas', $stats['total'])
                ->description('Etapa 1 — inventario')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Prioridad alta', $stats['alto'])
                ->description('Amenaza ≤ 59, requieren atención')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stats['alto'] > 0 ? 'danger' : 'success'),

            Stat::make('Sin estandarizar', $stats['sin_estandarizar'])
                ->description('Sin procedimiento codificado')
                ->descriptionIcon('heroicon-m-document')
                ->color($stats['sin_estandarizar'] > 0 ? 'warning' : 'success'),

            Stat::make('Plazo vencido', $stats['vencidas'])
                ->description('Estandarización fuera de plazo')
                ->descriptionIcon('heroicon-m-clock')
                ->color($stats['vencidas'] > 0 ? 'danger' : 'success'),
        ];
    }
}
