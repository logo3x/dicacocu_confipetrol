<?php

namespace App\Policies;

use App\Models\InspeccionGerencialRegla;
use App\Models\User;

class InspeccionGerencialReglaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function view(User $user, InspeccionGerencialRegla $regla): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function create(User $user): bool
    {
        return $user->is_active
            && ($user->can('evaluar actividad operativo') || $user->can('evaluar actividad hseq'));
    }

    public function update(User $user, InspeccionGerencialRegla $regla): bool
    {
        return $user->is_active
            && ($user->can('evaluar actividad operativo') || $user->can('evaluar actividad hseq'));
    }

    public function delete(User $user, InspeccionGerencialRegla $regla): bool
    {
        return $user->is_active && $user->can('consolidar indicadores do');
    }

    public function restore(User $user, InspeccionGerencialRegla $regla): bool
    {
        return $user->is_active && $user->can('consolidar indicadores do');
    }

    public function forceDelete(User $user, InspeccionGerencialRegla $regla): bool
    {
        return $user->is_active && $user->hasRole('super_admin');
    }
}
