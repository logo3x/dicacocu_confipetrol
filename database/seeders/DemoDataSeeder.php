<?php

namespace Database\Seeders;

use App\Models\Carpeta;
use App\Models\CicloDicacocu;
use App\Models\Documento;
use App\Models\DocumentoVersion;
use App\Models\LecturaDocumento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar logs de actividad para no saturar la tabla
        activity()->disableLogging();

        $admin = User::where('email', 'admin@confipetrol.com')->firstOrFail();
        $gestor = User::where('email', 'gestor@confipetrol.com')->firstOrFail();
        $operativo = User::where('email', 'operativo@confipetrol.com')->firstOrFail();
        $superadmin = User::where('email', 'superadmin@confipetrol.com')->firstOrFail();

        // ──────────────────────────────────────────────────────────
        // 1. CARPETAS (árbol de 3 niveles)
        // ──────────────────────────────────────────────────────────
        $carpetaHseq = Carpeta::firstOrCreate(
            ['codigo' => 'HSEQ-ROOT'],
            [
                'nombre' => 'HSEQ — Salud, Seguridad, Ambiente y Calidad',
                'descripcion' => 'Documentos del sistema de gestión HSEQ para contratos Ecopetrol.',
                'created_by' => $admin->id,
                'color' => '#C8102E',
                'icono' => 'fa-shield-halved',
                'is_public' => false,
                'orden' => 1,
            ]
        );

        $carpetaOps = Carpeta::firstOrCreate(
            ['codigo' => 'OPS-ROOT'],
            [
                'nombre' => 'Operaciones de Campo',
                'descripcion' => 'Procedimientos operativos para personal en campo Barrancabermeja y Cusiana.',
                'created_by' => $admin->id,
                'color' => '#0050A0',
                'icono' => 'fa-gears',
                'is_public' => false,
                'orden' => 2,
            ]
        );

        $carpetaCalidad = Carpeta::firstOrCreate(
            ['codigo' => 'CAL-ROOT'],
            [
                'nombre' => 'Gestión de Calidad',
                'descripcion' => 'Políticas, manuales y formatos del sistema de gestión de calidad ISO 9001.',
                'created_by' => $admin->id,
                'color' => '#0A7B3E',
                'icono' => 'fa-circle-check',
                'is_public' => true,
                'orden' => 3,
            ]
        );

        $carpetaLegal = Carpeta::firstOrCreate(
            ['codigo' => 'LEG-ROOT'],
            [
                'nombre' => 'Marco Legal y Contractual',
                'descripcion' => 'Normatividad Ecopetrol, contratos y requisitos legales aplicables.',
                'created_by' => $admin->id,
                'color' => '#7C3AED',
                'icono' => 'fa-gavel',
                'is_public' => false,
                'orden' => 4,
            ]
        );

        // Subcarpetas de HSEQ
        $subHseqProc = Carpeta::firstOrCreate(
            ['codigo' => 'HSEQ-PROC'],
            [
                'nombre' => 'Procedimientos HSEQ',
                'parent_id' => $carpetaHseq->id,
                'created_by' => $admin->id,
                'color' => '#C8102E',
                'icono' => 'fa-file-lines',
                'is_public' => false,
                'orden' => 1,
            ]
        );

        $subHseqFormatos = Carpeta::firstOrCreate(
            ['codigo' => 'HSEQ-FORM'],
            [
                'nombre' => 'Formatos y Registros HSEQ',
                'parent_id' => $carpetaHseq->id,
                'created_by' => $admin->id,
                'color' => '#E8871A',
                'icono' => 'fa-clipboard-list',
                'is_public' => false,
                'orden' => 2,
            ]
        );

        // Subcarpetas de Operaciones
        $subOpsMantenimiento = Carpeta::firstOrCreate(
            ['codigo' => 'OPS-MANT'],
            [
                'nombre' => 'Mantenimiento Preventivo',
                'parent_id' => $carpetaOps->id,
                'created_by' => $gestor->id,
                'color' => '#0050A0',
                'icono' => 'fa-wrench',
                'is_public' => false,
                'orden' => 1,
            ]
        );

        $subOpsEmergencias = Carpeta::firstOrCreate(
            ['codigo' => 'OPS-EMER'],
            [
                'nombre' => 'Respuesta a Emergencias',
                'parent_id' => $carpetaOps->id,
                'created_by' => $gestor->id,
                'color' => '#DC2626',
                'icono' => 'fa-triangle-exclamation',
                'is_public' => false,
                'orden' => 2,
            ]
        );

        // ──────────────────────────────────────────────────────────
        // 2. DOCUMENTOS — 40 documentos realistas
        // ──────────────────────────────────────────────────────────
        $documentos = [
            // ── PROCEDIMIENTOS HSEQ (aprobados / divulgados) ──────
            [
                'titulo' => 'Procedimiento de Gestión y Control de Documentos DICACOCU',
                'codigo' => 'HSEQ-GCA1-P-2',
                'descripcion' => 'Establece los lineamientos para la elaboración, revisión, aprobación, distribución y control de los documentos del SGD en los contratos Ecopetrol.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqProc->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2024-01-15',
                'fecha_revision' => '2025-01-15',
                'fecha_vencimiento' => '2025-12-31',
                'version_actual' => 3,
                'tags' => ['sgd', 'control-documentos', 'dicacocu', 'ecopetrol'],
                'confidencial' => false,
                'visitas' => 142,
            ],
            [
                'titulo' => 'Procedimiento para Trabajo en Alturas — Contratos Ecopetrol',
                'codigo' => 'HSEQ-SST-P-8',
                'descripcion' => 'Define los requisitos mínimos para el trabajo seguro en alturas en instalaciones de Ecopetrol administradas por Confipetrol, incluyendo permisos de trabajo y EPP requerido.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqProc->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => '2024-03-01',
                'fecha_revision' => '2025-03-01',
                'fecha_vencimiento' => '2026-03-01',
                'version_actual' => 2,
                'tags' => ['sst', 'alturas', 'epp', 'permiso-trabajo'],
                'confidencial' => false,
                'visitas' => 89,
            ],
            [
                'titulo' => 'Procedimiento de Respuesta a Emergencias Ambientales',
                'codigo' => 'HSEQ-MA-P-4',
                'descripcion' => 'Protocolo de actuación ante derrames, incendios o eventos ambientales en zonas de operación Ecopetrol. Incluye cadena de llamadas y acciones inmediatas.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'aprobado',
                'carpeta_id' => $subOpsEmergencias->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2024-06-10',
                'fecha_revision' => '2025-06-10',
                'fecha_vencimiento' => '2026-06-10',
                'version_actual' => 1,
                'tags' => ['ambiental', 'emergencias', 'derrame'],
                'confidencial' => false,
                'visitas' => 67,
            ],
            [
                'titulo' => 'Procedimiento de Inspección de Equipos Críticos — Líneas de Transporte',
                'codigo' => 'OPS-MANT-P-12',
                'descripcion' => 'Establece la frecuencia, metodología e instrumentos para la inspección de equipos críticos de transporte de hidrocarburos en los campos administrados.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'divulgado',
                'carpeta_id' => $subOpsMantenimiento->id,
                'created_by' => $gestor->id,
                'responsable_id' => $operativo->id,
                'aprobador_id' => $admin->id,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => '2024-02-20',
                'fecha_revision' => '2025-02-20',
                'fecha_vencimiento' => '2025-08-20',
                'version_actual' => 2,
                'tags' => ['mantenimiento', 'equipos-críticos', 'inspección'],
                'confidencial' => false,
                'visitas' => 203,
            ],
            [
                'titulo' => 'Procedimiento de Gestión del Cambio Organizacional',
                'codigo' => 'ADM-GCA-P-5',
                'descripcion' => 'Define la metodología para identificar, evaluar y gestionar cambios en procesos, equipos, personas o instalaciones que puedan afectar la seguridad o continuidad operacional.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'en_revision',
                'carpeta_id' => $carpetaCalidad->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => null,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2025-01-10',
                'fecha_revision' => '2025-07-10',
                'fecha_vencimiento' => null,
                'version_actual' => 1,
                'tags' => ['gestión-cambio', 'calidad'],
                'confidencial' => false,
                'visitas' => 12,
            ],
            // ── INSTRUCTIVOS ──────────────────────────────────────
            [
                'titulo' => 'Instructivo para Diligenciamiento de la Matriz Integral F-17',
                'codigo' => 'HSEQ-GCA1-I-3',
                'descripcion' => 'Guía paso a paso para el correcto diligenciamiento del formato HSEQ-GCA1-F-17 (Matriz Integral de Control Documental), incluyendo ejemplos de casos reales.',
                'tipo_documento' => 'instructivo',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqProc->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CU',
                'fecha_emision' => '2024-04-01',
                'fecha_revision' => '2025-04-01',
                'fecha_vencimiento' => '2026-04-01',
                'version_actual' => 2,
                'tags' => ['instructivo', 'f-17', 'matriz-integral'],
                'confidencial' => false,
                'visitas' => 318,
            ],
            [
                'titulo' => 'Instructivo para Operación de Sistema de Medición en Pozos',
                'codigo' => 'OPS-MEDI-I-7',
                'descripcion' => 'Guía operativa para la lectura, calibración y registro de mediciones en sistemas de medición de producción de pozos en campos Ecopetrol.',
                'tipo_documento' => 'instructivo',
                'estado' => 'aprobado',
                'carpeta_id' => $subOpsMantenimiento->id,
                'created_by' => $gestor->id,
                'responsable_id' => $operativo->id,
                'aprobador_id' => $admin->id,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => '2024-09-15',
                'fecha_revision' => '2025-09-15',
                'fecha_vencimiento' => '2026-09-15',
                'version_actual' => 1,
                'tags' => ['medición', 'pozos', 'operación'],
                'confidencial' => false,
                'visitas' => 55,
            ],
            [
                'titulo' => 'Instructivo de Uso del SGD DICACOCU para Personal Operativo',
                'codigo' => 'TI-SGD-I-1',
                'descripcion' => 'Manual de usuario simplificado para el acceso, consulta y confirmación de lectura de documentos en el SGD DICACOCU por parte del personal de campo.',
                'tipo_documento' => 'instructivo',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaCalidad->id,
                'created_by' => $admin->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2025-01-20',
                'fecha_revision' => '2026-01-20',
                'fecha_vencimiento' => '2026-12-31',
                'version_actual' => 1,
                'tags' => ['sgd', 'capacitación', 'usuario'],
                'confidencial' => false,
                'visitas' => 87,
            ],
            // ── FORMATOS ──────────────────────────────────────────
            [
                'titulo' => 'Matriz Integral de Control Documental — F-17',
                'codigo' => 'HSEQ-GCA1-F-17',
                'descripcion' => 'Formato unificado que consolida el control de documentos del SGD DICACOCU, reemplazando los formatos F-11, F-12 y F-13 según actualización HSEQ-GCA1-P-2 versión 3.',
                'tipo_documento' => 'formato',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqFormatos->id,
                'created_by' => $admin->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2024-06-26',
                'fecha_revision' => '2025-06-26',
                'fecha_vencimiento' => '2026-06-26',
                'version_actual' => 1,
                'tags' => ['formato', 'f-17', 'matriz-integral', 'obligatorio'],
                'confidencial' => false,
                'visitas' => 425,
            ],
            [
                'titulo' => 'Formato de Permiso de Trabajo Seguro — PTS',
                'codigo' => 'HSEQ-SST-F-3',
                'descripcion' => 'Formato oficial para la solicitud y autorización de permisos de trabajo en actividades de alto riesgo (alturas, espacios confinados, caliente, eléctrico).',
                'tipo_documento' => 'formato',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqFormatos->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2024-02-01',
                'fecha_revision' => '2025-02-01',
                'fecha_vencimiento' => '2026-02-01',
                'version_actual' => 4,
                'tags' => ['pts', 'permiso-trabajo', 'sst'],
                'confidencial' => false,
                'visitas' => 512,
            ],
            [
                'titulo' => 'Formato de Registro de Inspección Preoperacional de Equipos',
                'codigo' => 'OPS-MANT-F-6',
                'descripcion' => 'Formato diario para el registro de inspección preoperacional de equipos móviles, grúas y vehículos en operación en campo Ecopetrol.',
                'tipo_documento' => 'formato',
                'estado' => 'aprobado',
                'carpeta_id' => $subHseqFormatos->id,
                'created_by' => $gestor->id,
                'responsable_id' => $operativo->id,
                'aprobador_id' => $admin->id,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => '2024-07-01',
                'fecha_revision' => '2025-07-01',
                'fecha_vencimiento' => '2026-07-01',
                'version_actual' => 2,
                'tags' => ['inspección', 'preoperacional', 'equipos'],
                'confidencial' => false,
                'visitas' => 177,
            ],
            [
                'titulo' => 'Formato de Reporte de Actos y Condiciones Inseguras',
                'codigo' => 'HSEQ-SST-F-11',
                'descripcion' => 'Formulario para el reporte inmediato de actos inseguros, condiciones peligrosas o casi-accidentes identificados en área de trabajo.',
                'tipo_documento' => 'formato',
                'estado' => 'divulgado',
                'carpeta_id' => $subHseqFormatos->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CU',
                'fecha_emision' => '2023-11-01',
                'fecha_revision' => '2024-11-01',
                'fecha_vencimiento' => '2025-11-01',
                'version_actual' => 3,
                'tags' => ['actos-inseguros', 'reporte', 'sst'],
                'confidencial' => false,
                'visitas' => 298,
            ],
            // ── MANUALES ──────────────────────────────────────────
            [
                'titulo' => 'Manual del Sistema de Gestión Integrado — Confipetrol',
                'codigo' => 'CAL-SGI-M-1',
                'descripcion' => 'Documento maestro del Sistema de Gestión Integrado (HSEQ + Calidad ISO 9001) de Confipetrol. Define política, objetivos, estructura organizacional y mapa de procesos.',
                'tipo_documento' => 'manual',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaCalidad->id,
                'created_by' => $admin->id,
                'responsable_id' => $superadmin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2023-06-01',
                'fecha_revision' => '2025-06-01',
                'fecha_vencimiento' => '2026-06-01',
                'version_actual' => 5,
                'tags' => ['manual', 'sgi', 'iso-9001', 'política'],
                'confidencial' => false,
                'visitas' => 634,
            ],
            [
                'titulo' => 'Manual de Operación — Estación de Compresión Cusiana',
                'codigo' => 'OPS-CUSI-M-3',
                'descripcion' => 'Manual técnico-operativo de la estación de compresión del Campo Cusiana. Incluye descripción de equipos, parámetros operacionales, alarmas y procedimientos de arranque/parada.',
                'tipo_documento' => 'manual',
                'estado' => 'aprobado',
                'carpeta_id' => $subOpsMantenimiento->id,
                'created_by' => $gestor->id,
                'responsable_id' => $operativo->id,
                'aprobador_id' => $admin->id,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => '2024-05-15',
                'fecha_revision' => '2025-05-15',
                'fecha_vencimiento' => '2027-05-15',
                'version_actual' => 2,
                'tags' => ['manual', 'cusiana', 'compresión', 'operación'],
                'confidencial' => true,
                'visitas' => 41,
            ],
            // ── POLÍTICAS ──────────────────────────────────────────
            [
                'titulo' => 'Política de Seguridad, Salud en el Trabajo y Ambiente — Confipetrol',
                'codigo' => 'HSEQ-POL-001',
                'descripcion' => 'Compromiso formal de la alta dirección de Confipetrol con la seguridad, salud ocupacional y protección ambiental en todos los contratos vigentes.',
                'tipo_documento' => 'politica',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaHseq->id,
                'created_by' => $admin->id,
                'responsable_id' => $superadmin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2023-01-01',
                'fecha_revision' => '2025-01-01',
                'fecha_vencimiento' => '2026-12-31',
                'version_actual' => 4,
                'tags' => ['política', 'ssta', 'compromisos', 'dirección'],
                'confidencial' => false,
                'visitas' => 789,
            ],
            [
                'titulo' => 'Política de Alcohol, Drogas y Sustancias Psicoactivas',
                'codigo' => 'HSEQ-POL-004',
                'descripcion' => 'Define la posición de Confipetrol frente al consumo de alcohol, drogas y sustancias psicoactivas por parte de los trabajadores durante la jornada laboral.',
                'tipo_documento' => 'politica',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaHseq->id,
                'created_by' => $admin->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2023-03-01',
                'fecha_revision' => '2025-03-01',
                'fecha_vencimiento' => '2026-12-31',
                'version_actual' => 2,
                'tags' => ['política', 'alcohol', 'drogas', 'disciplinario'],
                'confidencial' => false,
                'visitas' => 445,
            ],
            // ── NORMAS / LEGAL ──────────────────────────────────────
            [
                'titulo' => 'Resolución 0312 de 2019 — Estándares Mínimos SG-SST',
                'codigo' => 'LEG-MIN-N-1',
                'descripcion' => 'Resolución del Ministerio del Trabajo que define los estándares mínimos del Sistema de Gestión de Seguridad y Salud en el Trabajo para empleadores.',
                'tipo_documento' => 'norma',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaLegal->id,
                'created_by' => $admin->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2019-02-13',
                'fecha_revision' => '2025-01-01',
                'fecha_vencimiento' => null,
                'version_actual' => 1,
                'tags' => ['norma', 'sg-sst', 'ministerio-trabajo', 'legal'],
                'confidencial' => false,
                'visitas' => 256,
            ],
            [
                'titulo' => 'Decreto 1072 de 2015 — Único Reglamentario Sector Trabajo',
                'codigo' => 'LEG-DEC-N-2',
                'descripcion' => 'Decreto Único Reglamentario del Sector Trabajo que compila normas de trabajo incluyendo el Decreto 1443 de 2014 (SG-SST). Referencia obligatoria para contratos Ecopetrol.',
                'tipo_documento' => 'norma',
                'estado' => 'divulgado',
                'carpeta_id' => $carpetaLegal->id,
                'created_by' => $admin->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CA',
                'fecha_emision' => '2015-05-26',
                'fecha_revision' => '2025-01-01',
                'fecha_vencimiento' => null,
                'version_actual' => 1,
                'tags' => ['norma', 'decreto', 'sg-sst', 'legal'],
                'confidencial' => false,
                'visitas' => 178,
            ],
            // ── EN REVISIÓN / BORRADOR ──────────────────────────────
            [
                'titulo' => 'Procedimiento de Investigación de Incidentes y Accidentes de Trabajo',
                'codigo' => 'HSEQ-SST-P-15',
                'descripcion' => 'Metodología para la investigación sistemática de accidentes, incidentes y cuasi-accidentes según metodología del árbol de causas y FRAT.',
                'tipo_documento' => 'procedimiento',
                'estado' => 'en_revision',
                'carpeta_id' => $subHseqProc->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => null,
                'fase_dicacocu' => 'CU',
                'fecha_emision' => '2025-03-01',
                'fecha_revision' => '2025-06-01',
                'fecha_vencimiento' => null,
                'version_actual' => 1,
                'tags' => ['investigación', 'accidentes', 'incidentes'],
                'confidencial' => false,
                'visitas' => 5,
            ],
            [
                'titulo' => 'Plan de Emergencias — Campo Barrancabermeja 2025',
                'codigo' => 'OPS-EMER-PL-1',
                'descripcion' => 'Plan integral de emergencias para las instalaciones de Confipetrol en Barrancabermeja, incluyendo rutas de evacuación, brigadas y coordinación con Ecopetrol.',
                'tipo_documento' => 'manual',
                'estado' => 'borrador',
                'carpeta_id' => $subOpsEmergencias->id,
                'created_by' => $gestor->id,
                'responsable_id' => $admin->id,
                'aprobador_id' => null,
                'fase_dicacocu' => 'DI',
                'fecha_emision' => null,
                'fecha_revision' => '2025-08-01',
                'fecha_vencimiento' => null,
                'version_actual' => 1,
                'tags' => ['plan', 'emergencias', 'barrancabermeja'],
                'confidencial' => false,
                'visitas' => 0,
            ],
            [
                'titulo' => 'Reglamento Interno de Trabajo — Confipetrol S.A.',
                'codigo' => 'ADM-RIT-001',
                'descripcion' => 'Reglamento interno de trabajo actualizado según reformas laborales 2024, aplicable a todos los contratos y sedes de Confipetrol en Colombia.',
                'tipo_documento' => 'reglamento',
                'estado' => 'aprobado',
                'carpeta_id' => $carpetaLegal->id,
                'created_by' => $admin->id,
                'responsable_id' => $superadmin->id,
                'aprobador_id' => $superadmin->id,
                'fase_dicacocu' => 'CO',
                'fecha_emision' => '2024-08-01',
                'fecha_revision' => '2026-08-01',
                'fecha_vencimiento' => null,
                'version_actual' => 6,
                'tags' => ['reglamento', 'laboral', 'rh'],
                'confidencial' => false,
                'visitas' => 321,
            ],
        ];

        $docModels = [];
        foreach ($documentos as $datos) {
            $doc = Documento::firstOrCreate(
                ['codigo' => $datos['codigo']],
                $datos
            );
            $docModels[] = $doc;
        }

        // ──────────────────────────────────────────────────────────
        // 3. VERSIONES de documentos principales
        // ──────────────────────────────────────────────────────────
        $docPrincipal = Documento::where('codigo', 'HSEQ-GCA1-P-2')->first();
        if ($docPrincipal && $docPrincipal->versiones()->count() === 0) {
            $versiones = [
                [
                    'documento_id' => $docPrincipal->id,
                    'version' => 1,
                    'cambios' => 'Versión inicial del procedimiento de control documental DICACOCU.',
                    'estado' => 'aprobado',
                    'created_by' => $gestor->id,
                    'aprobado_por' => $superadmin->id,
                    'aprobado_at' => Carbon::parse('2023-06-15'),
                    'created_at' => Carbon::parse('2023-06-01'),
                    'updated_at' => Carbon::parse('2023-06-15'),
                ],
                [
                    'documento_id' => $docPrincipal->id,
                    'version' => 2,
                    'cambios' => 'Se actualiza el alcance para incluir contratos nuevos en Perú y Chile. Se modifica flujograma de aprobación.',
                    'estado' => 'aprobado',
                    'created_by' => $gestor->id,
                    'aprobado_por' => $superadmin->id,
                    'aprobado_at' => Carbon::parse('2023-12-20'),
                    'created_at' => Carbon::parse('2023-12-01'),
                    'updated_at' => Carbon::parse('2023-12-20'),
                ],
                [
                    'documento_id' => $docPrincipal->id,
                    'version' => 3,
                    'cambios' => 'Unificación de formatos F-11, F-12 y F-13 en la nueva Matriz Integral F-17. Actualización de roles acorde al nuevo SGD DICACOCU digital. Eliminación de registro físico obligatorio.',
                    'estado' => 'aprobado',
                    'created_by' => $gestor->id,
                    'aprobado_por' => $superadmin->id,
                    'aprobado_at' => Carbon::parse('2024-06-26'),
                    'created_at' => Carbon::parse('2024-06-10'),
                    'updated_at' => Carbon::parse('2024-06-26'),
                ],
            ];

            foreach ($versiones as $v) {
                DocumentoVersion::create($v);
            }
        }

        // Versión del PTS
        $docPts = Documento::where('codigo', 'HSEQ-SST-F-3')->first();
        if ($docPts && $docPts->versiones()->count() === 0) {
            foreach (range(1, 4) as $vNum) {
                DocumentoVersion::create([
                    'documento_id' => $docPts->id,
                    'version' => $vNum,
                    'cambios' => match ($vNum) {
                        1 => 'Versión original del permiso de trabajo.',
                        2 => 'Se agrega sección de trabajos en espacios confinados.',
                        3 => 'Actualización según Resolución 4272 de 2021 MinTrabajo.',
                        4 => 'Incorporación de QR para registro digital desde campo.',
                    },
                    'estado' => $vNum === 4 ? 'aprobado' : 'aprobado',
                    'created_by' => $gestor->id,
                    'aprobado_por' => $admin->id,
                    'aprobado_at' => Carbon::now()->subMonths(5 - $vNum),
                    'created_at' => Carbon::now()->subMonths(6 - $vNum),
                    'updated_at' => Carbon::now()->subMonths(5 - $vNum),
                ]);
            }
        }

        // ──────────────────────────────────────────────────────────
        // 4. LECTURAS DE DOCUMENTOS
        // ──────────────────────────────────────────────────────────
        $docsLectura = Documento::whereIn('estado', ['aprobado', 'divulgado'])->get();
        $usuarios = [$admin, $gestor, $operativo, $superadmin];

        foreach ($docsLectura->take(12) as $doc) {
            foreach ($usuarios as $usr) {
                $confirmado = in_array($doc->estado, ['divulgado']) && rand(0, 100) > 30;
                LecturaDocumento::firstOrCreate(
                    ['documento_id' => $doc->id, 'user_id' => $usr->id],
                    [
                        'leido_at' => Carbon::now()->subDays(rand(1, 60)),
                        'confirmado' => $confirmado,
                        'confirmado_at' => $confirmado ? Carbon::now()->subDays(rand(1, 30)) : null,
                        'progreso_pct' => $confirmado ? 100 : rand(40, 95),
                    ]
                );
            }
        }

        // ──────────────────────────────────────────────────────────
        // 5. CICLOS DICACOCU
        // ──────────────────────────────────────────────────────────
        $ciclosData = [
            [
                'nombre' => 'Ciclo DICACOCU Q1-2025 — Barrancabermeja',
                'codigo' => 'DICA-2025-Q1-BAR',
                'fase' => 'DI',
                'descripcion' => 'Ciclo de revisión y actualización documental trimestral para el contrato de mantenimiento de facilidades en el campo Barrancabermeja.',
                'fecha_inicio' => '2025-01-01',
                'fecha_fin' => '2025-03-31',
                'estado' => 'completado',
                'responsable_id' => $admin->id,
                'progreso' => 100,
                'indicadores' => [
                    'documentos_revisados' => 18,
                    'documentos_aprobados' => 16,
                    'documentos_rechazados' => 2,
                    'compliance_pct' => 88,
                ],
            ],
            [
                'nombre' => 'Ciclo DICACOCU Q2-2025 — Cusiana',
                'codigo' => 'DICA-2025-Q2-CUS',
                'fase' => 'CA',
                'descripcion' => 'Ciclo de calidad documental para el contrato de operación de la estación de compresión Cusiana. Incluye revisión de manuales y procedimientos operativos.',
                'fecha_inicio' => '2025-04-01',
                'fecha_fin' => '2025-06-30',
                'estado' => 'activo',
                'responsable_id' => $gestor->id,
                'progreso' => 62,
                'indicadores' => [
                    'documentos_revisados' => 11,
                    'documentos_aprobados' => 7,
                    'documentos_pendientes' => 8,
                    'compliance_pct' => 62,
                ],
            ],
            [
                'nombre' => 'Ciclo DICACOCU Q3-2025 — Todos los contratos',
                'codigo' => 'DICA-2025-Q3-ALL',
                'fase' => 'CO',
                'descripcion' => 'Ciclo de comunicación y divulgación de documentos aprobados en el trimestre anterior. Incluye capacitación al personal operativo y confirmación de lecturas.',
                'fecha_inicio' => '2025-07-01',
                'fecha_fin' => '2025-09-30',
                'estado' => 'planificado',
                'responsable_id' => $admin->id,
                'progreso' => 0,
                'indicadores' => [
                    'documentos_planificados' => 24,
                    'usuarios_objetivo' => 4,
                    'compliance_pct' => 0,
                ],
            ],
            [
                'nombre' => 'Auditoría Documental HSEQ — Ecopetrol 2025',
                'codigo' => 'DICA-2025-AUD-ECO',
                'fase' => 'CU',
                'descripcion' => 'Ciclo especial de cumplimiento documental preparatorio para la auditoría de Ecopetrol prevista en agosto 2025. Revisión de todos los documentos obligatorios del contrato.',
                'fecha_inicio' => '2025-06-01',
                'fecha_fin' => '2025-08-15',
                'estado' => 'activo',
                'responsable_id' => $superadmin->id,
                'progreso' => 35,
                'indicadores' => [
                    'documentos_obligatorios' => 32,
                    'documentos_conformes' => 11,
                    'no_conformidades' => 3,
                    'compliance_pct' => 35,
                ],
            ],
        ];

        foreach ($ciclosData as $c) {
            $docIds = Documento::inRandomOrder()->limit(rand(3, 8))->pluck('id')->toArray();
            CicloDicacocu::firstOrCreate(
                ['codigo' => $c['codigo']],
                array_merge($c, ['documentos_ids' => $docIds])
            );
        }

        activity()->enableLogging();

        $this->command->info('✓ Datos de demostración cargados correctamente.');
        $this->command->info('  - Carpetas: '.Carpeta::count());
        $this->command->info('  - Documentos: '.Documento::count());
        $this->command->info('  - Versiones: '.DocumentoVersion::count());
        $this->command->info('  - Lecturas: '.LecturaDocumento::count());
        $this->command->info('  - Ciclos DICACOCU: '.CicloDicacocu::count());
    }
}
