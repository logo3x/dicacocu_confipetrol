<?php

namespace App\Policies;

use App\Models\AcompanamientoVerificacion;
use App\Models\User;

class AcompanamientoVerificacionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function view(User $user, AcompanamientoVerificacion $acompanamiento): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function create(User $user): bool
    {
        return $user->is_active
            && ($user->can('evaluar actividad operativo') || $user->can('evaluar actividad hseq'));
    }

    public function update(User $user, AcompanamientoVerificacion $acompanamiento): bool
    {
        if (! $user->is_active || $acompanamiento->estaCerrado()) {
            return false;
        }

        return $user->id === $acompanamiento->created_by
            || $user->can('consolidar indicadores do');
    }

    public function delete(User $user, AcompanamientoVerificacion $acompanamiento): bool
    {
        return $user->is_active && $user->can('consolidar indicadores do');
    }

    public function restore(User $user, AcompanamientoVerificacion $acompanamiento): bool
    {
        return $user->is_active && $user->can('consolidar indicadores do');
    }

    public function forceDelete(User $user, AcompanamientoVerificacion $acompanamiento): bool
    {
        return $user->is_active && $user->hasRole('super_admin');
    }
}
