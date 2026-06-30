<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Documentos
            'ver documentos',
            'crear documentos',
            'editar documentos',
            'eliminar documentos',
            'aprobar documentos',
            'divulgar documentos',
            'ver documentos confidenciales',
            // Carpetas
            'ver carpetas',
            'crear carpetas',
            'editar carpetas',
            'eliminar carpetas',
            // Ciclos DICACOCU
            'ver ciclos',
            'crear ciclos',
            'editar ciclos',
            'eliminar ciclos',
            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'gestionar roles',
            // Reportes
            'ver reportes',
            'exportar reportes',
            // Sistema
            'acceder panel admin',
            'ver logs actividad',
            'configurar sistema',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'ver documentos', 'crear documentos', 'editar documentos', 'eliminar documentos',
            'aprobar documentos', 'divulgar documentos', 'ver documentos confidenciales',
            'ver carpetas', 'crear carpetas', 'editar carpetas', 'eliminar carpetas',
            'ver ciclos', 'crear ciclos', 'editar ciclos', 'eliminar ciclos',
            'ver usuarios', 'crear usuarios', 'editar usuarios',
            'ver reportes', 'exportar reportes',
            'acceder panel admin', 'ver logs actividad',
        ]);

        $gestor = Role::firstOrCreate(['name' => 'gestor_documental']);
        $gestor->givePermissionTo([
            'ver documentos', 'crear documentos', 'editar documentos',
            'aprobar documentos', 'divulgar documentos',
            'ver carpetas', 'crear carpetas', 'editar carpetas',
            'ver ciclos', 'crear ciclos', 'editar ciclos',
            'ver reportes',
            'acceder panel admin',
        ]);

        $revisor = Role::firstOrCreate(['name' => 'revisor']);
        $revisor->givePermissionTo([
            'ver documentos', 'editar documentos',
            'ver carpetas',
            'ver ciclos',
            'acceder panel admin',
        ]);

        $colaborador = Role::firstOrCreate(['name' => 'colaborador']);
        $colaborador->givePermissionTo([
            'ver documentos',
            'crear documentos',
            'ver carpetas',
            'acceder panel admin',
        ]);

        $consultor = Role::firstOrCreate(['name' => 'consultor']);
        $consultor->givePermissionTo([
            'ver documentos',
            'ver carpetas',
            'acceder panel admin',
        ]);
    }
}
