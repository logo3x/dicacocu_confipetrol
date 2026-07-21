<?php

namespace App\Services;

use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Support\IndicadoresDicacoco;
use Illuminate\Support\Carbon;

class IndicadoresDicacocoService
{
    /**
     * Calcula los 4 indicadores DICACOCO en tiempo real.
     *
     * @param  string|null  $contrato  Filtra por contrato; null = consolidado corporativo.
     * @param  Carbon|null  $desde  Inicio del periodo para CA/CO/CU; null = sin límite inferior.
     * @param  Carbon|null  $hasta  Fin del periodo para CA/CO/CU; por defecto ahora.
     */
    public static function calcular(?string $contrato = null, ?Carbon $desde = null, ?Carbon $hasta = null): IndicadoresDicacoco
    {
        $hasta ??= now();

        $actividades = Actividad::query()
            ->when($contrato, fn ($q) => $q->where('contrato', $contrato));

        $totalActividades = (clone $actividades)->count();
        $estandarizadas = (clone $actividades)->whereNotNull('documento_id');
        $totalEstandarizadas = $estandarizadas->count();

        return new IndicadoresDicacoco(
            disponibilidad: self::porcentaje($totalEstandarizadas, $totalActividades),
            calidad: self::calcularCalidad($contrato, $desde, $hasta, $totalEstandarizadas),
            comunicacion: self::calcularComunicacion($contrato, $desde, $hasta, $totalEstandarizadas),
            cumplimiento: self::calcularCumplimiento($contrato, $desde, $hasta),
        );
    }

    private static function calcularCalidad(?string $contrato, ?Carbon $desde, ?Carbon $hasta, int $totalEstandarizadas): float
    {
        if ($totalEstandarizadas === 0) {
            return 0.0;
        }

        $verificadas = Actividad::query()
            ->when($contrato, fn ($q) => $q->where('contrato', $contrato))
            ->whereNotNull('documento_id')
            ->whereHas('acompanamientos', function ($q) use ($desde, $hasta) {
                $q->when($desde, fn ($q) => $q->where('fecha_ejecucion', '>=', $desde))
                    ->where('fecha_ejecucion', '<=', $hasta);
            })
            ->count();

        return self::porcentaje($verificadas, $totalEstandarizadas);
    }

    private static function calcularComunicacion(?string $contrato, ?Carbon $desde, ?Carbon $hasta, int $totalEstandarizadas): float
    {
        if ($totalEstandarizadas === 0) {
            return 0.0;
        }

        $socializadas = Actividad::query()
            ->when($contrato, fn ($q) => $q->where('contrato', $contrato))
            ->whereNotNull('documento_id')
            ->whereHas('personalExpuestoNominal', function ($q) use ($desde, $hasta) {
                $q->whereNotNull('fecha_socializacion')
                    ->when($desde, fn ($q) => $q->where('fecha_socializacion', '>=', $desde))
                    ->where('fecha_socializacion', '<=', $hasta);
            })
            ->count();

        return self::porcentaje($socializadas, $totalEstandarizadas);
    }

    private static function calcularCumplimiento(?string $contrato, ?Carbon $desde, ?Carbon $hasta): float
    {
        $evaluaciones = AcompanamientoVerificacion::query()
            ->whereNotNull('puntaje_opt_calculado')
            ->when($contrato, fn ($q) => $q->whereHas('actividad', fn ($q) => $q->where('contrato', $contrato)))
            ->when($desde, fn ($q) => $q->where('fecha_ejecucion', '>=', $desde))
            ->where('fecha_ejecucion', '<=', $hasta);

        $promedio = $evaluaciones->avg('puntaje_opt_calculado');

        return $promedio !== null ? round((float) $promedio, 2) : 0.0;
    }

    private static function porcentaje(int $parte, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($parte / $total) * 100, 1);
    }
}
