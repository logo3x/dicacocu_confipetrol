<?php

namespace Database\Seeders;

use App\Models\CursoCumplimiento;
use App\Models\Documento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CursosEjemploSeeder extends Seeder
{
    public function run(): void
    {
        activity()->disableLogging();

        $gestor = User::where('email', 'gestor@confipetrol.com')->firstOrFail();
        $admin = User::where('email', 'admin@confipetrol.com')->firstOrFail();

        // Obtener IDs de documentos por código
        $docIds = fn (array $codigos) => Documento::whereIn('codigo', $codigos)->pluck('id')->toArray();

        $cursos = [
            // ─────────────────────────────────────────────────────────
            // 1. Inducción al SGD DICACOCU (CA — Calidad)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'Inducción al Sistema de Gestión Documental DICACOCU',
                'descripcion' => 'Curso obligatorio para todo el personal que accede al SGD. Cubre el ciclo DICACOCU, el flujo de documentos y las responsabilidades de cada rol en la gestión documental de los contratos Ecopetrol.',
                'fase_dicacocu' => 'CA',
                'estado' => 'activo',
                'created_by' => $admin->id,
                'documentos_ids' => $docIds(['HSEQ-GCA1-P-2']),
                'nota_aprobacion' => 80,
                'fecha_limite' => Carbon::now()->addDays(45),
                'certificado_activo' => true,
                'preguntas' => [
                    [
                        'pregunta' => '¿Qué significa el acrónimo DICACOCU en el contexto del SGD Confipetrol?',
                        'opcion_a' => 'Distribución, Integración, Capacitación, Operación, Control, Uso',
                        'opcion_b' => 'Disponibilidad, Integridad, Calidad, Acceso, Comunicación, Cumplimiento, Uso',
                        'opcion_c' => 'Documentación, Información, Calidad, Auditoría, Control, Unidad',
                        'opcion_d' => 'Distribución, Identificación, Clasificación, Archivo, Consulta, Uso',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => 'Según el procedimiento HSEQ-GCA1-P-2, ¿cuál es el estado que tiene un documento recién creado antes de ser revisado?',
                        'opcion_a' => 'Aprobado',
                        'opcion_b' => 'Divulgado',
                        'opcion_c' => 'Borrador',
                        'opcion_d' => 'En revisión',
                        'respuesta_correcta' => 'C',
                    ],
                    [
                        'pregunta' => '¿Quién es responsable de aprobar un documento en el SGD DICACOCU?',
                        'opcion_a' => 'El operativo que ejecuta el procedimiento',
                        'opcion_b' => 'El aprobador asignado al documento, con rol admin o super_admin',
                        'opcion_c' => 'Cualquier usuario con acceso al sistema',
                        'opcion_d' => 'El gestor documental únicamente',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Cuál es el flujo correcto de estados de un documento en el SGD?',
                        'opcion_a' => 'Aprobado → Borrador → En revisión → Divulgado',
                        'opcion_b' => 'Borrador → En revisión → Aprobado → Divulgado',
                        'opcion_c' => 'Divulgado → Aprobado → En revisión → Borrador',
                        'opcion_d' => 'En revisión → Borrador → Divulgado → Aprobado',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Qué fase DICACOCU corresponde a la disponibilidad oportuna del documento para el personal que lo necesita?',
                        'opcion_a' => 'CA — Calidad',
                        'opcion_b' => 'CO — Comunicación',
                        'opcion_c' => 'DI — Disponibilidad',
                        'opcion_d' => 'CU — Cumplimiento',
                        'respuesta_correcta' => 'C',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 2. Trabajo seguro en alturas (DI — Disponibilidad)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'Trabajo Seguro en Alturas — Contratos Ecopetrol',
                'descripcion' => 'Curso de cumplimiento legal y contractual para personal operativo que realiza actividades en alturas superiores a 1,5 m en instalaciones Ecopetrol. Basado en la Resolución 4272 de 2021 y el procedimiento interno HSEQ-SST-P-8.',
                'fase_dicacocu' => 'DI',
                'estado' => 'activo',
                'created_by' => $gestor->id,
                'documentos_ids' => $docIds(['HSEQ-SST-P-8', 'HSEQ-SST-F-3']),
                'nota_aprobacion' => 85,
                'fecha_limite' => Carbon::now()->addDays(20),
                'certificado_activo' => true,
                'preguntas' => [
                    [
                        'pregunta' => '¿A partir de qué altura se considera trabajo en alturas según la normatividad colombiana vigente (Resolución 4272/2021)?',
                        'opcion_a' => '0,5 metros',
                        'opcion_b' => '1,0 metros',
                        'opcion_c' => '1,5 metros',
                        'opcion_d' => '2,0 metros',
                        'respuesta_correcta' => 'C',
                    ],
                    [
                        'pregunta' => '¿Cuál de los siguientes documentos debe diligenciarse ANTES de iniciar un trabajo en alturas en instalaciones Ecopetrol?',
                        'opcion_a' => 'Informe de incidente HSEQ-SST-F-3',
                        'opcion_b' => 'Permiso de trabajo en alturas y lista de verificación de EPP',
                        'opcion_c' => 'Registro de entrega de dotación personal',
                        'opcion_d' => 'Acta de reunión del comité HSEQ',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Cuál es el EPP mínimo obligatorio para trabajo en alturas según el procedimiento HSEQ-SST-P-8?',
                        'opcion_a' => 'Casco, gafas y guantes de vaqueta',
                        'opcion_b' => 'Arnés de cuerpo completo, eslinga con absorbedor de impacto y punto de anclaje certificado',
                        'opcion_c' => 'Cinturón de posicionamiento y cuerda de seguridad',
                        'opcion_d' => 'Casco dieléctrico y botas con puntera de acero',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Qué debe hacer el trabajador si durante la ejecución del trabajo en alturas identifica una condición insegura no prevista?',
                        'opcion_a' => 'Continuar el trabajo y reportar al finalizar la jornada',
                        'opcion_b' => 'Detener la actividad, asegurar la zona y notificar inmediatamente al supervisor HSEQ',
                        'opcion_c' => 'Adaptar el equipo de protección y continuar',
                        'opcion_d' => 'Esperar a que otro compañero evalúe la situación',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Con qué frecuencia mínima debe recertificarse el personal autorizado para trabajo en alturas según la normativa Ecopetrol?',
                        'opcion_a' => 'Cada 6 meses',
                        'opcion_b' => 'Cada 2 años',
                        'opcion_c' => 'Cada año',
                        'opcion_d' => 'Solo al ingresar al contrato',
                        'respuesta_correcta' => 'C',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 3. Respuesta a emergencias ambientales (CO — Comunicación)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'Respuesta a Emergencias Ambientales en Campo',
                'descripcion' => 'Entrenamiento para el personal operativo en la identificación y respuesta inicial ante derrames, fugas e incendios en zonas de operación Ecopetrol. Incluye cadena de llamadas, acciones de primera respuesta y diligenciamiento del formato de reporte.',
                'fase_dicacocu' => 'CO',
                'estado' => 'activo',
                'created_by' => $gestor->id,
                'documentos_ids' => $docIds(['HSEQ-MA-P-4', 'OPS-EMER-P-1']),
                'nota_aprobacion' => 80,
                'fecha_limite' => Carbon::now()->addDays(15),
                'certificado_activo' => false,
                'preguntas' => [
                    [
                        'pregunta' => 'Al detectar un derrame de hidrocarburos en campo, ¿cuál es la PRIMERA acción que debe tomar el trabajador según el procedimiento HSEQ-MA-P-4?',
                        'opcion_a' => 'Tomar fotos y enviarlas al supervisor',
                        'opcion_b' => 'Tratar de contener el derrame manualmente sin EPP',
                        'opcion_c' => 'Aislar el área, evitar fuentes de ignición y activar la cadena de llamadas de emergencia',
                        'opcion_d' => 'Esperar instrucciones del área de medioambiente de Ecopetrol',
                        'respuesta_correcta' => 'C',
                    ],
                    [
                        'pregunta' => '¿Cuál es el número de línea de emergencias Ecopetrol que debe conocer todo el personal operativo en campo?',
                        'opcion_a' => '123 — Policía Nacional',
                        'opcion_b' => 'La línea de emergencias definida en el Plan de Contingencia del contrato',
                        'opcion_c' => '132 — Bomberos',
                        'opcion_d' => 'El número personal del coordinador HSEQ',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Dentro de qué tiempo máximo debe reportarse formalmente un incidente ambiental a Ecopetrol según los lineamientos contractuales?',
                        'opcion_a' => '72 horas',
                        'opcion_b' => '48 horas',
                        'opcion_c' => '24 horas — reporte preliminar; 72 horas — reporte completo',
                        'opcion_d' => 'Al cierre del turno de trabajo',
                        'respuesta_correcta' => 'C',
                    ],
                    [
                        'pregunta' => '¿Qué información mínima debe contener el reporte inicial de un derrame (formato HSEQ)?',
                        'opcion_a' => 'Solo el nombre del trabajador que lo detectó',
                        'opcion_b' => 'Fecha, hora, ubicación GPS, tipo y volumen estimado del derrame, acciones tomadas y recursos afectados',
                        'opcion_c' => 'El nombre del responsable y el número de contrato',
                        'opcion_d' => 'Foto del derrame y firma del supervisor',
                        'respuesta_correcta' => 'B',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 4. Mantenimiento preventivo de equipos (CU — Cumplimiento)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'Mantenimiento Preventivo de Equipos Rotativos — Norma API 610',
                'descripcion' => 'Curso técnico para personal de mantenimiento sobre la planificación y ejecución de mantenimiento preventivo en equipos de bombeo centrífugo según la norma API 610 y el procedimiento interno OPS-MANT-P-12.',
                'fase_dicacocu' => 'CU',
                'estado' => 'activo',
                'created_by' => $gestor->id,
                'documentos_ids' => $docIds(['OPS-MANT-P-12', 'OPS-MANT-F-7']),
                'nota_aprobacion' => 75,
                'fecha_limite' => Carbon::now()->addDays(60),
                'certificado_activo' => true,
                'preguntas' => [
                    [
                        'pregunta' => '¿Cuál es el objetivo principal del mantenimiento preventivo según el procedimiento OPS-MANT-P-12?',
                        'opcion_a' => 'Reparar los equipos una vez que fallan para minimizar costos',
                        'opcion_b' => 'Ejecutar intervenciones programadas para prevenir fallas y prolongar la vida útil del equipo',
                        'opcion_c' => 'Reemplazar equipos cada dos años independientemente de su estado',
                        'opcion_d' => 'Reducir el tiempo de paro no programado mediante reparaciones rápidas',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => 'Según la norma API 610, ¿con qué frecuencia mínima debe verificarse el nivel y condición del lubricante en bombas centrífugas en operación continua?',
                        'opcion_a' => 'Mensualmente',
                        'opcion_b' => 'Semanalmente',
                        'opcion_c' => 'Diariamente durante cada ronda de inspección',
                        'opcion_d' => 'Solo en el mantenimiento semestral programado',
                        'respuesta_correcta' => 'C',
                    ],
                    [
                        'pregunta' => '¿Qué documento debe diligenciarse al ejecutar una actividad de mantenimiento preventivo para garantizar la trazabilidad?',
                        'opcion_a' => 'Correo electrónico al supervisor',
                        'opcion_b' => 'Orden de trabajo en el sistema y formato OPS-MANT-F-7 firmado',
                        'opcion_c' => 'Bitácora personal del técnico',
                        'opcion_d' => 'Ninguno, basta con la verificación visual',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Cuál es la condición que obliga a detener la operación de una bomba centrífuga según los criterios API 610?',
                        'opcion_a' => 'Vibración superior al umbral de alarma establecido en la hoja de datos del equipo',
                        'opcion_b' => 'Temperatura ambiente superior a 35 °C',
                        'opcion_c' => 'Cualquier ruido diferente al habitual',
                        'opcion_d' => 'Presencia de polvo en la sala de equipos',
                        'respuesta_correcta' => 'A',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 5. ISO 9001 — Control de documentos y registros (CA)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'ISO 9001:2015 — Control de Documentos y Registros de Calidad',
                'descripcion' => 'Formación en los requisitos de la norma ISO 9001:2015 para la creación, control y conservación de documentos y registros del sistema de gestión de calidad Confipetrol. Orientado a gestores documentales y coordinadores de área.',
                'fase_dicacocu' => 'CA',
                'estado' => 'activo',
                'created_by' => $admin->id,
                'documentos_ids' => $docIds(['CAL-POL-001', 'HSEQ-GCA1-P-2']),
                'nota_aprobacion' => 80,
                'fecha_limite' => Carbon::now()->addDays(90),
                'certificado_activo' => true,
                'preguntas' => [
                    [
                        'pregunta' => '¿Qué cláusula de la norma ISO 9001:2015 establece los requisitos de control de la información documentada?',
                        'opcion_a' => 'Cláusula 6 — Planificación',
                        'opcion_b' => 'Cláusula 7.5 — Información documentada',
                        'opcion_c' => 'Cláusula 8 — Operación',
                        'opcion_d' => 'Cláusula 9 — Evaluación del desempeño',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => 'Según ISO 9001:2015, ¿cuál es la diferencia entre un "documento" y un "registro"?',
                        'opcion_a' => 'No hay diferencia; ambos términos son equivalentes',
                        'opcion_b' => 'Un documento describe cómo hacer algo; un registro evidencia que se hizo',
                        'opcion_c' => 'Los registros son solo los firmados por el cliente',
                        'opcion_d' => 'Los documentos son digitales; los registros son físicos',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Qué información mínima debe contener un documento del sistema de gestión de calidad para considerarse controlado?',
                        'opcion_a' => 'Solo el título y la fecha de emisión',
                        'opcion_b' => 'Título, código, versión, fecha de emisión, aprobador y estado de vigencia',
                        'opcion_c' => 'El nombre de quien lo redactó y la firma del gerente',
                        'opcion_d' => 'Fecha de próxima revisión y número de páginas',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Qué debe hacerse con un documento obsoleto según los requisitos ISO 9001:2015?',
                        'opcion_a' => 'Eliminarlo inmediatamente de todos los medios',
                        'opcion_b' => 'Marcarlo claramente como obsoleto y controlar su retención si se conserva por razones legales',
                        'opcion_c' => 'Dejarlo disponible junto con la versión vigente',
                        'opcion_d' => 'Archivarlo sin ninguna marca especial',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Con qué frecuencia deben revisarse los documentos del sistema de gestión según la política de Confipetrol?',
                        'opcion_a' => 'Cada vez que se presente un no conformidad',
                        'opcion_b' => 'Solo cuando Ecopetrol lo solicite en auditoría',
                        'opcion_c' => 'De acuerdo con el período de revisión establecido en cada documento, como mínimo anual',
                        'opcion_d' => 'Cada cinco años como establece la norma ISO',
                        'respuesta_correcta' => 'C',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 6. Liderazgo visible y cultura de seguridad (DI — Disponibilidad)
            // ─────────────────────────────────────────────────────────
            [
                'titulo' => 'Liderazgo Visible y Cultura de Seguridad Ecopetrol',
                'descripcion' => 'Curso dirigido a supervisores y coordinadores sobre el modelo de liderazgo en HSE de Ecopetrol: observación preventiva de comportamientos, retroalimentación positiva y registro de intervenciones.',
                'fase_dicacocu' => 'DI',
                'estado' => 'borrador',
                'created_by' => $admin->id,
                'documentos_ids' => [],
                'nota_aprobacion' => 70,
                'fecha_limite' => Carbon::now()->addDays(120),
                'certificado_activo' => false,
                'preguntas' => [
                    [
                        'pregunta' => '¿En qué consiste el "liderazgo visible" en el modelo HSE de Ecopetrol?',
                        'opcion_a' => 'Que el líder use el EPP más visible en campo',
                        'opcion_b' => 'La presencia activa de los líderes en el campo con interacciones de seguridad planificadas y registradas',
                        'opcion_c' => 'Publicar los indicadores de accidentalidad en carteleras',
                        'opcion_d' => 'Dictar charlas de seguridad de 5 minutos antes de cada turno',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Cuál es el propósito de una Observación Preventiva de Comportamiento (OPC)?',
                        'opcion_a' => 'Sancionar al trabajador que incumple una norma de seguridad',
                        'opcion_b' => 'Identificar y reforzar comportamientos seguros, y corregir comportamientos de riesgo de forma constructiva',
                        'opcion_c' => 'Elaborar estadísticas de accidentalidad para reportar a Ecopetrol',
                        'opcion_d' => 'Verificar el uso de EPP para efectos disciplinarios',
                        'respuesta_correcta' => 'B',
                    ],
                    [
                        'pregunta' => '¿Cuántas OPC mínimas debe registrar un supervisor al mes según los compromisos del contrato Ecopetrol?',
                        'opcion_a' => 'La cantidad es libre, no hay mínimos',
                        'opcion_b' => 'El mínimo establecido en el Plan de Gestión HSEQ del contrato vigente',
                        'opcion_c' => '1 por semana obligatoriamente',
                        'opcion_d' => '10 por semana sin excepción',
                        'respuesta_correcta' => 'B',
                    ],
                ],
            ],
        ];

        foreach ($cursos as $datos) {
            CursoCumplimiento::firstOrCreate(
                ['titulo' => $datos['titulo']],
                $datos
            );
        }

        activity()->enableLogging();

        $this->command->info('✔ '.count($cursos).' cursos de ejemplo creados.');
    }
}
