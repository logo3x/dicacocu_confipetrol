<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Permisos del módulo de Procedimientos DO (Matriz Integral + formato F-14).
 * No altera los permisos del módulo de Disciplina Operativa existente.
 */
class DoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'ver procedimientos do',
            'crear procedimientos do',
            'editar procedimientos do',
            'eliminar procedimientos do',
            'evaluar f14',
            'cerrar acciones f14',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        if ($superAdmin = Role::where('name', 'super_admin')->first()) {
            $superAdmin->givePermissionTo($permisos);
        }

        if ($admin = Role::where('name', 'admin')->first()) {
            $admin->givePermissionTo($permisos);
        }

        if ($gestor = Role::where('name', 'gestor_documental')->first()) {
            $gestor->givePermissionTo([
                'ver procedimientos do',
                'crear procedimientos do',
                'editar procedimientos do',
                'evaluar f14',
            ]);
        }

        if ($hseq = Role::where('name', 'responsable_hseq')->first()) {
            $hseq->givePermissionTo([
                'ver procedimientos do',
                'crear procedimientos do',
                'editar procedimientos do',
                'evaluar f14',
                'cerrar acciones f14',
            ]);
        }

        if ($operativo = Role::where('name', 'operativo')->first()) {
            $operativo->givePermissionTo(['ver procedimientos do']);
        }

        if ($tecnico = Role::where('name', 'personal_tecnico')->first()) {
            $tecnico->givePermissionTo(['ver procedimientos do', 'evaluar f14']);
        }

        if ($lider = Role::where('name', 'lider_om')->first()) {
            $lider->givePermissionTo(['ver procedimientos do', 'crear procedimientos do', 'editar procedimientos do']);
        }
    }
}
