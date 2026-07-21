<?php

namespace App\Policies;

use App\Models\Compromiso;
use App\Models\User;

class CompromisoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function view(User $user, Compromiso $compromiso): bool
    {
        return $user->is_active && $user->can('ver actividades');
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->can('gestionar compromisos do');
    }

    public function update(User $user, Compromiso $compromiso): bool
    {
        return $user->is_active
            && ($user->can('gestionar compromisos do') || $user->id === $compromiso->responsable_id);
    }

    public function delete(User $user, Compromiso $compromiso): bool
    {
        return $user->is_active && $user->can('gestionar compromisos do');
    }

    public function restore(User $user, Compromiso $compromiso): bool
    {
        return $user->is_active && $user->can('gestionar compromisos do');
    }

    public function forceDelete(User $user, Compromiso $compromiso): bool
    {
        return $user->is_active && $user->hasRole('super_admin');
    }
}
