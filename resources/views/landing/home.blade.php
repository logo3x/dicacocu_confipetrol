<x-layouts.landing title="Inicio" description="Sistema de Gestión Documental DICACOCU — Confipetrol">

    {{-- ============================
         HERO
    ============================= --}}
    <section class="cp-hero" id="inicio">
        {{-- Elementos decorativos de fondo --}}
        <div class="cp-hero__bg-glow cp-hero__bg-glow--orange"></div>
        <div class="cp-hero__bg-glow cp-hero__bg-glow--blue"></div>
        <div class="cp-hero__grid"></div>

        <div class="cp-container cp-hero__inner">
            <div class="cp-hero__content">
                <p class="cp-eyebrow cp-anim-1">
                    <span class="cp-eyebrow__dot"></span>
                    Sistema de Gestión Documental
                </p>
                <h1 class="cp-hero__title cp-anim-2">
                    DICACOCU<br>
                    <span class="cp-hero__title-accent">en un solo sistema</span>
                </h1>
                <p class="cp-hero__desc cp-anim-3">
                    Gestión documental privada, trazable y alineada al ciclo operacional
                    de Ecopetrol S.A. Disponibilidad · Calidad · Comunicación · Cumplimiento.
                </p>
                <div class="cp-hero__cta cp-anim-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg cp-btn--glow">
                        <i class="fa-solid fa-right-to-bracket"></i> Acceder al sistema
                    </a>
                    <a href="{{ route('landing.dicacocu') }}" class="cp-btn cp-btn--white-outline cp-btn--lg">
                        <i class="fa-solid fa-circle-info"></i> Conocer DICACOCU
                    </a>
                </div>

                {{-- Pillars row --}}
                <div class="cp-hero__pillars cp-anim-5">
                    @foreach([['DI','Disponibilidad'],['CA','Calidad'],['CO','Comunicación'],['CU','Cumplimiento']] as [$l,$n])
                    <div class="cp-pillar">
                        <span class="cp-pillar__letters">{{ $l }}</span>
                        <span class="cp-pillar__name">{{ $n }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Visual con stat cards --}}
            <div class="cp-hero__visual cp-anim-6">
                <div class="cp-hero__card-stack">
                    <div class="cp-stat-card cp-float-1">
                        <div class="cp-stat-card__icon"><i class="fa-solid fa-file-circle-check"></i></div>
                        <div class="cp-stat-card__body">
                            <span class="cp-stat-card__value">+2.400</span>
                            <span class="cp-stat-card__label">Documentos gestionados</span>
                        </div>
                    </div>
                    <div class="cp-stat-card cp-stat-card--mid cp-float-2">
                        <div class="cp-stat-card__icon"><i class="fa-solid fa-users-gear"></i></div>
                        <div class="cp-stat-card__body">
                            <span class="cp-stat-card__value">4</span>
                            <span class="cp-stat-card__label">Pilares DICACOCU</span>
                        </div>
                    </div>
                    <div class="cp-stat-card cp-float-3">
                        <div class="cp-stat-card__icon"><i class="fa-solid fa-shield-check"></i></div>
                        <div class="cp-stat-card__body">
                            <span class="cp-stat-card__value">100%</span>
                            <span class="cp-stat-card__label">Trazabilidad documental</span>
                        </div>
                    </div>

                    {{-- Etiqueta "EN LÍNEA" --}}
                    <div class="cp-hero__badge">
                        <span class="cp-hero__badge-dot"></span> Sistema en línea
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="cp-scroll-hint cp-anim-5" aria-hidden="true">
            <span>Desplázate</span>
            <div class="cp-scroll-hint__arrow">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
    </section>

    {{-- ============================
         FASES DICACOCU
    ============================= --}}
    <section class="cp-section cp-section--white" id="fases">
        <div class="cp-container">
            <div class="cp-section__header cp-reveal-header">
                <p class="cp-eyebrow">El ciclo operacional</p>
                <h2>Las 8 dimensiones del ciclo DICACOCU</h2>
                <div class="cp-accent-rule"></div>
                <p class="cp-section__lead">
                    Cada letra del acrónimo representa un eje de gestión que garantiza la
                    excelencia operacional en los contratos Ecopetrol administrados por Confipetrol.
                </p>
            </div>
            <div class="cp-phases">
                @foreach([
                    ['D', 'Disponibilidad',  'fa-power-off',        'Garantizar la disponibilidad de activos e información crítica en todo momento.'],
                    ['I', 'Integridad',      'fa-shield-halved',    'Asegurar la integridad y exactitud de los documentos operacionales.'],
                    ['C', 'Calidad',         'fa-circle-check',     'Mantener estándares de calidad en cada documento generado y divulgado.'],
                    ['A', 'Acceso',          'fa-key',              'Control de acceso por roles y responsabilidades claramente definidas.'],
                    ['C', 'Comunicación',    'fa-bullhorn',         'Divulgación efectiva a los equipos responsables de su aplicación.'],
                    ['O', 'Operación',       'fa-gears',            'Aplicación correcta de los procedimientos en campo por el personal operativo.'],
                    ['C', 'Cumplimiento',    'fa-clipboard-check',  'Verificación del cumplimiento de normas y estándares definidos por Ecopetrol.'],
                    ['U', 'Uso',             'fa-chart-line',       'Seguimiento del uso efectivo del documento en las operaciones diarias.'],
                ] as $i => [$letra, $nombre, $icono, $desc])
                <div class="cp-phase-card cp-reveal" style="--reveal-delay: {{ $i * 0.07 }}s">
                    <div class="cp-phase-card__accent"></div>
                    <div class="cp-phase-card__letter">{{ $letra }}</div>
                    <div class="cp-phase-card__icon-wrap">
                        <div class="cp-phase-card__icon"><i class="fa-solid {{ $icono }}"></i></div>
                    </div>
                    <h3 class="cp-phase-card__name">{{ $nombre }}</h3>
                    <p class="cp-phase-card__desc">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================
         CARACTERÍSTICAS DEL SGD
    ============================= --}}
    <section class="cp-section" id="sistema">
        <div class="cp-container">
            <div class="cp-section__header cp-reveal-header">
                <p class="cp-eyebrow">El sistema</p>
                <h2>Funcionalidades del SGD DICACOCU</h2>
                <div class="cp-accent-rule"></div>
            </div>
            <div class="cp-features">
                @foreach([
                    ['fa-folder-open',      'Gestión Documental',         'Captura, clasificación y versionado de documentos por carpetas y fases DICACOCU.'],
                    ['fa-code-branch',      'Control de versiones',        'Historial completo con estados: Borrador → Revisión → Aprobado → Divulgado.'],
                    ['fa-magnifying-glass', 'Búsqueda inteligente',        'Búsqueda de texto completo sobre el contenido de documentos con índice FULLTEXT.'],
                    ['fa-lock',             'Control de acceso por roles',  'Super Admin, Admin, Gestor Documental y Operativo con permisos granulares.'],
                    ['fa-chart-bar',        'Reportes y trazabilidad',     'Reportes de cumplimiento, auditoría de actividad y dashboard por fase DICACOCU.'],
                    ['fa-robot',            'Asistente IA',                'Asistente que responde preguntas sobre el contenido de los documentos del sistema.'],
                ] as $i => [$icono, $titulo, $desc])
                <div class="cp-feature-card cp-reveal" style="--reveal-delay: {{ $i * 0.08 }}s">
                    <div class="cp-feature-card__icon-wrap">
                        <div class="cp-feature-card__icon">
                            <i class="fa-solid {{ $icono }}"></i>
                        </div>
                    </div>
                    <h3>{{ $titulo }}</h3>
                    <p>{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================
         STATS BAND
    ============================= --}}
    <section class="cp-stats-band" id="cifras">
        <div class="cp-container cp-stats-band__inner">
            @foreach([['+20','Años de experiencia Confipetrol'],['6','Países en Latinoamérica'],['8','Dimensiones ciclo DICACOCU'],['100%','Trazabilidad garantizada']] as [$v, $l])
            <div class="cp-stat cp-reveal-stat">
                <div class="cp-stat__value" data-count="{{ $v }}">{{ $v }}</div>
                <div class="cp-stat__label">{{ $l }}</div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ============================
         CTA ACCESO
    ============================= --}}
    <section class="cp-section" id="acceso">
        <div class="cp-container">
            <div class="cp-cta-box cp-reveal-block">
                <div class="cp-cta-box__glow cp-cta-box__glow--main"></div>
                <div class="cp-cta-box__glow cp-cta-box__glow--secondary"></div>
                <div class="cp-cta-box__content">
                    <p class="cp-eyebrow" style="color: var(--cp-orange-400);">Acceso al sistema</p>
                    <h2 style="color: #fff; margin-bottom: 0.75rem;">¿Tienes cuenta en el SGD DICACOCU?</h2>
                    <p style="color: rgba(255,255,255,0.82); font-size: 1.0625rem; line-height: 1.65; max-width: 520px;">
                        Accede con tus credenciales corporativas para consultar, gestionar y hacer
                        seguimiento de los documentos del ciclo operacional.
                    </p>
                </div>
                <div class="cp-cta-box__action">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg cp-btn--glow">
                        <i class="fa-solid fa-right-to-bracket"></i> Ingresar al sistema
                    </a>
                    <a href="{{ route('landing.contacto') }}" class="cp-btn cp-btn--white-outline cp-btn--md" style="margin-top: 0.75rem;">
                        Solicitar acceso
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-layouts.landing>

