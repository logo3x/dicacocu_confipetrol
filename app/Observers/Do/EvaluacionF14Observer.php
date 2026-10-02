<?php

namespace App\Observers\Do;

use App\Enums\Do\CriterioOpt;
use App\Filament\Widgets\IndicadoresDoWidget;
use App\Models\Do\EvaluacionF14;
use App\Notifications\Do\VerificacionDeficiente;
use App\Services\Do\CalculadoraDo;
use App\Services\Do\DestinatariosDo;
use Illuminate\Support\Facades\Notification;

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

    public function created(EvaluacionF14 $evaluacion): void
    {
        $this->avisarSiElResultadoEsBajo($evaluacion);
    }

    public function deleted(EvaluacionF14 $evaluacion): void
    {
        $this->actualizarProcedimiento($evaluacion);
    }

    /** Al recuperar una evaluación vuelve a contar para el promedio OPT. */
    public function restored(EvaluacionF14 $evaluacion): void
    {
        $this->actualizarProcedimiento($evaluacion);
    }

    /**
     * Un resultado bajo exige levantar un plan de acción, así que se avisa a
     * quienes acompañan el procedimiento.
     *
     * Se notifica solo en Deficiente y Regular: incluir "Bueno" llenaría la
     * campana de avisos que nadie atiende.
     */
    private function avisarSiElResultadoEsBajo(EvaluacionF14 $evaluacion): void
    {
        $criterio = $evaluacion->criterio_opt;

        if (! in_array($criterio, [CriterioOpt::Deficiente, CriterioOpt::Regular], true)) {
            return;
        }

        $procedimiento = $evaluacion->procedimiento;

        if (! $procedimiento) {
            return;
        }

        Notification::send(
            DestinatariosDo::para($procedimiento),
            new VerificacionDeficiente($evaluacion),
        );
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

        IndicadoresDoWidget::olvidarCache($procedimiento->contrato_id);
    }
}
