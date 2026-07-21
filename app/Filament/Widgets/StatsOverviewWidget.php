<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats = Cache::remember('sgd_dashboard_stats', 60, function () {
            return [
                'total' => Documento::count(),
                'vigentes' => Documento::whereIn('estado', ['aprobado', 'divulgado'])->count(),
                'en_revision' => Documento::where('estado', 'en_revision')->count(),
                'vencidos' => Documento::whereNotNull('fecha_vencimiento')
                    ->where('fecha_vencimiento', '<', now())
                    ->whereIn('estado', ['aprobado', 'divulgado'])
                    ->count(),
                'sparkline' => Documento::selectRaw('COUNT(*) as count')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->groupByRaw('DATE(created_at)')
                    ->orderByRaw('DATE(created_at)')
                    ->pluck('count')
                    ->toArray() ?: [0],
            ];
        });

        return [
            Stat::make('Total de documentos', $stats['total'])
                ->description('En el sistema')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart($stats['sparkline']),

            Stat::make('Documentos vigentes', $stats['vigentes'])
                ->description('Aprobados o divulgados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('En revisión', $stats['en_revision'])
                ->description('Pendientes de aprobación')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Documentos vencidos', $stats['vencidos'])
                ->description('Requieren actualización')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stats['vencidos'] > 0 ? 'danger' : 'success'),
        ];
    }
}
