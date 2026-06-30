<?php

namespace App\Observers;

use App\Models\Documento;

class DocumentoObserver
{
    public function creating(Documento $documento): void
    {
        if (empty($documento->created_by)) {
            $documento->created_by = auth()->id();
        }

        if (empty($documento->version_actual)) {
            $documento->version_actual = 1;
        }
    }

    public function created(Documento $documento): void
    {
        $documento->versiones()->create([
            'version' => 1,
            'estado' => $documento->estado,
            'cambios' => 'Versión inicial del documento.',
            'created_by' => $documento->created_by,
        ]);
    }

    public function updated(Documento $documento): void
    {
        if ($documento->wasChanged('estado')) {
            activity()
                ->performedOn($documento)
                ->causedBy(auth()->user())
                ->withProperties([
                    'estado_anterior' => $documento->getOriginal('estado'),
                    'estado_nuevo' => $documento->estado,
                ])
                ->log("Estado cambiado de [{$documento->getOriginal('estado')}] a [{$documento->estado}]");
        }
    }

    public function deleted(Documento $documento): void {}

    public function restored(Documento $documento): void {}

    public function forceDeleted(Documento $documento): void {}
}
