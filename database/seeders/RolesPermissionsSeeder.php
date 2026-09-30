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
            // Disciplina Operativa — Actividades y amenazas (Etapa 1-2)
            'ver actividades',
            'crear actividades',
            'editar actividades',
            'eliminar actividades',
            'valorar amenaza actividad',
            // Disciplina Operativa — Comunicación (Etapa 3)
            'socializar procedimiento',
            // Disciplina Operativa — Verificación F-14 (Etapa 4)
            'evaluar actividad operativo',
            'evaluar actividad hseq',
            'detener actividad por riesgo critico',
            // Disciplina Operativa — Indicadores y gestión corporativa
            'consolidar indicadores do',
            'gestionar capacitaciones do',
            'gestionar compromisos do',
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
            'ver actividades', 'crear actividades', 'editar actividades', 'eliminar actividades',
            'valorar amenaza actividad', 'consolidar indicadores do',
            'gestionar capacitaciones do', 'gestionar compromisos do',
        ]);

        // ── Roles de negocio — Disciplina Operativa (Confipetrol) ──────────

        // calidad_corporativa: capacitaciones, seguimiento de implementación, consolidación de indicadores
        $calidadCorporativa = Role::firstOrCreate(['name' => 'calidad_corporativa']);
        $calidadCorporativa->syncPermissions([
            'ver actividades',
            'crear actividades',
            'editar actividades',
            'valorar amenaza actividad',
            'ver documentos',
            'ver ciclos',
            'crear ciclos',
            'editar ciclos',
            'ver reportes',
            'exportar reportes',
            'consolidar indicadores do',
            'gestionar capacitaciones do',
            'gestionar compromisos do',
            'acceder panel admin',
        ]);

        // responsable_hseq: responsable HSEQ del contrato — gestión documental,
        // riesgos y evaluador HSEQ en el F-14. Absorbe al antiguo gestor
        // documental y al líder O&M, que hacían lo mismo sobre el módulo nuevo.
        $responsableHseq = Role::firstOrCreate(['name' => 'responsable_hseq']);
        $responsableHseq->syncPermissions([
            'ver actividades',
            'crear actividades',
            'editar actividades',
            'valorar amenaza actividad',
            'ver carpetas',
            'crear carpetas',
            'editar carpetas',
            'crear ciclos',
            'editar ciclos',
            'divulgar documentos',
            'gestionar compromisos do',
            'ver documentos',
            'crear documentos',
            'editar documentos',
            'aprobar documentos',
            'socializar procedimiento',
            'evaluar actividad hseq',
            'detener actividad por riesgo critico',
            'ver reportes',
            'acceder panel admin',
        ]);

        // personal_tecnico: aplica procedimientos y participa en verificaciones
        // como evaluador operativo. Absorbe al antiguo rol operativo.
        $personalTecnico = Role::firstOrCreate(['name' => 'personal_tecnico']);
        $personalTecnico->syncPermissions([
            'ver actividades',
            'ver documentos',
            'ver carpetas',
            'ver ciclos',
            'registrar ejecucion procedimiento',
            'evaluar actividad operativo',
            'detener actividad por riesgo critico',
            'acceder panel admin',
        ]);
    }
}
