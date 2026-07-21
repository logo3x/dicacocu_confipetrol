<?php

namespace App\Observers;

use App\Models\InspeccionGerencialAccion;

class InspeccionGerencialAccionObserver
{
    public function creating(InspeccionGerencialAccion $accion): void
    {
        if (empty($accion->acompanamiento_verificacion_id) && $accion->inspeccionGerencial) {
            $accion->acompanamiento_verificacion_id = $accion->inspeccionGerencial->acompanamiento_verificacion_id;
        }
    }
}
