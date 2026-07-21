<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActividadesResumenWidget;
use App\Filament\Widgets\CompromisosProximosWidget;
use App\Filament\Widgets\DocumentosPorEstadoChart;
use App\Filament\Widgets\DocumentosRecientesWidget;
use App\Filament\Widgets\DocumentosVencidosWidget;
use App\Filament\Widgets\IndicadoresDicacocoWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;

class DashboardDicacocu extends Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'SGD DICACOCU — Panel de Control';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            IndicadoresDicacocoWidget::class,
            StatsOverviewWidget::class,
            ActividadesResumenWidget::class,
            DocumentosPorEstadoChart::class,
            DocumentosRecientesWidget::class,
            DocumentosVencidosWidget::class,
            CompromisosProximosWidget::class,
        ];
    }
}
