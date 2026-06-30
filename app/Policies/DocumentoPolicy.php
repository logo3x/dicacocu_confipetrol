<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class DocumentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Documento $documento): bool
    {
        if (! $user->is_active) {
            return false;
        }

        // Documentos confidenciales solo para creador, responsable y aprobador
        if ($documento->confidencial) {
            return in_array($user->id, [
                $documento->created_by,
                $documento->responsable_id,
                $documento->aprobador_id,
            ], true) || $user->hasRole(['super_admin', 'admin']);
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin', 'gestor_documental']);
    }

    public function update(User $user, Documento $documento): bool
    {
        if (! $user->is_active) {
            return false;
        }

        // Solo el creador puede editar en borrador
        if ($documento->estado === 'borrador') {
            return $user->id === $documento->created_by || $user->hasRole(['super_admin', 'admin']);
        }

        // Aprobadores pueden editar en revisión
        if ($documento->estado === 'en_revision') {
            return in_array($user->id, [$documento->aprobador_id, $documento->responsable_id], true)
                || $user->hasRole(['super_admin', 'admin']);
        }

        return $user->hasRole(['super_admin', 'admin']);
    }

    public function delete(User $user, Documento $documento): bool
    {
        if (! $user->is_active) {
            return false;
        }

        // Solo borrador puede eliminarse por el creador; admins pueden eliminar cualquiera
        if ($documento->estado === 'borrador') {
            return $user->id === $documento->created_by || $user->hasRole(['super_admin', 'admin']);
        }

        return $user->hasRole(['super_admin', 'admin']);
    }

    public function restore(User $user, Documento $documento): bool
    {
        return $user->is_active && $user->hasRole(['super_admin', 'admin']);
    }

    public function forceDelete(User $user, Documento $documento): bool
    {
        return $user->is_active && $user->hasRole('super_admin');
    }
}