<style>
/* ================================================================
   HERO
================================================================ */
.cp-hero {
    background: linear-gradient(125deg, #050F2A 0%, #0A1F44 40%, #0050A0 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
    padding-block: 6rem 5rem;
    min-height: 92vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Fondos decorativos */
.cp-hero__bg-glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.cp-hero__bg-glow--orange {
    top: -25%;
    right: -10%;
    width: 55vw;
    height: 55vw;
    background: radial-gradient(circle, rgba(232,135,26,0.18) 0%, transparent 60%);
    animation: glowDrift 12s ease-in-out infinite alternate;
}
.cp-hero__bg-glow--blue {
    bottom: -20%;
    left: -8%;
    width: 40vw;
    height: 40vw;
    background: radial-gradient(circle, rgba(0,80,160,0.45) 0%, transparent 65%);
    animation: glowDrift 10s 2s ease-in-out infinite alternate-reverse;
}
.cp-hero__grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 56px 56px;
    mask-image: linear-gradient(to bottom, transparent, rgba(0,0,0,0.6) 20%, rgba(0,0,0,0.6) 80%, transparent);
}

/* Layout interior */
.cp-hero__inner {
    display: grid;
    grid-template-columns: 1fr 0.85fr;
    gap: 4rem;
    align-items: center;
    position: relative;
}

/* Eyebrow con punto animado */
.cp-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--cp-orange-400);
}
.cp-eyebrow__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--cp-orange-400);
    animation: pulseDot 2s ease-in-out infinite;
    flex-shrink: 0;
}

