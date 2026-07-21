<?php

namespace Database\Seeders;

use App\Models\Compromiso;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Compromisos reales anunciados en el webinario de lanzamiento de Disciplina
 * Operativa (03/07/2026), diapositiva "¿Qué sigue?".
 */
class CompromisosDoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@confipetrol.com')->first();
        $gestor = User::where('email', 'gestor@confipetrol.com')->first();
        $operativo = User::where('email', 'operativo@confipetrol.com')->first();

        if (! $admin || ! $gestor || ! $operativo) {
            return;
        }

        $compromisos = [
            [
                'nombre' => 'Entregar Listado Maestro de Documentos actualizado (HSEQ-GCA1-F-10)',
                'descripcion' => 'Cada contrato debe entregar su listado maestro de documentos actualizado a corte de julio a Calidad Corporativa.',
                'fecha_limite' => '2026-07-17',
                'responsable_id' => $gestor->id,
                'rol_responsable' => 'responsable_hseq',
                'created_by' => $admin->id,
            ],
            [
                'nombre' => 'Entrega Etapa 1 — Inventario y priorización de actividades',
                'descripcion' => 'Realizar inventario de actividades y priorizar según nivel de amenaza (HSEQ-GCA1-F-17 Matriz Integral de Disciplina Operativa).',
                'fecha_limite' => '2026-07-31',
                'responsable_id' => $operativo->id,
                'rol_responsable' => 'lider_om',
                'created_by' => $admin->id,
            ],
            [
                'nombre' => 'Establecer cronograma de capacitaciones específicas por etapa',
                'descripcion' => 'Definir cronograma de capacitaciones por contrato y etapa, y consolidar la documentación previa.',
                'fecha_limite' => '2026-07-31',
                'responsable_id' => $admin->id,
                'rol_responsable' => 'calidad_corporativa',
                'created_by' => $admin->id,
            ],
            [
                'nombre' => 'Reportar indicadores de la metodología (mensual)',
                'descripcion' => 'Reportar mensualmente, a corte de mes, el avance de cada contrato en los indicadores DICACOCO.',
                'fecha_limite' => now()->endOfMonth()->toDateString(),
                'responsable_id' => $gestor->id,
                'rol_responsable' => 'responsable_hseq',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($compromisos as $datos) {
            Compromiso::firstOrCreate(
                ['nombre' => $datos['nombre']],
                $datos
            );
        }
    }
}
