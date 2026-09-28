<?php

namespace App\Policies\Do;

use App\Models\Do\Contrato;
use App\Models\User;

class ContratoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function view(User $user, Contrato $contrato): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function create(User $user): bool
    {
        return $user->can('gestionar catalogos do');
    }

    public function update(User $user, Contrato $contrato): bool
    {
        return $user->can('gestionar catalogos do');
    }

    public function delete(User $user, Contrato $contrato): bool
    {
        return $user->can('gestionar catalogos do');
    }
}