.cp-hero__title {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2.75rem, 5.5vw, 4rem);
    line-height: 1.02;
    letter-spacing: -0.025em;
    color: #fff;
    margin: 0.625rem 0 1.25rem;
}
.cp-hero__title-accent {
    background: linear-gradient(90deg, var(--cp-orange-400), #F5A940);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cp-hero__desc {
    font-size: 1.125rem;
    line-height: 1.7;
    color: rgba(255,255,255,0.8);
    max-width: 460px;
    margin-bottom: 2.25rem;
}

.cp-hero__cta { display: flex; gap: 1rem; flex-wrap: wrap; }

/* Pillars en el hero */
.cp-hero__pillars {
    display: flex;
    gap: 1.5rem;
    margin-top: 2.75rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.cp-pillar {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.cp-pillar__letters {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.375rem;
    color: var(--cp-orange-400);
    line-height: 1;
}
.cp-pillar__name {
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255,255,255,0.5);
}

/* Visual — stat cards */
.cp-hero__visual { position: relative; }
.cp-hero__card-stack {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
}

.cp-stat-card {
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: var(--radius-lg);
    padding: 1.125rem 1.375rem;
    display: flex;
    align-items: center;
    gap: 1.125rem;
    transition: transform 0.25s ease, border-color 0.25s ease;
}
.cp-stat-card:hover {
    border-color: rgba(232,135,26,0.4);
    transform: translateX(4px);
}
.cp-stat-card__icon {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    background: rgba(232,135,26,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cp-stat-card__icon i { font-size: 1.125rem; color: var(--cp-orange-400); }
.cp-stat-card__body { display: flex; flex-direction: column; }
.cp-stat-card__value {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.5rem;
    color: #fff;
    line-height: 1;
}
.cp-stat-card__label { font-size: 0.8125rem; color: rgba(255,255,255,0.65); margin-top: 0.25rem; }

.cp-stat-card--mid { margin-inline: 1.25rem; }

/* Floating animations with custom offset */
.cp-float-1 { animation: cardFloat 4.5s ease-in-out infinite; }
.cp-float-2 { animation: cardFloat 4.5s 1.5s ease-in-out infinite; }
.cp-float-3 { animation: cardFloat 4.5s 3s ease-in-out infinite; }

/* Badge EN LÍNEA */
.cp-hero__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(22,197,94,0.15);
    border: 1px solid rgba(22,197,94,0.3);
    border-radius: 100px;
    padding: 0.375rem 0.875rem;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: var(--font-display);
    color: rgba(255,255,255,0.85);
    margin-top: 0.5rem;
    margin-left: 1.25rem;
    width: fit-content;
}
.cp-hero__badge-dot {
    width: 7px;
    height: 7px;
    background: #16c55e;
    border-radius: 50%;
    animation: pulseDot 1.8s ease-in-out infinite;
}

/* Scroll hint */
.cp-scroll-hint {
    position: absolute;
    bottom: 2.5rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.4);
    font-size: 0.6875rem;
    font-family: var(--font-display);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.cp-scroll-hint__arrow {
    animation: bounceDown 1.6s ease-in-out infinite;
    font-size: 0.875rem;
}

/* Botón glow */
.cp-btn--glow { box-shadow: 0 4px 20px rgba(232,135,26,0.4); }
.cp-btn--glow:hover { box-shadow: 0 6px 28px rgba(232,135,26,0.55); }

/* ================================================================
   SECTIONS
================================================================ */
.cp-section { padding-block: 5.5rem; background: var(--surface-page); }
.cp-section--white { background: var(--surface-card); }

.cp-section__header { text-align: center; margin-bottom: 3rem; }
.cp-section__header h2 {
    font-size: clamp(1.875rem, 3vw, 2.375rem);
    letter-spacing: -0.025em;
    margin-top: 0.5rem;
    margin-bottom: 0;
}
.cp-section__lead {
    max-width: 600px;
    margin: 1.25rem auto 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
}

.cp-accent-rule {
    width: 3rem;
    height: 3px;
    background: linear-gradient(90deg, var(--color-accent), var(--cp-orange-400));
    border-radius: 2px;
    margin: 1rem auto 0;
}

/* ================================================================
   PHASE CARDS
================================================================ */
.cp-phases {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}
.cp-phase-card {
    background: var(--surface-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    padding: 1.625rem;
    box-shadow: var(--shadow-xs);
    transition: transform 0.3s var(--ease-out), box-shadow 0.3s ease, border-color 0.3s ease;
    position: relative;
    overflow: hidden;
}
.cp-phase-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(0,80,160,0.2);
}
.cp-phase-card__accent {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--color-accent), var(--cp-orange-400));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.35s var(--ease-out);
}
.cp-phase-card:hover .cp-phase-card__accent { transform: scaleX(1); }

.cp-phase-card__letter {
    font-family: var(--font-display);
    font-weight: 900;
    font-size: 3rem;
    color: var(--color-primary);
    line-height: 1;
    opacity: 0.07;
    position: absolute;
    top: 0.625rem; right: 0.875rem;
    transition: opacity 0.3s ease;
}
.cp-phase-card:hover .cp-phase-card__letter { opacity: 0.12; }

.cp-phase-card__icon-wrap { margin-bottom: 1rem; }
.cp-phase-card__icon {
    width: 44px; height: 44px;
    border-radius: var(--radius-md);
    background: rgba(0,80,160,0.08);
    color: var(--color-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: transform 0.25s ease, background 0.25s ease;
}
.cp-phase-card:hover .cp-phase-card__icon {
    transform: scale(1.15) rotate(-4deg);
    background: rgba(0,80,160,0.15);
}

.cp-phase-card__name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-strong);
    margin-bottom: 0.5rem;
}
.cp-phase-card__desc {
    font-size: 0.875rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0;
}

