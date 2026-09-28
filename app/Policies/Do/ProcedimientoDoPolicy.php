<?php

namespace App\Policies\Do;

use App\Models\Do\ProcedimientoDo;
use App\Models\User;

class ProcedimientoDoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function view(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function create(User $user): bool
    {
        return $user->can('crear procedimientos do');
    }

    public function update(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('editar procedimientos do');
    }

    public function delete(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('eliminar procedimientos do');
    }

    public function restore(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('eliminar procedimientos do');
    }

    public function forceDelete(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('eliminar procedimientos do');
    }
}
