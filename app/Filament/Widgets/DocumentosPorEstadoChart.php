<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Widgets\ChartWidget;

class DocumentosPorEstadoChart extends ChartWidget
{
    protected ?string $heading = 'Distribución por Estado del Workflow';

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $estados = ['borrador', 'en_revision', 'aprobado', 'divulgado', 'verificado', 'rechazado'];
        $etiquetas = [
            'borrador' => 'Borrador',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobado',
            'divulgado' => 'Divulgado',
            'verificado' => 'Verificado',
            'rechazado' => 'Rechazado',
        ];

        $counts = Documento::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $data = array_map(fn ($e) => $counts->get($e, 0), $estados);

        return [
            'datasets' => [
                [
                    'label' => 'Documentos',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(148, 163, 184, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(0, 80, 160, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => array_values($etiquetas),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
