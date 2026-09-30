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
        return $user->can('ver procedimientos do')
            && $this->perteneceASuContrato($user, $procedimiento);
    }

    public function create(User $user): bool
    {
        return $user->can('crear procedimientos do');
    }

    public function update(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('editar procedimientos do')
            && $this->perteneceASuContrato($user, $procedimiento);
    }

    public function delete(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->can('eliminar procedimientos do')
            && $this->perteneceASuContrato($user, $procedimiento);
    }

    public function restore(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $this->delete($user, $procedimiento);
    }

    public function forceDelete(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $this->delete($user, $procedimiento);
    }

    /**
     * Cada usuario trabaja sobre los procedimientos de su contrato; los
     * administradores no tienen esa restricción.
     */
    private function perteneceASuContrato(User $user, ProcedimientoDo $procedimiento): bool
    {
        return $user->veTodosLosContratos()
            || ($user->contrato_id !== null && $user->contrato_id === $procedimiento->contrato_id);
    }
}
