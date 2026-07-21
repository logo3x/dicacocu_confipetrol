<?php

namespace App\Observers;

use App\Models\InspeccionGerencialRegla;

class InspeccionGerencialReglaObserver
{
    public function creating(InspeccionGerencialRegla $regla): void
    {
        if (empty($regla->acompanamiento_verificacion_id) && $regla->inspeccionGerencial) {
            $regla->acompanamiento_verificacion_id = $regla->inspeccionGerencial->acompanamiento_verificacion_id;
        }
    }
}
