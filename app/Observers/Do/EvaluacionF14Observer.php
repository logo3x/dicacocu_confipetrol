<?php

namespace App\Observers\Do;

use App\Models\Do\EvaluacionF14;
use App\Services\Do\CalculadoraDo;
use Illuminate\Support\Facades\Cache;

class EvaluacionF14Observer
{
    public function saving(EvaluacionF14 $evaluacion): void
    {
        $respuestas = $evaluacion->respuestasChecklist();

        $evaluacion->subtotal = CalculadoraDo::subtotalChecklist($respuestas);
        $evaluacion->puntaje_opt = CalculadoraDo::puntajeOpt(
            $respuestas,
            $evaluacion->pasos_segun_procedimiento,
            $evaluacion->pasos_en_observacion,
        );
        $evaluacion->criterio_opt = CalculadoraDo::criterioOpt($evaluacion->puntaje_opt);
    }

    public function saved(EvaluacionF14 $evaluacion): void
    {
        $this->actualizarProcedimiento($evaluacion);
    }

    public function deleted(EvaluacionF14 $evaluacion): void
    {
        $this->actualizarProcedimiento($evaluacion);
    }

    /**
     * El puntaje OPT del procedimiento es el promedio de sus evaluaciones,
     * equivalente a AVERAGE(DO!AG:AG) de la hoja de indicadores.
     */
    private function actualizarProcedimiento(EvaluacionF14 $evaluacion): void
    {
        $procedimiento = $evaluacion->procedimiento;

        if (! $procedimiento) {
            return;
        }

        $agregado = $procedimiento->evaluaciones()
            ->toBase()
            ->selectRaw('avg(puntaje_opt) as promedio, max(fecha_ejecucion) as ultima_ejecucion')
            ->first();

        $promedio = $agregado?->promedio;

        $procedimiento->forceFill([
            'puntaje_opt' => $promedio !== null ? round((float) $promedio, 2) : null,
            'criterio_opt' => CalculadoraDo::criterioOpt($promedio !== null ? (float) $promedio : null),
            'fecha_ejecutada_verificacion' => $agregado?->ultima_ejecucion,
        ])->saveQuietly();

        Cache::forget('do_indicadores_procedimientos');
    }
}
