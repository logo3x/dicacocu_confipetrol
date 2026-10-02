<?php

namespace App\Policies;

use App\Models\User;

/**
 * Quién puede administrar las cuentas del sistema.
 *
 * Sin esta política cualquiera con acceso al panel podía abrir Usuarios,
 * editarse a sí mismo y asignarse un rol superior.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver usuarios');
    }

    public function view(User $user, User $modelo): bool
    {
        // Cada quien puede consultar su propia ficha.
        return $user->is($modelo) || $user->can('ver usuarios');
    }

    public function create(User $user): bool
    {
        return $user->can('crear usuarios');
    }

    public function update(User $user, User $modelo): bool
    {
        return $user->can('editar usuarios');
    }

    /**
     * Nadie se borra a sí mismo: dejaría el sistema sin esa cuenta y, si es
     * el único administrador, sin quien lo gestione.
     */
    public function delete(User $user, User $modelo): bool
    {
        return ! $user->is($modelo) && $user->can('eliminar usuarios');
    }

    public function restore(User $user, User $modelo): bool
    {
        return $user->can('editar usuarios');
    }

    public function forceDelete(User $user, User $modelo): bool
    {
        return ! $user->is($modelo) && $user->can('eliminar usuarios');
    }
}