/* ================================================================
   FEATURE CARDS
================================================================ */
.cp-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
.cp-feature-card {
    padding: 1.875rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
    background: var(--surface-card);
    box-shadow: var(--shadow-xs);
    transition: transform 0.3s var(--ease-out), box-shadow 0.3s ease, border-color 0.3s ease;
    position: relative;
    overflow: hidden;
}
.cp-feature-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,80,160,0.03), transparent 60%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.cp-feature-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(0,80,160,0.15);
}
.cp-feature-card:hover::before { opacity: 1; }

.cp-feature-card__icon-wrap { margin-bottom: 1.125rem; }
.cp-feature-card__icon {
    width: 50px; height: 50px;
    border-radius: var(--radius-md);
    background: rgba(0,80,160,0.08);
    color: var(--color-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: transform 0.25s ease, background 0.25s ease;
}
.cp-feature-card:hover .cp-feature-card__icon {
    transform: scale(1.1);
    background: rgba(0,80,160,0.15);
}

.cp-feature-card h3 {
    font-size: 1.0625rem;
    font-weight: 700;
    color: var(--text-strong);
    margin-bottom: 0.5rem;
}
.cp-feature-card p {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.65;
    margin: 0;
}

/* ================================================================
   STATS BAND
================================================================ */
.cp-stats-band {
    background: linear-gradient(110deg, var(--cp-blue-900) 0%, var(--cp-blue-800) 60%, #0050A0 100%);
    color: #fff;
    padding-block: 4rem;
    position: relative;
    overflow: hidden;
}
.cp-stats-band::before {
    content: '';
    position: absolute;
    right: -5%;
    top: -50%;
    width: 30vw;
    height: 200%;
    background: radial-gradient(circle, rgba(232,135,26,0.12), transparent 60%);
}
.cp-stats-band__inner {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    position: relative;
}
.cp-stat { text-align: center; }
.cp-stat__value {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2.125rem, 4vw, 3rem);
    line-height: 1;
    color: var(--cp-orange-400);
    display: inline-block;
    transition: transform 0.4s ease;
}
.cp-stat:hover .cp-stat__value { transform: scale(1.1); }
.cp-stat__label { font-size: 0.9375rem; color: rgba(255,255,255,0.75); margin-top: 0.625rem; }

/* ================================================================
   CTA BOX
================================================================ */
.cp-cta-box {
    border-radius: var(--radius-2xl);
    overflow: hidden;
    position: relative;
    background: linear-gradient(110deg, var(--cp-blue-700), var(--cp-blue-900) 70%);
    color: #fff;
    padding: 3.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2.5rem;
    border: 1px solid rgba(255,255,255,0.08);
}
.cp-cta-box__glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.cp-cta-box__glow--main {
    right: -3rem; bottom: -4rem;
    width: 22rem; height: 22rem;
    background: radial-gradient(circle, rgba(232,135,26,0.22), transparent 65%);
    animation: glowPulse 5s ease-in-out infinite;
}
.cp-cta-box__glow--secondary {
    left: -2rem; top: -3rem;
    width: 15rem; height: 15rem;
    background: radial-gradient(circle, rgba(0,80,160,0.45), transparent 70%);
    animation: glowPulse 7s 1.5s ease-in-out infinite;
}
.cp-cta-box__content { position: relative; max-width: 560px; }
.cp-cta-box__action {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex-shrink: 0;
}

/* ================================================================
   ANIMACIONES HERO
================================================================ */
@keyframes glowDrift {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(3%, 3%) scale(1.05); }
}
@keyframes glowPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.7; transform: scale(1.07); }
}
@keyframes pulseDot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.5); opacity: 0.6; }
}
@keyframes cardFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-8px); }
}
@keyframes bounceDown {
    0%, 100% { transform: translateY(0); opacity: 0.4; }
    50%       { transform: translateY(6px); opacity: 0.8; }
}

