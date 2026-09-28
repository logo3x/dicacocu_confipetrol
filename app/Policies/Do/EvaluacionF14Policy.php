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
        return $user->can('ver procedimientos do');
    }

    public function create(User $user): bool
    {
        return $user->can('evaluar f14');
    }

    public function update(User $user, EvaluacionF14 $evaluacion): bool
    {
        return $user->can('evaluar f14');
    }

    public function delete(User $user, EvaluacionF14 $evaluacion): bool
    {
        return $user->can('evaluar f14');
    }
}
