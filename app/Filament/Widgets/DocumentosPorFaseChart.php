<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Widgets\ChartWidget;

class DocumentosPorFaseChart extends ChartWidget
{
    protected ?string $heading = 'Documentos por Fase DICACOCU';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $fases = ['D', 'I', 'C', 'A', 'O', 'U'];
        $labels = [
            'D' => 'Disponibilidad',
            'I' => 'Integridad',
            'C' => 'Calidad',
            'A' => 'Acceso',
            'O' => 'Operación',
            'U' => 'Uso',
        ];

        $counts = Documento::selectRaw('fase_dicacocu, COUNT(*) as total')
            ->whereIn('fase_dicacocu', $fases)
            ->groupBy('fase_dicacocu')
            ->pluck('total', 'fase_dicacocu');

        $data = array_map(fn ($fase) => $counts->get($fase, 0), $fases);
        $labelNames = array_map(fn ($fase) => $labels[$fase], $fases);

        return [
            'datasets' => [
                [
                    'label' => 'Documentos',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(0, 80, 160, 0.8)',
                        'rgba(0, 80, 160, 0.65)',
                        'rgba(0, 80, 160, 0.5)',
                        'rgba(245, 138, 31, 0.8)',
                        'rgba(245, 138, 31, 0.65)',
                        'rgba(245, 138, 31, 0.5)',
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => $labelNames,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
