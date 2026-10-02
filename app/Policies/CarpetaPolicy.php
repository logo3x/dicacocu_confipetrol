<?php

namespace App\Policies;

use App\Models\Carpeta;
use App\Models\User;

/** Quién puede organizar el árbol de carpetas del repositorio. */
class CarpetaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver carpetas');
    }

    public function view(User $user, Carpeta $carpeta): bool
    {
        return $user->can('ver carpetas');
    }

    public function create(User $user): bool
    {
        return $user->can('crear carpetas');
    }

    public function update(User $user, Carpeta $carpeta): bool
    {
        return $user->can('editar carpetas');
    }

    public function delete(User $user, Carpeta $carpeta): bool
    {
        return $user->can('eliminar carpetas');
    }

    public function restore(User $user, Carpeta $carpeta): bool
    {
        return $user->can('editar carpetas');
    }

    public function forceDelete(User $user, Carpeta $carpeta): bool
    {
        return $user->can('eliminar carpetas');
    }
}
