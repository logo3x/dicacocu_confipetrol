<?php

namespace App\Observers;

use App\Enums\PrioridadAmenaza;
use App\Models\Actividad;

class ActividadObserver
{
    public function creating(Actividad $actividad): void
    {
        if (empty($actividad->created_by)) {
            $actividad->created_by = auth()->id();
        }

        if (empty($actividad->fecha_identificacion)) {
            $actividad->fecha_identificacion = now()->toDateString();
        }

        $this->recalcularPrioridadYPlazos($actividad);
    }

    public function updating(Actividad $actividad): void
    {
        if ($actividad->isDirty('valoracion_amenaza')) {
            $this->recalcularPrioridadYPlazos($actividad);
        }
    }

    private function recalcularPrioridadYPlazos(Actividad $actividad): void
    {
        if ($actividad->valoracion_amenaza === null) {
            $actividad->prioridad_amenaza = null;
            $actividad->fecha_limite_estandarizacion = null;
            $actividad->fecha_limite_verificacion = null;

            return;
        }

        $prioridad = PrioridadAmenaza::desdeValoracion($actividad->valoracion_amenaza);
        $actividad->prioridad_amenaza = $prioridad->value;

        $fechaBase = $actividad->fecha_identificacion ?? now();

        $actividad->fecha_limite_estandarizacion = $fechaBase
            ->copy()
            ->addMonths($prioridad->plazoEstandarizacionMeses());

        $actividad->fecha_limite_verificacion = $fechaBase
            ->copy()
            ->addMonths($prioridad->frecuenciaVerificacionMeses());
    }
}
