<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FasesDicacoCuWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getHeading(): ?string
    {
        return 'Distribución por Fase DICACOCU';
    }

    protected function getStats(): array
    {
        $fases = [
            'D' => 'Disponibilidad',
            'I' => 'Integridad',
            'C' => 'Calidad',
            'A' => 'Acceso',
            'O' => 'Operación',
            'U' => 'Uso',
        ];

        $counts = Documento::query()
            ->selectRaw('fase_dicacocu, COUNT(*) as total')
            ->whereNotNull('fase_dicacocu')
            ->groupBy('fase_dicacocu')
            ->pluck('total', 'fase_dicacocu');

        $stats = [];

        foreach ($fases as $codigo => $nombre) {
            $count = $counts->get($codigo, 0);
            $stats[] = Stat::make("{$codigo} — {$nombre}", $count)
                ->description('documentos')
                ->color('primary');
        }

        return $stats;
    }
}
