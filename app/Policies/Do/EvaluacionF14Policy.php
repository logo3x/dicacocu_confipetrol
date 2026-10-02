<?php

namespace App\Policies\Do;

use App\Models\Do\EvaluacionF14;
use App\Models\User;

class EvaluacionF14Policy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function view(User $user, EvaluacionF14 $evaluacion): bool
    {
        return $user->can('ver procedimientos do')
            && $this->perteneceASuContrato($user, $evaluacion);
    }

    public function create(User $user): bool
    {
        return $user->can('evaluar f14');
    }

    public function update(User $user, EvaluacionF14 $evaluacion): bool
    {
        return $user->can('evaluar f14')
            && $this->perteneceASuContrato($user, $evaluacion);
    }

    public function delete(User $user, EvaluacionF14 $evaluacion): bool
    {
        return $user->can('evaluar f14')
            && $this->perteneceASuContrato($user, $evaluacion);
    }

    /**
     * La evaluación hereda el alcance de su procedimiento: quien no puede ver
     * el procedimiento tampoco debe tocar sus verificaciones, aunque llegue
     * por una ruta que no pase por el listado.
     */
    private function perteneceASuContrato(User $user, EvaluacionF14 $evaluacion): bool
    {
        if ($user->veTodosLosContratos()) {
            return true;
        }

        $procedimiento = $evaluacion->procedimiento;

        return $procedimiento !== null
            && $user->contrato_id !== null
            && $user->contrato_id === $procedimiento->contrato_id;
    }
}
