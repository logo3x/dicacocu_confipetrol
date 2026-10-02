<?php

namespace App\Services\Do;

use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Decide a quién se avisa de lo que ocurre con un procedimiento.
 *
 * El criterio es el mismo que rige el resto del módulo: cada quien ve lo de
 * su contrato, así que un aviso nunca cruza de un contrato a otro.
 */
class DestinatariosDo
{
    /** Roles que acompañan el ciclo y reciben los avisos de su contrato. */
    private const ROLES_DE_SEGUIMIENTO = ['responsable_hseq', 'calidad_corporativa'];

    /**
     * Personas interesadas en un procedimiento: las que aparecen en él más
     * quienes hacen seguimiento en ese contrato.
     *
     * @return Collection<int, User>
     */
    public static function para(ProcedimientoDo $procedimiento): Collection
    {
        $directos = array_filter([
            $procedimiento->responsable_area_id,
            $procedimiento->responsable_codificacion_id,
            $procedimiento->observador_hseq_id,
            $procedimiento->created_by,
        ]);

        return User::query()
            ->where('is_active', true)
            ->where(function ($consulta) use ($directos, $procedimiento) {
                $consulta
                    ->whereIn('id', $directos)
                    ->orWhere(fn ($seguimiento) => $seguimiento
                        ->where('contrato_id', $procedimiento->contrato_id)
                        ->whereHas('roles', fn ($rol) => $rol->whereIn('name', self::ROLES_DE_SEGUIMIENTO)));
            })
            ->get();
    }
}
