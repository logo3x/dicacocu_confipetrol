<?php

namespace Database\Seeders;

use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\ActividadPersonalExpuesto;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Actividades y acompañamientos F-14 de ejemplo, para ilustrar el ciclo
 * completo de Disciplina Operativa: identificación de actividad → valoración
 * de amenaza → estandarización → socialización → verificación.
 */
class ActividadesEjemploSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@confipetrol.com')->firstOrFail();
        $gestor = User::where('email', 'gestor@confipetrol.com')->firstOrFail();
        $operativo = User::where('email', 'operativo@confipetrol.com')->firstOrFail();

        $docAlturas = Documento::where('codigo', 'HSEQ-SST-P-8')->first();
        $docEmergencias = Documento::where('codigo', 'HSEQ-MA-P-4')->first();
        $docControlDoc = Documento::where('codigo', 'HSEQ-GCA1-P-2')->first();

        // ──────────────────────────────────────────────────────────
        // 1. Trabajo en alturas — CONT-0001 — amenaza ALTA, ya estandarizada
        // ──────────────────────────────────────────────────────────
        $actividad1 = Actividad::create([
            'nombre' => 'Trabajo en alturas — mantenimiento de torre de perforación',
            'contrato' => 'CONT-0001',
            'campo' => 'Cusiana',
            'descripcion' => 'Inspección y mantenimiento correctivo de estructuras metálicas de la torre de perforación a más de 1.8 m de altura.',
            'personal_expuesto' => 6,
            'valoracion_amenaza' => 35,
            'fecha_identificacion' => Carbon::parse('2026-05-10'),
            'documento_id' => $docAlturas?->id,
            'responsable_id' => $gestor->id,
            'created_by' => $admin->id,
        ]);

        foreach ([$operativo, $gestor] as $persona) {
            $registro = ActividadPersonalExpuesto::create([
                'actividad_id' => $actividad1->id,
                'user_id' => $persona->id,
            ]);
            $registro->socializar($gestor, Carbon::parse('2026-06-01'));
        }

        $f14_1 = AcompanamientoVerificacion::create([
            'actividad_id' => $actividad1->id,
            'fecha_ejecucion' => Carbon::parse('2026-06-15'),
            'campo' => 'Cusiana',
            'area' => 'Perforación',
            'responsable_area_id' => $gestor->id,
            'tipo_verificacion' => 'verificacion_cumplimiento_do',
            'observador_id' => $gestor->id,
            'cargo_observador' => 'Gestor Documental / Responsable HSEQ',
            'acompanante_id' => $operativo->id,
            'cargo_acompanante' => 'Coordinador de Campo',
            'pasos_observados' => [
                'Verificación de permiso de trabajo en alturas vigente',
                'Inspección de arnés y línea de vida',
                'Anclaje en punto certificado',
                'Ascenso y ejecución de la inspección estructural',
                'Descenso y cierre del permiso de trabajo',
            ],
            'q1_procedimiento_disponible' => true,
            'q2_usa_epp_correctamente' => true,
            'q3_identifica_peligros_riesgos' => true,
            'q4_herramientas_disponibles' => true,
            'q5_area_limpia_ordenada' => true,
            'q6_aplica_controles' => true,
            'q7_procedimiento_actualizado' => true,
            'q8_procedimiento_facil_entendimiento' => true,
            'q9_procedimiento_divulgado' => true,
            'q10_personal_capacitado_certificado' => true,
            'q11_personal_mostro_habilidad' => true,
            'pasos_segun_procedimiento' => 5,
            'pasos_en_observacion' => 5,
            'hallazgos' => 'Personal aplica correctamente el procedimiento de trabajo en alturas. Se recomienda reforzar la inspección visual del arnés antes de cada uso.',
            'analisis_actividad' => 'sigue_correctamente',
            'created_by' => $gestor->id,
        ]);

        // ──────────────────────────────────────────────────────────
        // 2. Respuesta a emergencias ambientales — CONT-0001 — amenaza MEDIA
        // ──────────────────────────────────────────────────────────
        $actividad2 = Actividad::create([
            'nombre' => 'Simulacro de derrame menor de crudo en área de tanques',
            'contrato' => 'CONT-0001',
            'campo' => 'Cusiana',
            'descripcion' => 'Ejercicio de respuesta ante derrame menor, activación de kit de contención y cadena de llamadas.',
            'personal_expuesto' => 10,
            'valoracion_amenaza' => 68,
            'fecha_identificacion' => Carbon::parse('2026-04-20'),
            'documento_id' => $docEmergencias?->id,
            'responsable_id' => $operativo->id,
            'created_by' => $admin->id,
        ]);

        $f14_2 = AcompanamientoVerificacion::create([
            'actividad_id' => $actividad2->id,
            'fecha_ejecucion' => Carbon::parse('2026-06-20'),
            'campo' => 'Cusiana',
            'area' => 'HSEQ',
            'responsable_area_id' => $operativo->id,
            'tipo_verificacion' => 'verificacion_cumplimiento_do',
            'observador_id' => $operativo->id,
            'cargo_observador' => 'Coordinador de Campo',
            'pasos_observados' => [
                'Activación de la alarma de derrame',
                'Despliegue del kit de contención',
                'Cadena de llamadas a HSEQ y Gerencia',
                'Contención del área afectada',
            ],
            'q1_procedimiento_disponible' => true,
            'q2_usa_epp_correctamente' => true,
            'q3_identifica_peligros_riesgos' => true,
            'q4_herramientas_disponibles' => false,
            'q5_area_limpia_ordenada' => true,
            'q6_aplica_controles' => true,
            'q7_procedimiento_actualizado' => true,
            'q8_procedimiento_facil_entendimiento' => true,
            'q9_procedimiento_divulgado' => false,
            'q10_personal_capacitado_certificado' => true,
            'q11_personal_mostro_habilidad' => true,
            'pasos_segun_procedimiento' => 4,
            'pasos_en_observacion' => 3,
            'hallazgos' => 'El kit de contención no estaba completo (faltaban paños absorbentes). El procedimiento no ha sido divulgado a todo el personal del turno nocturno.',
            'analisis_actividad' => 'difusion_reentrenamiento',
            'plan_accion' => 'Reabastecer kit de contención antes del 15/07/2026. Programar sesión de divulgación para el turno nocturno.',
            'created_by' => $operativo->id,
        ]);

        // ──────────────────────────────────────────────────────────
        // 3. Permisos de trabajo en caliente — CONT-0002 — amenaza ALTA, sin estandarizar
        // ──────────────────────────────────────────────────────────
        $actividad3 = Actividad::create([
            'nombre' => 'Soldadura de tubería en línea de producción',
            'contrato' => 'CONT-0002',
            'campo' => 'Barrancabermeja',
            'descripcion' => 'Trabajo en caliente para reparación de fuga en línea de producción de crudo.',
            'personal_expuesto' => 4,
            'valoracion_amenaza' => 22,
            'fecha_identificacion' => Carbon::parse('2026-06-25'),
            'responsable_id' => $gestor->id,
            'created_by' => $gestor->id,
        ]);

        // Sin documento vinculado aún: ilustra una actividad pendiente de estandarizar.

        // ──────────────────────────────────────────────────────────
        // 4. Gestión documental / control de cambios — CONT-0002 — amenaza BAJA
        // ──────────────────────────────────────────────────────────
        $actividad4 = Actividad::create([
            'nombre' => 'Actualización y control de versiones de procedimientos HSEQ',
            'contrato' => 'CONT-0002',
            'campo' => 'Barrancabermeja',
            'descripcion' => 'Revisión periódica y control de cambios de los procedimientos del sistema de gestión documental.',
            'personal_expuesto' => 3,
            'valoracion_amenaza' => 92,
            'fecha_identificacion' => Carbon::parse('2026-03-01'),
            'documento_id' => $docControlDoc?->id,
            'responsable_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $registroAdmin = ActividadPersonalExpuesto::create([
            'actividad_id' => $actividad4->id,
            'user_id' => $admin->id,
        ]);
        $registroAdmin->socializar($admin, Carbon::parse('2025-05-01'));
        // Vencida a propósito: socializada hace más de 1 año.

        // ──────────────────────────────────────────────────────────
        // 5. Espacios confinados — CONT-0003 — amenaza ALTA, con detención por riesgo crítico
        // ──────────────────────────────────────────────────────────
        $actividad5 = Actividad::create([
            'nombre' => 'Limpieza interna de tanque de almacenamiento',
            'contrato' => 'CONT-0003',
            'campo' => 'Cusiana',
            'descripcion' => 'Ingreso a espacio confinado para limpieza y remoción de sedimentos en tanque de almacenamiento API 650.',
            'personal_expuesto' => 5,
            'valoracion_amenaza' => 18,
            'fecha_identificacion' => Carbon::parse('2026-06-01'),
            'responsable_id' => $operativo->id,
            'created_by' => $operativo->id,
        ]);

        $f14_5 = AcompanamientoVerificacion::create([
            'actividad_id' => $actividad5->id,
            'fecha_ejecucion' => Carbon::parse('2026-06-28'),
            'campo' => 'Cusiana',
            'area' => 'Producción',
            'responsable_area_id' => $operativo->id,
            'tipo_verificacion' => 'verificacion_cumplimiento_do',
            'observador_id' => $gestor->id,
            'cargo_observador' => 'Responsable HSEQ',
            'acompanante_id' => $operativo->id,
            'cargo_acompanante' => 'Coordinador de Campo',
            'pasos_observados' => [
                'Medición de gases previa al ingreso',
                'Verificación de permiso de espacio confinado',
                'Ingreso con vigía permanente',
            ],
            'q1_procedimiento_disponible' => true,
            'q2_usa_epp_correctamente' => false,
            'q3_identifica_peligros_riesgos' => false,
            'q4_herramientas_disponibles' => true,
            'q5_area_limpia_ordenada' => false,
            'q6_aplica_controles' => false,
            'q7_procedimiento_actualizado' => true,
            'q8_procedimiento_facil_entendimiento' => true,
            'q9_procedimiento_divulgado' => false,
            'q10_personal_capacitado_certificado' => false,
            'q11_personal_mostro_habilidad' => false,
            'pasos_segun_procedimiento' => 6,
            'pasos_en_observacion' => 3,
            'hallazgos' => 'La medición de gases no se realizó antes del ingreso. No hay vigía permanente en el punto de acceso. Personal sin certificación vigente para espacios confinados.',
            'analisis_actividad' => 'suspension_tareas',
            'plan_accion' => 'Suspender la actividad hasta certificar al personal y garantizar medición de gases y vigía permanente.',
            'actividad_detenida' => true,
            'motivo_detencion' => 'Ausencia de medición de gases previa al ingreso y de vigía permanente — riesgo de asfixia/intoxicación.',
            'created_by' => $gestor->id,
        ]);

        // ──────────────────────────────────────────────────────────
        // 6. Inspección Gerencial — Caminar la Planta — CONT-0001
        // ──────────────────────────────────────────────────────────
        $f14_6 = AcompanamientoVerificacion::create([
            'actividad_id' => $actividad1->id,
            'fecha_ejecucion' => Carbon::parse('2026-06-30'),
            'campo' => 'Cusiana',
            'area' => 'Perforación',
            'responsable_area_id' => $gestor->id,
            'tipo_verificacion' => 'inspeccion_gerencial_caminar_planta',
            'observador_id' => $admin->id,
            'cargo_observador' => 'Coordinadora de Calidad',
            'acompanante_id' => $gestor->id,
            'cargo_acompanante' => 'Responsable HSEQ',
            'created_by' => $admin->id,
        ]);

        $inspeccion = $f14_6->fresh()->inspeccionGerencial;
        $inspeccion->update([
            'hallazgos_positivos' => 'Uso consistente de EPP en toda el área. Personal identifica correctamente los puntos de anclaje certificados. Buena disposición de extintores y señalización de rutas de evacuación.',
            'desvios_oportunidades_mejora' => 'Se observó un cilindro de gas sin asegurar cerca del área de soldadura. Falta actualizar el rótulo de un punto de acopio de residuos peligrosos.',
        ]);

        $inspeccion->reglas()->where('numero_regla', 1)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 2)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 3)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 4)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 5)->update(['cumple' => 'na']);
        $inspeccion->reglas()->where('numero_regla', 6)->update(['cumple' => 'no']);
        $inspeccion->reglas()->where('numero_regla', 7)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 8)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 9)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 10)->update(['cumple' => 'si']);
        $inspeccion->reglas()->where('numero_regla', 11)->update(['cumple' => 'na']);
        $inspeccion->reglas()->where('numero_regla', 12)->update(['cumple' => 'si']);

        $inspeccion->acciones()->create([
            'accion' => 'Asegurar el cilindro de gas cerca del área de soldadura con cadena y base fija.',
            'responsable_id' => $operativo->id,
            'fecha_cierre' => Carbon::parse('2026-07-10'),
        ]);

        $inspeccion->acciones()->create([
            'accion' => 'Actualizar rótulo del punto de acopio de residuos peligrosos según la matriz de clasificación vigente.',
            'responsable_id' => $gestor->id,
            'fecha_cierre' => Carbon::parse('2026-07-15'),
        ]);

        $this->command?->info('✓ Actividades y acompañamientos F-14 de ejemplo creados.');
        $this->command?->info('  - Actividades: '.Actividad::count());
        $this->command?->info('  - Acompañamientos F-14: '.AcompanamientoVerificacion::count());
        $this->command?->info('  - Personal expuesto registrado: '.ActividadPersonalExpuesto::count());
    }
}
