<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ComplianceFasesWidget;
use App\Filament\Widgets\DocumentosPorEstadoChart;
use App\Filament\Widgets\DocumentosPorFaseChart;
use App\Filament\Widgets\DocumentosVencidosWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class ReportesPage extends Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Reportes';

    protected static string|\UnitEnum|null $navigationGroup = 'Ciclo DICACOCU';

    protected static ?string $title = 'Reportes de Gestión Documental';

    protected static string $routePath = 'reportes';

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            DocumentosPorFaseChart::class,
            DocumentosPorEstadoChart::class,
            ComplianceFasesWidget::class,
            DocumentosVencidosWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
