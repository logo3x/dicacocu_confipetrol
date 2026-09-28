<?php

namespace App\Observers\Do;

use App\Enums\Do\CriterioAmenaza;
use App\Models\Do\ProcedimientoDo;
use App\Services\Do\CalculadoraDo;

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

        if ($procedimiento->personas_socializadas === null) {
            $procedimiento->personas_socializadas = $procedimiento->personas_involucradas;
        }

        $procedimiento->cobertura_socializacion = CalculadoraDo::coberturaSocializacion(
            $procedimiento->personas_socializadas,
            $procedimiento->personas_involucradas,
        );
    }
}
