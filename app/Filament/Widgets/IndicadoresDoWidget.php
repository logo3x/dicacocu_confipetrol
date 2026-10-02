<?php

namespace App\Filament\Widgets;

use App\Services\Do\IndicadoresDoService;
use App\Support\Do\IndicadoresDo;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Indicadores del módulo de Procedimientos DO (Matriz Integral HSEQ-GCA1-F-17).
 */
class IndicadoresDoWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected static ?int $sort = 0;

    public static function canView(): bool
    {
        return auth()->user()?->can('ver procedimientos do') ?? false;
    }

    protected function getHeading(): ?string
    {
        return 'Procedimientos DICACOCU — Indicadores DI / CA / CO / CU';
    }

    protected function getDescription(): ?string
    {
        $indicadores = $this->indicadores();

        return "Cumplimiento global: {$indicadores->global()}% · {$indicadores->estandarizados} de {$indicadores->totalProcedimientos} procedimientos estandarizados";
    }

    protected function getStats(): array
    {
        $indicadores = $this->indicadores();

        return [
            Stat::make('DI — Disponibilidad', "{$indicadores->disponibilidad}%")
                ->description('Meta: '.IndicadoresDo::META_DISPONIBILIDAD.'% · Procedimientos estandarizados')
                ->descriptionIcon($indicadores->cumpleDisponibilidad() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleDisponibilidad() ? 'success' : 'danger'),

            Stat::make('CA — Calidad', "{$indicadores->calidad}%")
                ->description('Meta: '.IndicadoresDo::META_CALIDAD.'% · Cobertura de verificación')
                ->descriptionIcon($indicadores->cumpleCalidad() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleCalidad() ? 'success' : 'danger'),

            Stat::make('CO — Comunicación', "{$indicadores->comunicacion}%")
                ->description('Meta: '.IndicadoresDo::META_COMUNICACION.'% · Cobertura de socialización')
                ->descriptionIcon($indicadores->cumpleComunicacion() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleComunicacion() ? 'success' : 'danger'),

            Stat::make('CU — Cumplimiento', "{$indicadores->cumplimiento}%")
                ->description('Meta: '.IndicadoresDo::META_CUMPLIMIENTO.'% · Promedio puntaje OPT (F-14)')
                ->descriptionIcon($indicadores->cumpleCumplimiento() ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($indicadores->cumpleCumplimiento() ? 'success' : 'danger'),
        ];
    }

    private function indicadores(): IndicadoresDo
    {
        $usuario = Auth::user();

        // Cada quien ve los indicadores de su contrato, igual que el resto del
        // módulo. La clave de caché lleva el contrato: una clave común haría
        // que un usuario viera las cifras de otro.
        $contratoId = $usuario?->veTodosLosContratos() ? null : $usuario?->contrato_id;

        // Se cachean valores escalares: un objeto serializado se recupera como
        // __PHP_Incomplete_Class si la clase cambia entre despliegues.
        $datos = Cache::remember(
            self::claveCache($contratoId),
            60,
            fn () => (array) IndicadoresDoService::calcular(contratoId: $contratoId),
        );

        return new IndicadoresDo(...$datos);
    }

    /** Clave de caché por contrato, para no mezclar cifras entre ellos. */
    public static function claveCache(?int $contratoId): string
    {
        return 'do_indicadores_procedimientos:'.($contratoId ?? 'global');
    }

    /**
     * Invalida las cifras afectadas por un cambio: las del contrato y las
     * globales que ven los administradores.
     */
    public static function olvidarCache(?int $contratoId = null): void
    {
        Cache::forget(self::claveCache(null));

        if ($contratoId !== null) {
            Cache::forget(self::claveCache($contratoId));
        }
    }
}
