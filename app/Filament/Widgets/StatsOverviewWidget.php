<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $total = Documento::count();
        $vigentes = Documento::whereIn('estado', ['aprobado', 'divulgado'])->count();
        $enRevision = Documento::where('estado', 'en_revision')->count();
        $vencidos = Documento::whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', now())
            ->whereIn('estado', ['aprobado', 'divulgado'])
            ->count();

        return [
            Stat::make('Total de documentos', $total)
                ->description('En el sistema')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart(
                    Documento::selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->pluck('count')
                        ->toArray() ?: [0]
                ),

            Stat::make('Documentos vigentes', $vigentes)
                ->description('Aprobados o divulgados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('En revisión', $enRevision)
                ->description('Pendientes de aprobación')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Documentos vencidos', $vencidos)
                ->description('Requieren actualización')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($vencidos > 0 ? 'danger' : 'success'),
        ];
    }
}
