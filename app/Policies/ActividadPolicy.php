<?php

namespace App\Policies;

use App\Models\Actividad;
use App\Models\User;

class ActividadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function view(User $user, Actividad $actividad): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->can('crear actividades');
    }

    public function update(User $user, Actividad $actividad): bool
    {
        return $user->is_active && $user->can('editar actividades');
    }

    public function delete(User $user, Actividad $actividad): bool
    {
        return $user->is_active && $user->can('eliminar actividades');
    }

    public function restore(User $user, Actividad $actividad): bool
    {
        return $user->is_active && $user->can('eliminar actividades');
    }

    public function forceDelete(User $user, Actividad $actividad): bool
    {
        return $user->is_active && $user->hasRole('super_admin');
    }
}