/* Hero entrada escalonada */
.cp-anim-1 { animation: slideUp 0.65s cubic-bezier(0.16,1,0.3,1) both; }
.cp-anim-2 { animation: slideUp 0.65s 0.1s cubic-bezier(0.16,1,0.3,1) both; }
.cp-anim-3 { animation: slideUp 0.65s 0.2s cubic-bezier(0.16,1,0.3,1) both; }
.cp-anim-4 { animation: slideUp 0.65s 0.3s cubic-bezier(0.16,1,0.3,1) both; }
.cp-anim-5 { animation: fadeIn 0.8s 0.55s ease both; }
.cp-anim-6 { animation: slideLeft 0.8s 0.35s cubic-bezier(0.16,1,0.3,1) both; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes slideLeft {
    from { opacity: 0; transform: translateX(32px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* ================================================================
   SCROLL REVEAL
================================================================ */
.cp-reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s var(--ease-out), transform 0.6s var(--ease-out);
    transition-delay: var(--reveal-delay, 0s);
}
.cp-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}
.cp-reveal-header {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.cp-reveal-header.is-visible {
    opacity: 1;
    transform: translateY(0);
}
.cp-reveal-block {
    opacity: 0;
    transform: translateY(20px) scale(0.99);
    transition: opacity 0.7s ease, transform 0.7s var(--ease-out);
}
.cp-reveal-block.is-visible {
    opacity: 1;
    transform: translateY(0) scale(1);
}
.cp-reveal-stat {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.cp-reveal-stat.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Nav scrolled */
.cp-nav.scrolled {
    box-shadow: var(--shadow-md);
    background: rgba(255,255,255,0.98);
}

/* Botón micro-interacción */
.cp-btn { position: relative; overflow: hidden; }
.cp-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background 0.2s ease;
}
.cp-btn:hover::after { background: rgba(255,255,255,0.07); }
.cp-btn:active { transform: scale(0.97) translateY(1px); }

/* ================================================================
   RESPONSIVE
================================================================ */
@media (max-width: 1100px) {
    .cp-hero__inner { grid-template-columns: 1fr; gap: 3rem; }
    .cp-hero__visual { display: flex; justify-content: center; }
    .cp-hero__card-stack { flex-direction: row; flex-wrap: wrap; justify-content: center; }
    .cp-stat-card--mid { margin-inline: 0; }
    .cp-phases { grid-template-columns: repeat(4, 1fr); }
    .cp-features { grid-template-columns: repeat(2, 1fr); }
    .cp-hero { min-height: auto; padding-block: 5rem 4rem; }
}
@media (max-width: 900px) {
    .cp-phases { grid-template-columns: repeat(2, 1fr); }
    .cp-stats-band__inner { grid-template-columns: repeat(2, 1fr); }
    .cp-hero__pillars { gap: 1rem; }
}
@media (max-width: 768px) {
    .cp-hero__visual { display: none; }
    .cp-cta-box { flex-direction: column; padding: 2.5rem 2rem; }
    .cp-cta-box__action { width: 100%; align-items: stretch; }
}
@media (max-width: 540px) {
    .cp-phases { grid-template-columns: 1fr 1fr; }
    .cp-features { grid-template-columns: 1fr; }
    .cp-scroll-hint { display: none; }
    .cp-hero__pillars { flex-wrap: wrap; gap: 0.875rem; }
}
@media (max-width: 420px) {
    .cp-phases { grid-template-columns: 1fr; }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .cp-float-1, .cp-float-2, .cp-float-3,
    .cp-hero__bg-glow--orange, .cp-hero__bg-glow--blue,
    .cp-cta-box__glow--main, .cp-cta-box__glow--secondary,
    .cp-eyebrow__dot, .cp-hero__badge-dot, .cp-scroll-hint__arrow {
        animation: none !important;
    }
    .cp-anim-1, .cp-anim-2, .cp-anim-3, .cp-anim-4, .cp-anim-5, .cp-anim-6 {
        animation: none !important;
        opacity: 1 !important;
        transform: none !important;
    }
    .cp-reveal, .cp-reveal-header, .cp-reveal-block, .cp-reveal-stat {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}
</style>

@push('scripts')
<script>
(function () {
    // ── IntersectionObserver para scroll reveals ──────────────────
    if (!('IntersectionObserver' in window)) {
        // Fallback: mostrar todo inmediatamente
        document.querySelectorAll('.cp-reveal, .cp-reveal-header, .cp-reveal-block, .cp-reveal-stat')
            .forEach(el => el.classList.add('is-visible'));
        return;
    }

    const revealOpts = { threshold: 0.1, rootMargin: '0px 0px -48px 0px' };
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, revealOpts);

    document.querySelectorAll('.cp-reveal, .cp-reveal-header, .cp-reveal-block').forEach(el => io.observe(el));

    // Stagger de reveal-stat
    const statOpts = { threshold: 0.2, rootMargin: '0px 0px -20px 0px' };
    const statIo = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const stats = document.querySelectorAll('.cp-reveal-stat');
            stats.forEach((el, i) => {
                setTimeout(() => el.classList.add('is-visible'), i * 110);
            });
            statIo.disconnect();
        });
    }, statOpts);
    const firstStat = document.querySelector('.cp-reveal-stat');
    if (firstStat) statIo.observe(firstStat);

    // ── Nav shadow on scroll ──────────────────────────────────────
    const nav = document.querySelector('.cp-nav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 24);
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // ── Smooth scroll para links de anclaje ───────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Contador animado en stats band ────────────────────────────
    const counterIo = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const raw = el.dataset.count || el.textContent.trim();
            const num = parseInt(raw.replace(/\D/g, ''), 10);
            if (isNaN(num) || num === 0) return;

            const prefix = raw.startsWith('+') ? '+' : '';
            const suffix = raw.endsWith('%') ? '%' : '';
            const duration = 1400;
            const start = performance.now();

            const tick = (now) => {
                const t = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - t, 4); // easeOutQuart
                el.textContent = prefix + Math.round(eased * num).toLocaleString('es-CO') + suffix;
                if (t < 1) requestAnimationFrame(tick);
                else el.textContent = raw; // restaurar valor original exacto
            };
            requestAnimationFrame(tick);
            counterIo.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.cp-stat__value').forEach(el => counterIo.observe(el));

    // ── Parallax suave en el hero (solo desktop) ──────────────────
    const heroGlows = document.querySelectorAll('.cp-hero__bg-glow');
    if (window.matchMedia('(min-width: 900px) and (prefers-reduced-motion: no-preference)').matches && heroGlows.length) {
        window.addEventListener('mousemove', e => {
            const x = (e.clientX / window.innerWidth - 0.5) * 30;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            heroGlows[0] && (heroGlows[0].style.transform = `translate(${x * 0.4}px, ${y * 0.4}px)`);
            heroGlows[1] && (heroGlows[1].style.transform = `translate(${-x * 0.25}px, ${-y * 0.25}px)`);
        }, { passive: true });
    }
})();
</script>
@endpush
