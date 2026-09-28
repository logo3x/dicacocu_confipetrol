<?php

namespace Database\Seeders;

use App\Enums\Do\CumpleRegla;
use App\Enums\Do\ReglaSalvaVidas;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Datos de demostración del módulo de Procedimientos DO.
 */
class DoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $hseq = User::where('email', 'gestor@confipetrol.com')->first();
        $operativo = User::where('email', 'operativo@confipetrol.com')->first();
        $admin = User::where('email', 'admin@confipetrol.com')->first();

        if (! $hseq || ! $operativo) {
            return;
        }

        $procedimientos = [
            [
                'nombre_actividad' => 'Mantenimiento de bomba centrífuga de crudo',
                'contrato' => 'Ecopetrol Cusiana',
                'campo' => 'Cusiana',
                'personas_involucradas' => 12,
                'amenaza_riesgo_critico' => true,
                'amenaza_equipos_criticos' => true,
                'amenaza_impacto_ambiental' => true,
                'amenaza_antecedentes' => true,
                'amenaza_afecta_servicio' => true,
                'codificado' => true,
                'codigo_asignado' => 'HSEQ-GCA1-P-01',
                'titulo_procedimiento' => 'Procedimiento de mantenimiento de bombas centrífugas',
                'version_actual' => 3,
                'ubicacion_acceso' => 'SharePoint / HSEQ / Procedimientos',
                'personas_socializadas' => 11,
            ],
            [
                'nombre_actividad' => 'Inspección de líneas de proceso en altura',
                'contrato' => 'Frontera Quifa',
                'campo' => 'Quifa',
                'personas_involucradas' => 8,
                'amenaza_riesgo_critico' => true,
                'amenaza_antecedentes' => true,
                'amenaza_afecta_servicio' => true,
                'codificado' => true,
                'codigo_asignado' => 'HSEQ-GCA1-P-02',
                'titulo_procedimiento' => 'Procedimiento de inspección de líneas en altura',
                'version_actual' => 2,
                'ubicacion_acceso' => 'SharePoint / HSEQ / Procedimientos',
                'personas_socializadas' => 6,
            ],
            [
                'nombre_actividad' => 'Limpieza de área administrativa',
                'contrato' => 'Ecopetrol Cusiana',
                'campo' => 'Cusiana',
                'personas_involucradas' => 4,
                'amenaza_no_rutinaria' => true,
                'personas_socializadas' => 4,
            ],
        ];

        foreach ($procedimientos as $datos) {
            $procedimiento = ProcedimientoDo::firstOrCreate(
                ['nombre_actividad' => $datos['nombre_actividad']],
                array_merge($datos, [
                    'anio_ciclo' => now()->year,
                    'fecha_identificacion' => now()->subMonths(2),
                    'responsable_area_id' => $operativo->id,
                    'observador_operativo_id' => $operativo->id,
                    'observador_hseq_id' => $hseq->id,
                    'responsable_codificacion_id' => $admin?->id,
                    'fecha_codificacion' => now()->subMonth(),
                    'fecha_ultima_divulgacion' => now()->subWeeks(3),
                    'created_by' => $admin?->id,
                ]),
            );

            if (! $procedimiento->estaEstandarizado() || $procedimiento->evaluaciones()->exists()) {
                continue;
            }

            $evaluacion = EvaluacionF14::create([
                'procedimiento_id' => $procedimiento->id,
                'fecha_ejecucion' => now()->subWeeks(2),
                'campo' => $procedimiento->campo,
                'area' => 'Operaciones',
                'responsable_area_id' => $operativo->id,
                'nombre_actividad_observada' => $procedimiento->nombre_actividad,
                'observador_id' => $hseq->id,
                'cargo_observador' => 'Responsable HSEQ',
                'acompanante_id' => $operativo->id,
                'cargo_acompanante' => 'Coordinador de Campo',
                'pasos_observados' => ['Alistamiento', 'Bloqueo de energías', 'Ejecución', 'Cierre'],
                'q1' => true, 'q2' => true, 'q3' => true, 'q4' => true, 'q5' => true,
                'q6' => true, 'q7' => true, 'q8' => true, 'q9' => true, 'q10' => true,
                'q11' => false,
                'pasos_segun_procedimiento' => 4,
                'pasos_en_observacion' => 4,
                'analisis_actividad' => 'La actividad se ejecutó conforme al procedimiento estandarizado.',
                'aplica_inspeccion_gerencial' => true,
                'hallazgos_positivos' => 'El personal demostró conocimiento del procedimiento.',
                'desvios_oportunidades' => 'Reforzar la señalización del área de trabajo.',
                'created_by' => $hseq->id,
            ]);

            foreach (ReglaSalvaVidas::cases() as $regla) {
                $evaluacion->reglas()->create([
                    'numero_regla' => $regla->value,
                    'cumple' => $regla === ReglaSalvaVidas::TrabajoAlturas
                        ? CumpleRegla::No->value
                        : CumpleRegla::Si->value,
                ]);
            }

            $evaluacion->acciones()->create([
                'accion' => 'Reforzar la señalización del área de trabajo',
                'responsable_id' => $operativo->id,
                'fecha_cierre' => now()->addWeeks(3),
            ]);
        }
    }
}
