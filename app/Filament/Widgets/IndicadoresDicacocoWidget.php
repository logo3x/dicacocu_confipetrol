<?php

namespace App\Filament\Widgets;

use App\Services\IndicadoresDicacocoService;
use App\Support\IndicadoresDicacoco;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class IndicadoresDicacocoWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected static ?int $sort = 1;

    protected function getHeading(): ?string
    {
        return 'Indicadores DICACOCO — Disciplina Operativa';
    }

    protected function getStats(): array
    {
        /** @var IndicadoresDicacoco $indicadores */
        $indicadores = Cache::remember('do_indicadores_dicacoco', 60, fn () => IndicadoresDicacocoService::calcular());

        return [
            Stat::make('DI — Disponibilidad', "{$indicadores->disponibilidad}%")
                ->description('Meta: '.IndicadoresDicacoco::META_DISPONIBILIDAD.'% · Procedimientos estandarizados')
                ->descriptionIcon($indicadores->cumpleDisponibilidad() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleDisponibilidad() ? 'success' : 'danger'),

            Stat::make('CA — Calidad', "{$indicadores->calidad}%")
                ->description('Meta: '.IndicadoresDicacoco::META_CALIDAD.'% · Cobertura de verificación')
                ->descriptionIcon($indicadores->cumpleCalidad() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleCalidad() ? 'success' : 'danger'),

            Stat::make('CO — Comunicación', "{$indicadores->comunicacion}%")
                ->description('Meta: '.IndicadoresDicacoco::META_COMUNICACION.'% · Cobertura de socialización')
                ->descriptionIcon($indicadores->cumpleComunicacion() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleComunicacion() ? 'success' : 'danger'),

            Stat::make('CU — Cumplimiento', "{$indicadores->cumplimiento}%")
                ->description('Meta: '.IndicadoresDicacoco::META_CUMPLIMIENTO.'% · Desempeño promedio OPT')
                ->descriptionIcon($indicadores->cumpleCumplimiento() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleCumplimiento() ? 'success' : 'danger'),
        ];
    }
}
