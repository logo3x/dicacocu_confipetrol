<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DocumentosRecientesWidget;
use App\Filament\Widgets\FasesDicacoCuWidget;
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
            StatsOverviewWidget::class,
            FasesDicacoCuWidget::class,
            DocumentosRecientesWidget::class,
        ];
    }
}
