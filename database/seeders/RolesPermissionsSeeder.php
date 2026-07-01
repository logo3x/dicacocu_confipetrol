<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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
            // Operativo de campo
            'registrar ejecucion procedimiento',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // super_admin: acceso total (Gate::before lo garantiza, pero tiene todos los permisos también)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // admin: gestión completa del ciclo documental y usuarios
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'ver documentos', 'crear documentos', 'editar documentos', 'eliminar documentos',
            'aprobar documentos', 'divulgar documentos', 'ver documentos confidenciales',
            'ver carpetas', 'crear carpetas', 'editar carpetas', 'eliminar carpetas',
            'ver ciclos', 'crear ciclos', 'editar ciclos', 'eliminar ciclos',
            'ver usuarios', 'crear usuarios', 'editar usuarios',
            'ver reportes', 'exportar reportes',
            'acceder panel admin', 'ver logs actividad',
        ]);

        // gestor_documental: opera el ciclo documental (crea, versiona, aprueba, divulga)
        $gestor = Role::firstOrCreate(['name' => 'gestor_documental']);
        $gestor->syncPermissions([
            'ver documentos', 'crear documentos', 'editar documentos',
            'aprobar documentos', 'divulgar documentos',
            'ver carpetas', 'crear carpetas', 'editar carpetas',
            'ver ciclos', 'crear ciclos', 'editar ciclos',
            'ver reportes',
            'acceder panel admin',
        ]);

        // operativo: coordinador de campo — lee procedimientos aprobados y registra ejecuciones
        $operativo = Role::firstOrCreate(['name' => 'operativo']);
        $operativo->syncPermissions([
            'ver documentos',
            'ver carpetas',
            'ver ciclos',
            'acceder panel admin',
            'registrar ejecucion procedimiento',
        ]);
    }
}
