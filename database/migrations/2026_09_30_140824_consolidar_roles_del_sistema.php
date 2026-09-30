<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Los ocho roles heredados se reducen a los cinco perfiles que realmente
 * existen en la operación. Cada usuario conserva el acceso equivalente.
 *
 * Se conserva super_admin porque las políticas del módulo de Disciplina
 * Operativa anterior siguen dependiendo de él.
 */
return new class extends Migration
{
    /** Rol antiguo => rol que lo reemplaza. */
    private const EQUIVALENCIAS = [
        'gestor_documental' => 'responsable_hseq',
        'lider_om' => 'responsable_hseq',
        'operativo' => 'personal_tecnico',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::EQUIVALENCIAS as $anterior => $reemplazo) {
            $rolAnterior = Role::where('name', $anterior)->first();
            $rolNuevo = Role::firstOrCreate(['name' => $reemplazo, 'guard_name' => 'web']);

            if (! $rolAnterior) {
                continue;
            }

            foreach ($rolAnterior->users as $usuario) {
                // Quien ya tiene un rol equivalente no necesita otro: asignarlo
                // le daría más permisos de los que tenía.
                if ($usuario->hasAnyRole(array_values(self::EQUIVALENCIAS))) {
                    continue;
                }

                $usuario->assignRole($rolNuevo);
            }

            $rolAnterior->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        foreach (array_keys(self::EQUIVALENCIAS) as $anterior) {
            Role::firstOrCreate(['name' => $anterior, 'guard_name' => 'web']);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
