<?php

namespace App\Observers\Do;

use App\Enums\Do\CriterioAmenaza;
use App\Models\Do\ProcedimientoDo;
use App\Services\Do\CalculadoraDo;
use Illuminate\Support\Facades\Cache;

class ProcedimientoDoObserver
{
    public function saving(ProcedimientoDo $procedimiento): void
    {
        $criterios = [];

        foreach (CriterioAmenaza::cases() as $criterio) {
            $criterios[$criterio->value] = (bool) $procedimiento->{$criterio->value};
        }

        $puntaje = CalculadoraDo::puntajePrioridad($criterios);
        $prioridad = CalculadoraDo::prioridad($puntaje);

        $procedimiento->puntaje_prioridad = $puntaje;
        $procedimiento->prioridad = $prioridad;
        $procedimiento->plazo_estandarizacion_meses = $prioridad->plazoEstandarizacionMeses();
        $procedimiento->frecuencia_verificacion_meses = $prioridad->frecuenciaVerificacionMeses();

        if ($procedimiento->fecha_identificacion) {
            $procedimiento->fecha_limite_estandarizacion = $procedimiento->fecha_identificacion
                ->copy()
                ->addMonths($prioridad->plazoEstandarizacionMeses());
        }

        // Solo al crear: en un update, un valor vacío significa que aún no se socializó a nadie.
        if (! $procedimiento->exists && $procedimiento->personas_socializadas === null) {
            $procedimiento->personas_socializadas = $procedimiento->personas_involucradas;
        }

        $procedimiento->cobertura_socializacion = CalculadoraDo::coberturaSocializacion(
            $procedimiento->personas_socializadas,
            $procedimiento->personas_involucradas,
        );
    }

    public function saved(ProcedimientoDo $procedimiento): void
    {
        Cache::forget('do_indicadores_procedimientos');
    }

    public function deleted(ProcedimientoDo $procedimiento): void
    {
        Cache::forget('do_indicadores_procedimientos');
    }
}
