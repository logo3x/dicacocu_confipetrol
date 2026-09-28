<?php

namespace App\Services\Do;

use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Support\Do\IndicadoresDo;

/**
 * Calcula los 4 indicadores de Disciplina Operativa replicando la hoja
 * INDICADORES del formato HSEQ-GCA1-F-17.
 */
class IndicadoresDoService
{
    public static function calcular(?int $anioCiclo = null, ?string $contrato = null): IndicadoresDo
    {
        $procedimientos = ProcedimientoDo::query()
            ->when($anioCiclo, fn ($q) => $q->where('anio_ciclo', $anioCiclo))
            ->when($contrato, fn ($q) => $q->where('contrato', $contrato));

        $total = (clone $procedimientos)->count();
        $estandarizados = (clone $procedimientos)->whereNotNull('codigo_asignado')->count();

        // DI — Disponibilidad: procedimientos estandarizados / total de actividades
        $disponibilidad = $total > 0 ? round($estandarizados / $total * 100, 2) : 0.0;

        // CA — Calidad: procedimientos verificados en el periodo / estandarizados
        $verificados = (clone $procedimientos)
            ->whereNotNull('codigo_asignado')
            ->whereNotNull('fecha_ejecutada_verificacion')
            ->count();
        $calidad = $estandarizados > 0 ? round($verificados / $estandarizados * 100, 2) : 0.0;

        // CO — Comunicación: promedio de cobertura de socialización
        $comunicacion = round((float) (clone $procedimientos)->avg('cobertura_socializacion'), 2);

        // CU — Cumplimiento: promedio de puntajes OPT de las evaluaciones F-14
        $cumplimiento = round((float) EvaluacionF14::query()
            ->when(
                $anioCiclo || $contrato,
                fn ($q) => $q->whereHas(
                    'procedimiento',
                    fn ($p) => $p
                        ->when($anioCiclo, fn ($x) => $x->where('anio_ciclo', $anioCiclo))
                        ->when($contrato, fn ($x) => $x->where('contrato', $contrato)),
                ),
            )
            ->avg('puntaje_opt'), 2);

        return new IndicadoresDo(
            disponibilidad: $disponibilidad,
            calidad: $calidad,
            comunicacion: $comunicacion,
            cumplimiento: $cumplimiento,
            totalProcedimientos: $total,
            estandarizados: $estandarizados,
        );
    }
}
