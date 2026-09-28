<?php

namespace App\Policies\Do;

use App\Models\Do\Campo;
use App\Models\User;

class CampoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function view(User $user, Campo $campo): bool
    {
        return $user->can('ver procedimientos do');
    }

    public function create(User $user): bool
    {
        return $user->can('gestionar catalogos do');
    }

    public function update(User $user, Campo $campo): bool
    {
        return $user->can('gestionar catalogos do');
    }

    public function delete(User $user, Campo $campo): bool
    {
        return $user->can('gestionar catalogos do');
    }
}
