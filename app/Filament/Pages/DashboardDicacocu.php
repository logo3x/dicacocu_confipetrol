<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IndicadoresDoWidget;
use App\Filament\Widgets\ProcedimientosPorVencerWidget;
use App\Filament\Widgets\ResumenProcedimientosWidget;
use App\Filament\Widgets\VerificacionesRecientesWidget;
use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;

class DashboardDicacocu extends Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Panel de control';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            IndicadoresDoWidget::class,
            ResumenProcedimientosWidget::class,
            ProcedimientosPorVencerWidget::class,
            VerificacionesRecientesWidget::class,
        ];
    }
}
