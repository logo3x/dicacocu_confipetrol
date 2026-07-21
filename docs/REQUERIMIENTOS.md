# Requerimientos — Disciplina Operativa (Confipetrol)

> Basado en la presentación oficial *"Disciplina Operativa — Lanzamiento Nueva Metodología"* (webinar 03/07/2026, A. Velásquez / Y. Gil, Calidad Confipetrol) y en la transcripción del webinario del 06/07/2026.
>
> **Estado:** RF-1 a RF-8 implementados, incluyendo el formato F-14 confirmado contra el documento oficial real (07/07/2026). Sustituye cualquier interpretación previa del acrónimo "DICACOCU" usada en el código o el README del proyecto (ver [Hallazgo Crítico #1](#hallazgo-crítico-1--colisión-de-significados-de-dicacocu)). Pendiente conocido: confirmar con Calidad Corporativa la fórmula exacta del indicador CO (ver sección 6).

---

## ⚠️ Hallazgo Crítico #1 — Colisión de significados de "DICACOCU"

En este repositorio conviven **tres significados distintos** para variaciones del mismo acrónimo. Antes de escribir una sola línea de código de negocio hay que desambiguar:

| Origen | Significado | Alcance |
|---|---|---|
| `README.md` (proyecto de software) | **DICACOCU** = Diagnóstico, Ingeniería, Construcción, Arranque, Comisionamiento, Operación (6 fases) | Fases del *proyecto de desarrollo del propio sistema* — no es un concepto de negocio de Confipetrol. |
| Comentario en migración `ciclos_dicacocu` / campo `fase_dicacocu` en `Documento` y `CursoCumplimiento` | **DICACOCU** = D-I-C-A-C-O-C-U = Disponibilidad, Integridad, Calidad, Acceso, Comunicación, Operación, Cumplimiento, Uso (8 fases) | Invención ad-hoc, no corresponde a ningún documento oficial de Confipetrol. |
| Presentación oficial de Calidad Confipetrol (este documento) | **DICACOCO** = **DI**sponibilidad + **CA**lidad + **CO**municación + **CU**mplimiento (4 pilares, con fórmula y meta cada uno) | Es el ciclo de medición del programa de **Disciplina Operativa**. Es el único significado válido de negocio. |

**Decisión tomada (06/07/2026):** se eliminó por completo el campo `fase_dicacocu` de `Documento` y `CursoCumplimiento` (modelos, migraciones, formularios, tablas, factories, seeders) y los 3 widgets de dashboard que dependían de él (`ComplianceFasesWidget`, `FasesDicacoCuWidget`, `DocumentosPorFaseChart` — que además estaban rotos: filtraban por letras sueltas `D/I/C/A/O/U` mientras los datos demo ya usaban pares `DI/CA/CO/CU`, por lo que nunca producían resultados). Migración de limpieza: `database/migrations/2026_07_06_155014_drop_fase_dicacocu_column_from_documentos_and_curso_cumplimientos_table.php`.

El significado de negocio real (**DICACOCO = 4 pilares**) se modelará como indicadores calculados (ver RF-5), no como un atributo de documento. Todo lo que sigue en este documento usa esa convención y llama explícitamente "Disciplina Operativa" (DO) al programa completo para no seguir sobrecargando la sigla.

Nota: se encontró que el modelo `CicloDicacocu` ya usa en sus datos demo un campo `fase` con los valores correctos (`DI`, `CA`, `CO`, `CU`) — es un punto de partida potencial para el módulo de indicadores (RF-5), a evaluar cuando se llegue a esa etapa.

---

## ⚠️ Hallazgo Crítico #2 — Colisión de código de documento `HSEQ-GCA1-F-17`

El seeder `database/seeders/DemoDataSeeder.php` usaba el código `HSEQ-GCA1-F-17` para una **"Matriz Integral de Control Documental"** genérica (dato ficticio de demo). La metodología oficial asigna ese mismo código a la **"Matriz Integral de Disciplina Operativa"** (el documento maestro que integra las 4 etapas de DO). Eran conceptos distintos con el mismo código.

**Corregido (06/07/2026):** el dato demo ficticio se renombró a `HSEQ-GCA1-F-16` (código, título, descripción, tags e historial de versión), liberando `F-17` para el futuro módulo real de Matriz Integral de Disciplina Operativa. Pendiente: verificar con el área de Calidad cuál es el código real y definitivo de ese formato antes de implementarlo.

---

## 1. Resumen del dominio de negocio

**Disciplina Operativa (DO)** es la metodología de Confipetrol para asegurar que los procedimientos escritos se ejecuten realmente en campo. Se basa en un ciclo de 4 pilares (**DI**sponibilidad, **CA**lidad, **CO**municación, **CU**mplimiento — acrónimo **DICACOCO**) medidos a través de 4 etapas operativas:

1. **Etapa 1 — Identificación y priorización de actividades**
2. **Etapa 2 — Plan de estandarización y codificación**
3. **Etapa 3 — Comunicación (socialización)**
4. **Etapa 4 — Programa de verificación de DO** (formato de campo "caminar la planta")

Documentos normativos citados:
- `HSEQ-GCA1-P-2` — Procedimiento de Disciplina Operativa (22/06/2026)
- `HSEQ-GCA1-F-17` — Matriz Integral de Disciplina Operativa (22/06/2026)
- `HSEQ-GCA1-F-10` — Listado Maestro de Documentos
- `HSEQ-GCA1-P-1` — Procedimiento para la Gestión Documental
- `HSEQ-GCA1-F-14` — Acompañamiento y Verificación de Actividades

---

## 2. Requerimientos Funcionales

### RF-1 — Gestión de Actividades y Amenazas (Etapa 1)

- RF-1.1 El sistema debe permitir registrar un **inventario de actividades** por contrato/campo, indicando el personal expuesto a cada una.
- RF-1.2 Cada actividad debe tener una **valoración de amenaza** numérica de 0 a 100.
- RF-1.3 La valoración debe clasificar automáticamente la actividad según estos rangos (**nota: la escala es inversa — score alto = amenaza baja**):

  | Score | Prioridad de la actividad |
  |---|---|
  | 80 – 100 | BAJO |
  | 60 – 79 | MEDIO |
  | 0 – 59 | ALTO |

- RF-1.4 La fecha de identificación de la actividad debe quedar registrada.

### RF-2 — Plan de Estandarización y Codificación (Etapa 2)

- RF-2.1 Con base en la prioridad calculada en RF-1.3, el sistema debe calcular automáticamente la **fecha límite de codificación/estandarización**:

  | Prioridad | Plazo máximo |
  |---|---|
  | BAJO | 4 meses |
  | MEDIO | 2 meses |
  | ALTO | 1 mes |

- RF-2.2 Cada procedimiento/instructivo generado debe registrarse en un **Listado Maestro de Documentos** (equivalente a `HSEQ-GCA1-F-10`), con: código asignado, título, versión, responsable de codificación, fecha de codificación, ubicación/acceso.
- RF-2.3 El sistema debe permitir marcar si el documento está: pendiente por codificar / codificado, y alertar si se vence el plazo de RF-2.1.
- RF-2.4 La numeración de versión debe iniciar en `0` (documento nuevo) e incrementar con cada actualización — ya existe como `version_actual` en `Documento`, reutilizable.

### RF-3 — Comunicación / Socialización (Etapa 3)

- RF-3.1 Cada procedimiento estandarizado debe poder **socializarse** al personal expuesto identificado en RF-1.1.
- RF-3.2 El sistema debe calcular la **cobertura de socialización**: personas que confirmaron lectura/entendimiento vs. total de personal expuesto a esa actividad.
- RF-3.3 El **ciclo de re-socialización es de 1 año** — el sistema debe alertar cuando un procedimiento socializado hace ≥ 12 meses necesita repetirse.
- RF-3.4 Reutilizable: `LecturaDocumento` (confirmación de lectura) y `CursoCumplimiento`/`CursoInscripcion` (capacitación con evaluación) ya cubren parte de esto, pero **no tienen relación con "personal expuesto por actividad"** ni ciclo de vencimiento anual — requieren extensión.

### RF-4 — Programa de Verificación de DO / Formato F-14 (Etapa 4)

- RF-4.1 La frecuencia de verificación de cada actividad se calcula según la prioridad de amenaza (RF-1.3):

  | Prioridad | Frecuencia de verificación |
  |---|---|
  | BAJO | Cada 12 meses |
  | MEDIO | Cada 6 meses |
  | ALTO | Cada 3 meses |

- RF-4.2 El sistema debe soportar el registro de un **"Acompañamiento y Verificación de Actividades"** (equivalente a `HSEQ-GCA1-F-14`) con, como mínimo:
  - Campo, área, responsable del área, actividad observada.
  - **Evaluador operativo** (personal técnico con conocimiento de la actividad) y **evaluador HSEQ** (responsable HSEQ o competencia SST) — modalidad **binaria** (ambos roles son obligatorios).
  - Tipo de verificación: "Verificación Cumplimiento DO" o "Inspección Gerencial — Caminar la Planta".
  - Paso a paso de la actividad observada (lista de ítems).
  - Hallazgos y observaciones.
  - Plan de acción acordado en conjunto.
  - Firma/cierre del formato.
- RF-4.3 El sistema debe calcular un **puntaje OPT** (porcentual) resultante del formulario F-14 y clasificarlo automáticamente:

  | Puntaje OPT | Clasificación | Estado / acción |
  |---|---|---|
  | ≥ 95% | EXCELENTE | Aprobado. Sin plan de acción; se promueve reconocimiento. |
  | 80% – 94% | BUENO | Aprobado con observaciones. Requiere acciones preventivas/de mejora documentadas. |
  | 60% – 79% | REGULAR | Condicional. Requiere plan de acción correctivo. |
  | < 60% | DEFICIENTE | No aprobado. Alerta operativa — puede implicar **detención de la actividad**, reentrenamiento de personal y nueva verificación. |

- RF-4.4 El evaluador debe tener la **autoridad para detener la actividad en curso** si identifica un riesgo crítico, independientemente del puntaje final (registrar esta acción explícitamente).
- RF-4.5 El sistema debe permitir consultar el **historial de verificaciones previas** de una actividad antes de una nueva visita (hallazgos repetidos, condiciones inseguras persistentes).

### RF-5 — Indicadores DICACOCO

El sistema debe calcular y exponer 4 indicadores agregados (por contrato y consolidado corporativo), con recálculo automático a medida que avanza la operación de las etapas anteriores:

| Pilar | Indicador | Fórmula | Meta |
|---|---|---|---|
| **DI** — Disponibilidad | Disponibilidad de procedimientos | (Nº procedimientos estandarizados y disponibles / Nº total de actividades) × 100 | 90% |
| **CA** — Calidad | Cobertura de verificación de procedimientos | (Nº procedimientos verificados en el periodo / Nº procedimientos estandarizados y disponibles) × 100 | 90% |
| **CO** — Comunicación | Cobertura de comunicación de los procedimientos | (Nº procedimientos socializados en el periodo / Nº procedimientos estandarizados y disponibles) × 100 | 80% |
| **CU** — Cumplimiento | Desempeño promedio OPT | (Σ puntajes OPT de evaluaciones ejecutadas / Nº total de evaluaciones OPT ejecutadas) | 90% |

> Nota: la fórmula oficial de CO en la presentación reutiliza literalmente el numerador/denominador de CA ("procedimientos verificados"); es probable que en la fuente oficial sea un error de copia y el numerador de CO deba ser "procedimientos socializados/comunicados". **Confirmar con Calidad Corporativa antes de implementar** — se documenta aquí la interpretación más consistente con el resto de la metodología (columna FÓRMULA arriba ya refleja la corrección propuesta; ver PDF original para el texto literal).

- RF-5.1 Los indicadores deben poder verse por contrato individual y consolidados a nivel corporativo.
- RF-5.2 El campo `indicadores` (JSON libre) de `CicloDicacocu` no es suficiente — se requieren columnas/tablas con fórmula reproducible y auditable, no un blob de texto.

### RF-6 — Roles y Responsabilidades

El sistema debe modelar los siguientes roles de negocio (distintos de los roles técnicos actuales `super_admin`/`admin`/`gestor_documental`/`operativo`):

| Rol de negocio | Responsabilidades clave |
|---|---|
| **Calidad Corporativa** | Brindar capacitaciones asociadas a DO; dar seguimiento a la implementación en todos los contratos; consolidar los 4 indicadores a nivel corporativo. |
| **Líder O&M** (coordinador/supervisor de contrato) | Asegurar la implementación de DO en su contrato; participar y promover actividades DO; garantizar disponibilidad de personal y logística. |
| **Responsable HSEQ del contrato** | Apoyar la implementación; mantener la gestión documental disponible/vigente; realizar evaluación de riesgos; actuar como evaluador HSEQ en el formato F-14. |
| **Personal técnico** | Contar con los procedimientos y aplicarlos; participar en las verificaciones de actividades; actuar como evaluador operativo en el formato F-14 cuando sea competente en la actividad. |

- RF-6.1 Debe existir trazabilidad de quién ejecuta cada etapa (responsable de codificación, responsable de socialización, evaluador operativo, evaluador HSEQ) — no solo un `created_by` genérico.

### RF-7 — Gestión Documental (ya parcialmente implementada, a conservar/ajustar)

- RF-7.1 Mantener el ciclo de vida actual de documentos: `borrador → en_revisión → aprobado → divulgado`.
- RF-7.2 Mantener versionamiento (`DocumentoVersion`) y confirmación de lectura (`LecturaDocumento`).
- RF-7.3 **Ajustar**: el `Documento` de DO no es un documento genérico — debe poder vincularse a: una actividad (RF-1.1), un nivel de amenaza heredado (RF-1.3), y sus fechas de codificación/socialización/verificación derivadas de las reglas anteriores.
- RF-7.4 El Listado Maestro (`HSEQ-GCA1-F-10`) debe ser una vista/reporte filtrable por contrato, no solo el listado plano de `documentos`.

### RF-8 — Compromisos y cronograma (gestión de hitos)

- RF-8.1 El sistema debe permitir registrar y dar seguimiento a hitos corporativos con fecha límite y responsable, replicando el patrón de la diapositiva "¿Qué sigue?":
  - Entrega de Listado Maestro actualizado — Responsable HSEQ.
  - Entrega de Etapa 1 (inventario + priorización) — Líder O&M y Responsable HSEQ.
  - Cronograma de capacitaciones por etapa — Calidad Corporativa.
  - Reporte mensual de indicadores — Gestor HSEQ.
- RF-8.2 Estos hitos deben ser configurables (fechas límite parametrizables), no hardcodeados, ya que las fechas de este lanzamiento (17/07/2026, 31/07/2026) son específicas de este ciclo y cambiarán en el futuro.

---

## 3. Requerimientos No Funcionales

- RNF-1 **Auditoría**: toda evaluación OPT, cambio de estado de documento, y valoración de amenaza debe quedar registrada con usuario y timestamp (ya existe infraestructura vía `spatie/laravel-activitylog`, usada en `Documento` — extender a los nuevos modelos).
- RNF-2 **Autorización granular**: los permisos de Spatie deben reflejar las acciones de negocio reales (p. ej. `evaluar actividad hseq`, `evaluar actividad operativo`, `consolidar indicadores corporativos`), no solo CRUD genérico. Seguir el patrón de policies existente (`DocumentoPolicy`).
- RNF-3 **Integridad referencial**: una evaluación F-14 no debe poder crearse sin sus dos evaluadores (modalidad binaria) — validar a nivel de formulario Filament y a nivel de base de datos (constraint / regla de negocio en el modelo).
- RNF-4 **Recalculo consistente de indicadores**: los 4 indicadores DICACOCO deben derivarse siempre de datos transaccionales (actividades, verificaciones, evaluaciones), nunca de un campo editable manualmente, para evitar que se reporten cifras no verificables.
- RNF-5 **Rendimiento**: los cálculos de indicadores agregados (RF-5) deben cachearse (patrón ya usado en el dashboard actual — `Cache::remember`, TTL 60s) dado que se consolidan a nivel corporativo sobre múltiples contratos.
- RNF-6 **Localización**: toda la interfaz de estos nuevos módulos debe estar en español, consistente con el resto de la aplicación.
- RNF-7 **Trazabilidad de plazos**: las fechas límite calculadas automáticamente (RF-2.1, RF-4.1) deben recalcularse si cambia la valoración de amenaza de una actividad, y debe quedar registro del valor anterior.
- RNF-8 **Pruebas**: cada regla de negocio numérica (rangos de amenaza, plazos, clasificación OPT, fórmulas de indicadores) debe cubrirse con Pest feature/unit tests — son reglas con límites exactos (p. ej. 59 vs 60, 79 vs 80) propensas a errores de "off-by-one".
- RNF-9 **No usar el acrónimo "DICACOCU/DICACOCO" en nuevo código sin desambiguar** — usar nombres de clase/tabla explícitos (`Actividad`, `ValoracionAmenaza`, `AcompanamientoVerificacion`, `IndicadorDisciplinaOperativa`) en lugar de siglas, dado el historial de colisión de significados documentado arriba.

---

## 4. Gap Analysis — Implementado vs. Requerido

| # | Requerido (negocio) | Implementado actualmente | Acción necesaria |
|---|---|---|---|
| 1 | DICACOCO = 4 pilares (DI/CA/CO/CU) de Disciplina Operativa | ✅ **Resuelto.** `fase_dicacocu` (6/8 fases inventadas) eliminado del código por completo | Modelar los 4 pilares como indicadores calculados cuando se llegue a RF-5 |
| 2 | Código `HSEQ-GCA1-F-17` = Matriz Integral de Disciplina Operativa | ✅ **Resuelto.** Dato demo ficticio renombrado a `F-16`, código `F-17` liberado | Confirmar código real con Calidad antes de usarlo en el módulo de Matriz Integral |
| 3 | Actividad con inventario + personal expuesto (Etapa 1) | ✅ **Implementado.** Modelo `Actividad`, migración `actividades`, Resource Filament (`app/Filament/Resources/Actividades/`) | Ninguna por ahora; extender con relación a personal expuesto nominal si se requiere trazabilidad individual |
| 4 | Valoración de amenaza 0–100 con rangos 80-100/60-79/0-59 → bajo/medio/alto | ✅ **Implementado.** Enum `App\Enums\PrioridadAmenaza` + `ActividadObserver` recalcula en `creating`/`updating`. Cubierto por 8 tests de rangos límite exactos en `tests/Feature/ActividadTest.php` | Ninguna |
| 5 | Plazos automáticos Etapa 2 (4/2/1 meses) y Etapa 4 (12/6/3 meses) | ✅ **Implementado** en `ActividadObserver::recalcularPrioridadYPlazos()`. **Simplificación conocida:** `fecha_limite_verificacion` se calcula desde `fecha_identificacion`, no desde la fecha real de estandarización (que sería lo correcto una vez el documento esté codificado) | Revisar cuando se implemente el vínculo formal Actividad↔Documento estandarizado (recalcular verificación desde la fecha de codificación real) |
| 6 | Listado Maestro de Documentos (F-10) | `Actividad` tiene FK opcional `documento_id` hacia `Documento` (vínculo básico) | Construir vista/reporte F-10 filtrable por contrato sobre esta relación |
| 7 | Socialización con cobertura de personal expuesto + ciclo anual | ✅ **Implementado.** Tabla puente `actividad_personal_expuesto` (una fila = una persona expuesta a una actividad, con su propio ciclo de socialización). Enum `EstadoSocializacion` (pendiente/vigente/vencida derivado de fechas). Método `Actividad::coberturaSocializacion()` calcula % de personal con socialización vigente. Acción "Socializar ahora" en RelationManager del panel Filament. 13 tests (9 modelo + 2 integración Resource + 2 en gap analysis previo) | El campo `Actividad.personal_expuesto` (contador manual) queda como dato de referencia rápida; considerar sincronizarlo automáticamente con `personalExpuestoNominal()->count()` o deprecarlo a favor del conteo real |
| 8 | Formato F-14 real (HSEQ-GCA1-F-14 v0, 30-06-2021): checklist de 12 preguntas con puntaje OPT calculado, Parte 2 (Inspección Gerencial + 12 Reglas que Salvan Vidas) | ✅ **Implementado y alineado al formato oficial (confirmado 07/07/2026).** Modelo `AcompanamientoVerificacion` con las 11 preguntas SI/NO (6.37% c/u) + pregunta 12 de coincidencia de pasos (30%), cálculo automático de `puntaje_opt_calculado` y `clasificacion_opt` vía Observer. Modelos `InspeccionGerencial` + `InspeccionGerencialRegla` (12 reglas) + `InspeccionGerencialAccion` para la Parte 2. Enums `AnalisisActividad`, `ReglaSalvaVidas`, `CumpleRegla`. Resource Filament con 2 RelationManagers editables desde la página de detalle. 30+ tests | Ninguna — formato confirmado. Ver nota de implementación en sección 5 |
| 9 | Autoridad para detener actividad por riesgo crítico | ✅ **Implementado.** Campo `actividad_detenida` + `motivo_detencion` en `AcompanamientoVerificacion`, independiente del puntaje OPT (extensión razonable, no contradicha por el formato real) | Ninguna por ahora; evaluar si debe disparar una notificación/alerta automática (RNF futuro) |
| 10 | 4 indicadores DICACOCO con fórmulas y metas | ✅ **Implementado.** `IndicadoresDicacocoService::calcular()` — cálculo en tiempo real (sin persistencia, siempre fresco), filtrable por contrato y periodo. DTO `App\Support\IndicadoresDicacoco` con las 4 metas oficiales y helpers `cumpleX()`. Expuesto en `IndicadoresDicacocoWidget` (dashboard, cacheado 60s). `CicloDicacocu` se dejó intacto (sigue siendo para "ciclos de trabajo por periodo" con snapshots manuales, un concepto distinto) | Confirmar con Calidad la fórmula exacta de CO (ver nota más abajo); evaluar si se necesita historial mensual además del valor en tiempo real |
| 11 | Roles: Calidad Corporativa, Líder O&M, Responsable HSEQ, Personal técnico | ✅ **Implementado.** 4 roles nuevos (`calidad_corporativa`, `lider_om`, `responsable_hseq`, `personal_tecnico`) añadidos junto a los roles técnicos existentes (no se renombraron, un usuario combina ambos tipos). `ActividadPolicy` creada y registrada. Usuarios demo reasignados según su cargo real | Ninguna por ahora; revisar si `DocumentoPolicy` debe empezar a considerar `responsable_hseq` para aprobar documentos de DO específicamente |
| 12 | Hitos/compromisos con fecha límite y responsable | ✅ **Implementado.** Modelo `Compromiso` (nombre, descripción, contrato opcional, fecha límite, responsable, rol esperado). Enum `EstadoCompromiso` (pendiente/cumplido/vencido). Resource Filament en `admin/compromisos` con acción "Marcar cumplido". `CompromisoPolicy` (gestión general vs. responsable asignado). `CompromisosDoSeeder` siembra los 4 compromisos reales del webinario (17/07 y 31/07/2026) asignados a los usuarios demo según su rol real | Ninguna por ahora |
| 13 | Ciclo de vida documental (borrador→aprobado→divulgado), versionamiento, confirmación de lectura | ✅ Implementado en `Documento`, `DocumentoVersion`, `LecturaDocumento` | Conservar; vincular a `Actividad` vía `documento_id` cuando se codifique un procedimiento |
| 14 | Auditoría de cambios | ✅ Implementado con `spatie/laravel-activitylog` en `Documento` | Extender a `Actividad` y a los nuevos modelos de DO (F-14, indicadores) |
| 15 | `CicloDicacocu` como posible entidad "programa de DO por contrato" | Existe pero sin Filament Resource, campos genéricos (`fase` string libre, `progreso` manual) | Evaluar si se reutiliza para los indicadores DICACOCO (RF-5) dado que ya usa la nomenclatura correcta, o si se reemplaza por un modelo nuevo más preciso |

---

## 5. Nota de implementación — Formato F-14 (confirmado contra el documento real)

El 07/07/2026 se obtuvo el formato oficial `HSEQ-GCA1-F-14` v0 (30-06-2021, "Acompañamiento y Verificación de Actividades"). Esto reemplazó la versión preliminar construida solo con la descripción textual de la presentación, que tenía **tres diferencias sustanciales**:

1. **El puntaje OPT no se ingresa manualmente — se calcula desde un checklist de 12 preguntas**, tal como aparece en el formato:
   - Preguntas 1–11 (SI/NO): cada una vale 6.37% si la respuesta es "Sí" (11 × 6.37% ≈ 70% del total). Cubren: procedimiento disponible, uso de EPP, identificación de peligros (AST/APR/IPERC), herramientas, orden y aseo, controles, procedimiento actualizado, procedimiento comprensible, procedimiento divulgado, personal capacitado/certificado, personal mostró habilidad.
   - Pregunta 12 (30% del total): compara "pasos según procedimiento" vs. "pasos en la observación ejecutada" — si el conteo coincide, suma 30%; si no, suma 0%.
   - Implementado en `AcompanamientoVerificacion::calcularPuntajeOpt()`, ejecutado automáticamente por `AcompanamientoVerificacionObserver` en cada guardado. Los rangos de clasificación (`ClasificacionOpt`) ya coincidían exactamente con el formato real (≥95 Excelente, 80-94 Bueno, 60-79 Regular, <60 Deficiente) y no requirieron cambio.
2. **Los roles no son "evaluador operativo" + "evaluador HSEQ" binarios estrictos** — el formato real pide "Nombre completo" (observador, quien firma al final) + "Nombre completo de Acompañante" (opcional). Se renombraron los campos `evaluador_operativo_id`/`evaluador_hseq_id` a `observador_id` (obligatorio) / `acompanante_id` (opcional, debe ser persona distinta al observador si se indica).
3. **Existe una Parte 2 completa que no estaba modelada**: "Inspección Gerencial — Caminar la Planta" (aplica solo cuando se marca ese tipo de verificación), con: aplicación de las 12 Reglas que Salvan Vidas (cumple Sí/No/N.A. por regla), hallazgos positivos y desvíos/oportunidades de mejora (dos campos de texto separados), y una tabla de acciones definidas y acordadas (acción + responsable + fecha de cierre). Implementado con los modelos `InspeccionGerencial` (1:1 con el acompañamiento), `InspeccionGerencialRegla` (12 filas, autogeneradas) e `InspeccionGerencialAccion`.

**Detalle técnico relevante:** inicialmente se modeló la Parte 2 con `HasManyThrough` (Actividad → InspeccionGerencial → Regla/Acción), pero Eloquent no soporta `create()`/`update()` fiable en relaciones `HasManyThrough` (no hay FK directa al padre inmediato). Se corrigió denormalizando `acompanamiento_verificacion_id` directamente en `inspeccion_gerencial_reglas` e `inspeccion_gerencial_acciones`, con un Observer que la puebla automáticamente, permitiendo un `HasMany` real editable desde el panel. Además, Filament v5 oculta por defecto las acciones de mutación en RelationManagers dentro de páginas "Ver" (`isReadOnly()` por defecto) — se sobrescribió explícitamente en ambos RelationManagers de la Parte 2, ya que su flujo de uso natural es diligenciarlos desde la misma página de detalle del acompañamiento.

Fuente: `14. Formato Acompañamiento y Verificación de Actividades HSEQ-GCA1-F-14 V0.pdf`.

## 6. Nota de implementación — Indicadores DICACOCO

`IndicadoresDicacocoService::calcular()` (06/07/2026) implementa las 4 fórmulas de RF-5 así:

- **DI (Disponibilidad):** actividades con `documento_id` no nulo / total de actividades del contrato.
- **CA (Calidad):** de las actividades estandarizadas, cuántas tienen al menos un `AcompanamientoVerificacion` con `fecha_ejecucion` dentro del periodo consultado.
- **CO (Comunicación):** de las actividades estandarizadas, cuántas tienen al menos una persona con `fecha_socializacion` dentro del periodo (vía `ActividadPersonalExpuesto`). **Nota:** la presentación oficial reutiliza literalmente el numerador/denominador de CA para CO en el texto ("procedimientos verificados"), lo cual parece un error de copia — se implementó con la interpretación más consistente con el resto de la metodología (procedimientos *socializados*, no verificados). **Confirmar con Calidad Corporativa.**
- **CU (Cumplimiento):** promedio simple de `puntaje_opt` de todas las evaluaciones F-14 ejecutadas (con puntaje no nulo) en el periodo, sin filtrar por actividad estandarizada.

El servicio no persiste nada — cada llamada recalcula desde las tablas transaccionales (`Actividad`, `AcompanamientoVerificacion`, `ActividadPersonalExpuesto`), cumpliendo RNF-4. El widget del dashboard cachea el resultado 60s (RNF-5).

## 7. Próximos pasos sugeridos

1. ~~**Decisión de negocio**: confirmar significado único a usar para "DICACOCU" en el código~~ ✅ Hecho — `fase_dicacocu` eliminado, DICACOCO se modelará como indicadores calculados.
2. ~~**Corregir colisión de código F-17**~~ ✅ Hecho — dato demo renombrado a F-16.
3. ~~**Modelo `Actividad` con valoración de amenaza, prioridad y plazos automáticos (Etapa 1 y 2)**~~ ✅ Hecho — modelo, Enum `PrioridadAmenaza`, `ActividadObserver`, Resource Filament, 22 tests (16 de modelo + 6 de Resource).
4. ~~**Roles y permisos de negocio (RF-6)**~~ ✅ Hecho — 4 roles nuevos (`calidad_corporativa`, `lider_om`, `responsable_hseq`, `personal_tecnico`) con permisos específicos de DO (valorar amenaza, evaluar F-14 como operativo/HSEQ, detener actividad, consolidar indicadores, gestionar capacitaciones/compromisos), `ActividadPolicy` registrada, usuarios demo reasignados, 9 tests de policy/roles.
5. ~~**Modelo `AcompanamientoVerificacion` (F-14) con checklist y puntaje OPT calculado (RF-4)**~~ ✅ Hecho y **confirmado contra el formato oficial real** (07/07/2026) — checklist de 12 preguntas, cálculo automático de OPT, Parte 2 completa (Inspección Gerencial + 12 Reglas que Salvan Vidas), Resources y Policies para los 5 modelos involucrados, 30+ tests. Ver sección 5.
6. ~~**Socialización con cobertura de personal expuesto nominal y ciclo anual (RF-3)**~~ ✅ Hecho — tabla `actividad_personal_expuesto`, Enum `EstadoSocializacion`, `Actividad::coberturaSocializacion()`, RelationManager con acción "Socializar ahora" protegida por el permiso `socializar procedimiento`, 13 tests nuevos.
7. ~~**4 indicadores DICACOCO con fórmulas reales (RF-5)**~~ ✅ Hecho — `IndicadoresDicacocoService`, DTO `IndicadoresDicacoco`, widget de dashboard, 11 tests (10 servicio + 1 widget). Ver nota de implementación en sección 6 sobre la fórmula de CO a confirmar.
8. ~~**Modelo de hitos/compromisos configurables (RF-8)**~~ ✅ Hecho — modelo `Compromiso`, Enum `EstadoCompromiso`, Resource Filament, `CompromisoPolicy`, `CompromisosDoSeeder` con los 4 compromisos reales del webinario, 16 tests (5 modelo + 5 policy + 6 Resource).

**Con esto, los 8 requerimientos funcionales (RF-1 a RF-8) definidos en la sección 2 tienen una primera implementación funcional**, con 123 tests pasando. Quedan como trabajo futuro: confirmar el formato F-14 real (sección 5), confirmar la fórmula de CO con Calidad Corporativa (sección 6), y los refinamientos menores señalados en la tabla de Gap Analysis (sección 4).

Este documento debe evolucionar como fuente de verdad; actualizar el Gap Analysis (sección 4) a medida que se implemente cada punto.
