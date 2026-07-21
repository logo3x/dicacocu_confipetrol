<?php

namespace App\Observers;

use App\Enums\ClasificacionOpt;
use App\Models\AcompanamientoVerificacion;
use Illuminate\Validation\ValidationException;

class AcompanamientoVerificacionObserver
{
    public function saving(AcompanamientoVerificacion $acompanamiento): void
    {
        if (empty($acompanamiento->created_by)) {
            $acompanamiento->created_by = auth()->id();
        }

        if (empty($acompanamiento->observador_id)) {
            throw ValidationException::withMessages([
                'observador_id' => 'El acompañamiento requiere el nombre de quien observa/visita.',
            ]);
        }

        if ($acompanamiento->acompanante_id !== null && $acompanamiento->acompanante_id === $acompanamiento->observador_id) {
            throw ValidationException::withMessages([
                'acompanante_id' => 'El observador y el acompañante deben ser personas distintas.',
            ]);
        }

        $puntaje = $acompanamiento->calcularPuntajeOpt();
        $acompanamiento->puntaje_opt_calculado = $puntaje;
        $acompanamiento->clasificacion_opt = $puntaje !== null
            ? ClasificacionOpt::desdePuntaje($puntaje)->value
            : null;
    }

    public function saved(AcompanamientoVerificacion $acompanamiento): void
    {
        if ($acompanamiento->esInspeccionGerencial() && $acompanamiento->inspeccionGerencial()->doesntExist()) {
            $inspeccion = $acompanamiento->inspeccionGerencial()->create();
            $inspeccion->inicializarReglas();
        }
    }
}
