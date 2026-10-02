<?php

namespace App\Observers\Do;

use App\Enums\Do\CriterioAmenaza;
use App\Filament\Widgets\IndicadoresDoWidget;
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

        // Solo al crear: en un update, un valor vacío significa que aún no se socializó a nadie.
        if (! $procedimiento->exists && $procedimiento->personas_socializadas === null) {
            $procedimiento->personas_socializadas = $procedimiento->personas_involucradas;
        }

        // La cobertura solo cuenta desde que hubo divulgación. Sin esta
        // condición, un procedimiento recién inventariado nacía al 100 % e
        // inflaba el indicador CO sin que nadie hubiera socializado nada.
        $procedimiento->cobertura_socializacion = $procedimiento->fecha_ultima_divulgacion
            ? CalculadoraDo::coberturaSocializacion(
                $procedimiento->personas_socializadas,
                $procedimiento->personas_involucradas,
            )
            : 0.0;
    }

    public function created(ProcedimientoDo $procedimiento): void
    {
        $this->registrar($procedimiento, 'created', 'Creó el procedimiento', [
            'attributes' => array_intersect_key($procedimiento->getAttributes(), array_flip(ProcedimientoDo::CAMPOS_AUDITADOS)),
        ]);
    }

    public function updated(ProcedimientoDo $procedimiento): void
    {
        $nuevos = array_intersect_key($procedimiento->getChanges(), array_flip(ProcedimientoDo::CAMPOS_AUDITADOS));

        if (blank($nuevos)) {
            return;
        }

        $this->registrar($procedimiento, 'updated', 'Editó el procedimiento', [
            'attributes' => $nuevos,
            'old' => array_intersect_key($procedimiento->getOriginal(), $nuevos),
        ]);
    }

    public function deleted(ProcedimientoDo $procedimiento): void
    {
        $this->registrar($procedimiento, 'deleted', 'Eliminó el procedimiento', [
            'old' => array_intersect_key($procedimiento->getOriginal(), array_flip(ProcedimientoDo::CAMPOS_AUDITADOS)),
        ]);

        IndicadoresDoWidget::olvidarCache($procedimiento->contrato_id);
    }

    public function restored(ProcedimientoDo $procedimiento): void
    {
        $this->registrar($procedimiento, 'restored', 'Restauró el procedimiento', []);
    }

    public function saved(ProcedimientoDo $procedimiento): void
    {
        IndicadoresDoWidget::olvidarCache($procedimiento->contrato_id);
    }

    /**
     * Deja constancia de quién hizo el cambio y qué valores tomó cada campo,
     * para que el historial sirva como evidencia de auditoría.
     *
     * @param  array<string, mixed>  $propiedades
     */
    private function registrar(ProcedimientoDo $procedimiento, string $evento, string $descripcion, array $propiedades): void
    {
        activity()
            ->performedOn($procedimiento)
            ->causedBy(auth()->user())
            ->withProperties($propiedades)
            ->event($evento)
            ->log($descripcion);
    }
}
