<x-layouts.landing title="Inicio" description="Sistema de Gestión Documental DICACOCU — Confipetrol">

    {{-- ============================
         HERO
    ============================= --}}
    <section class="cp-hero">
        <div class="cp-hero__glow"></div>
        <div class="cp-container cp-hero__inner">
            <div class="cp-hero__content">
                <p class="cp-eyebrow">Sistema de Gestión Documental</p>
                <h1 class="cp-hero__title">
                    DICACOCU<br>
                    <span class="cp-hero__title-accent">en un solo sistema</span>
                </h1>
                <p class="cp-hero__desc">
                    Gestión documental privada, trazable y alineada al ciclo operacional
                    de Ecopetrol S.A. Disponibilidad, Calidad, Comunicación, Cumplimiento.
                </p>
                <div class="cp-hero__cta">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg">
                        <i class="fa-solid fa-right-to-bracket"></i> Acceder al sistema
                    </a>
                    <a href="{{ route('landing.dicacocu') }}" class="cp-btn cp-btn--white-outline cp-btn--lg">
                        <i class="fa-solid fa-circle-info"></i> Conocer DICACOCU
                    </a>
                </div>
            </div>
            <div class="cp-hero__visual">
                <div class="cp-hero__card-stack">
                    <div class="cp-stat-card">
                        <i class="fa-solid fa-file-circle-check"></i>
                        <span class="cp-stat-card__value">+2.400</span>
                        <span class="cp-stat-card__label">Documentos gestionados</span>
                    </div>
                    <div class="cp-stat-card cp-stat-card--offset">
                        <i class="fa-solid fa-users-gear"></i>
                        <span class="cp-stat-card__value">8</span>
                        <span class="cp-stat-card__label">Fases del ciclo DICACOCU</span>
                    </div>
                    <div class="cp-stat-card cp-stat-card--bottom">
                        <i class="fa-solid fa-shield-check"></i>
                        <span class="cp-stat-card__value">100%</span>
                        <span class="cp-stat-card__label">Trazabilidad documental</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================
         FASES DICACOCU
    ============================= --}}
    <section class="cp-section cp-section--white">
        <div class="cp-container">
            <div class="cp-section__header">
                <p class="cp-eyebrow">El ciclo operacional</p>
                <h2>Las 8 fases del ciclo DICACOCU</h2>
                <div class="cp-accent-rule" style="margin-inline: auto; margin-top: 1rem;"></div>
            </div>
            <div class="cp-phases">
                @foreach([
                    ['D', 'Disponibilidad',  'fa-power-off',        'Garantizar la disponibilidad de activos e información crítica en todo momento.'],
                    ['I', 'Integridad',      'fa-shield-halved',    'Asegurar la integridad y exactitud de los documentos operacionales.'],
                    ['C', 'Calidad',         'fa-circle-check',     'Mantener estándares de calidad en cada documento generado.'],
                    ['A', 'Acceso',          'fa-key',              'Control de acceso por roles y responsabilidades definidas.'],
                    ['C', 'Comunicación',    'fa-bullhorn',         'Divulgación efectiva a los equipos responsables de su aplicación.'],
                    ['O', 'Operación',       'fa-gears',            'Aplicación correcta de los procedimientos en campo.'],
                    ['C', 'Cumplimiento',    'fa-clipboard-check',  'Verificación del cumplimiento de normas y estándares Ecopetrol.'],
                    ['U', 'Uso',             'fa-chart-line',       'Seguimiento del uso efectivo del documento en las operaciones.'],
                ] as $i => [$letra, $nombre, $icono, $desc])
                <div class="cp-phase-card">
                    <div class="cp-phase-card__letter">{{ $letra }}</div>
                    <div class="cp-phase-card__icon"><i class="fa-solid {{ $icono }}"></i></div>
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
    <section class="cp-section" style="background: var(--surface-page);">
        <div class="cp-container">
            <div class="cp-section__header">
                <p class="cp-eyebrow">El sistema</p>
                <h2>Funcionalidades del SGD DICACOCU</h2>
                <div class="cp-accent-rule" style="margin-inline: auto; margin-top: 1rem;"></div>
            </div>
            <div class="cp-features">
                @foreach([
                    ['fa-folder-open',      'Gestión Documental',         'Captura, clasificación y versionado de documentos por carpetas y fases DICACOCU.'],
                    ['fa-code-branch',      'Control de versiones',        'Historial completo de cambios con estados: Borrador → Revisión → Aprobado → Divulgado.'],
                    ['fa-magnifying-glass', 'Búsqueda inteligente',        'Búsqueda de texto completo sobre el contenido de documentos, incluyendo OCR para PDFs.'],
                    ['fa-lock',             'Control de acceso por roles',  'Super Admin, Gestor, Revisor, Colaborador y Consultor con permisos granulares.'],
                    ['fa-chart-bar',        'Reportes y trazabilidad',     'Reportes de cumplimiento, auditoría de actividad y dashboard por fase DICACOCU.'],
                    ['fa-robot',            'Asistente RAG',               'Asistente de IA que responde preguntas sobre el contenido de los documentos del sistema.'],
                ] as [$icono, $titulo, $desc])
                <div class="cp-feature-card">
                    <div class="cp-feature-card__icon">
                        <i class="fa-solid {{ $icono }}"></i>
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
    <section class="cp-stats-band">
        <div class="cp-container cp-stats-band__inner">
            @foreach([['+20', 'Años de experiencia Confipetrol'], ['6', 'Países en Latinoamérica'], ['8', 'Fases ciclo DICACOCU'], ['100%', 'Trazabilidad garantizada']] as [$v, $l])
            <div class="cp-stat">
                <div class="cp-stat__value">{{ $v }}</div>
                <div class="cp-stat__label">{{ $l }}</div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ============================
         CTA ACCESO
    ============================= --}}
    <section class="cp-section" style="background: var(--surface-page);">
        <div class="cp-container">
            <div class="cp-cta-box">
                <div class="cp-cta-box__glow"></div>
                <div class="cp-cta-box__content">
                    <p class="cp-eyebrow" style="color: var(--cp-orange-400);">Acceso al sistema</p>
                    <h2 style="color: #fff; margin-bottom: 0.75rem;">¿Tienes cuenta en el SGD DICACOCU?</h2>
                    <p style="color: rgba(255,255,255,0.82); font-size: 1.0625rem; line-height: 1.65; max-width: 520px;">
                        Accede con tus credenciales corporativas para consultar, gestionar y hacer seguimiento
                        de los documentos del ciclo operacional.
                    </p>
                </div>
                <div class="cp-cta-box__action">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg">
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
/* HERO */
.cp-hero {
    background: linear-gradient(120deg, var(--cp-blue-900) 0%, var(--cp-blue-700) 60%, var(--cp-blue-600) 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
    padding-block: 5.5rem 6rem;
}
.cp-hero__glow {
    position: absolute;
    top: -7.5rem; right: -5rem;
    width: 32.5rem; height: 32.5rem;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,138,31,0.22), transparent 70%);
    pointer-events: none;
}
.cp-hero__inner {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 3.5rem;
    align-items: center;
    position: relative;
}
.cp-hero__content .cp-eyebrow { color: var(--cp-orange-400); }
.cp-hero__title {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    line-height: 1.04;
    letter-spacing: -0.02em;
    color: #fff;
    margin: 0.5rem 0 1.125rem;
}
.cp-hero__title-accent { color: var(--cp-orange-400); }
.cp-hero__desc {
    font-size: 1.125rem;
    line-height: 1.65;
    color: rgba(255,255,255,0.85);
    max-width: 460px;
    margin-bottom: 2rem;
}
.cp-hero__cta { display: flex; gap: 0.875rem; flex-wrap: wrap; }

.cp-hero__card-stack {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
    position: relative;
}
.cp-stat-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.cp-stat-card i { font-size: 1.5rem; color: var(--cp-orange-400); flex-shrink: 0; }
.cp-stat-card__value {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.625rem;
    color: #fff;
    line-height: 1;
}
.cp-stat-card__label { font-size: 0.8125rem; color: rgba(255,255,255,0.72); }
.cp-stat-card--offset { margin-left: 1.5rem; }
.cp-stat-card--bottom { margin-left: 0.75rem; }

/* SECTIONS */
.cp-section { padding-block: 5rem; }
.cp-section--white { background: var(--surface-card); }
.cp-section__header { text-align: center; margin-bottom: 2.75rem; }
.cp-section__header h2 {
    font-size: clamp(1.75rem, 3vw, 2.25rem);
    letter-spacing: -0.02em;
    margin-top: 0.5rem;
}

/* PHASES */
.cp-phases {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.375rem;
}
.cp-phase-card {
    background: var(--surface-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-xs);
    transition: transform var(--dur-base) var(--ease-out), box-shadow var(--dur-base);
    position: relative;
    overflow: hidden;
}
.cp-phase-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
.cp-phase-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--color-accent);
}
.cp-phase-card__letter {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 2rem;
    color: var(--color-primary);
    line-height: 1;
    opacity: 0.15;
    position: absolute;
    top: 0.75rem; right: 1rem;
}
.cp-phase-card__icon {
    width: 42px; height: 42px;
    border-radius: var(--radius-md);
    background: var(--cp-blue-50);
    color: var(--color-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    margin-bottom: 0.875rem;
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
    line-height: 1.55;
    margin: 0;
}

/* FEATURES */
.cp-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
.cp-feature-card {
    padding: 1.75rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
    background: var(--surface-card);
    box-shadow: var(--shadow-xs);
    transition: box-shadow var(--dur-base);
}
.cp-feature-card:hover { box-shadow: var(--shadow-md); }
.cp-feature-card__icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    background: var(--cp-blue-50);
    color: var(--color-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 1rem;
}
.cp-feature-card h3 {
    font-size: 1.0625rem;
    font-weight: 700;
    color: var(--text-strong);
    margin-bottom: 0.5rem;
}
.cp-feature-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0; }

/* STATS BAND */
.cp-stats-band { background: var(--cp-blue-800); color: #fff; padding-block: 3.5rem; }
.cp-stats-band__inner {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}
.cp-stat { text-align: center; }
.cp-stat__value {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2rem, 4vw, 2.875rem);
    line-height: 1;
    color: var(--cp-orange-400);
}
.cp-stat__label { font-size: 0.9375rem; color: rgba(255,255,255,0.82); margin-top: 0.5rem; }

/* CTA BOX */
.cp-cta-box {
    border-radius: var(--radius-2xl);
    overflow: hidden;
    position: relative;
    background: linear-gradient(110deg, var(--cp-blue-700), var(--cp-blue-900));
    color: #fff;
    padding: 3.25rem 3.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2.5rem;
}
.cp-cta-box__glow {
    position: absolute;
    right: -2.5rem; bottom: -3.75rem;
    width: 20rem; height: 20rem;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,138,31,0.25), transparent 70%);
    pointer-events: none;
}
.cp-cta-box__content { position: relative; max-width: 560px; }
.cp-cta-box__action {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex-shrink: 0;
}

/* ANIMACIONES */
@keyframes heroSlideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes heroFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes cardFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-6px); }
}
@keyframes glowPulse {
    0%, 100% { opacity: 0.22; transform: scale(1); }
    50%      { opacity: 0.38; transform: scale(1.08); }
}
@keyframes countUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Hero entrada */
.cp-hero__content .cp-eyebrow { animation: heroSlideUp 0.6s ease both; }
.cp-hero__title               { animation: heroSlideUp 0.6s 0.1s ease both; }
.cp-hero__desc                { animation: heroSlideUp 0.6s 0.2s ease both; }
.cp-hero__cta                 { animation: heroSlideUp 0.6s 0.32s ease both; }
.cp-hero__visual              { animation: heroFadeIn 0.8s 0.4s ease both; }

/* Glow animado */
.cp-hero__glow { animation: glowPulse 6s ease-in-out infinite; }
.cp-cta-box__glow { animation: glowPulse 5s 1s ease-in-out infinite; }

/* Stat cards del hero con flotación */
.cp-stat-card:nth-child(1) { animation: cardFloat 4s 0s ease-in-out infinite; }
.cp-stat-card:nth-child(2) { animation: cardFloat 4s 1.3s ease-in-out infinite; }
.cp-stat-card:nth-child(3) { animation: cardFloat 4s 2.6s ease-in-out infinite; }

/* Scroll reveal — oculto por defecto */
.cp-reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.cp-reveal.cp-revealed {
    opacity: 1;
    transform: translateY(0);
}
.cp-reveal:nth-child(2) { transition-delay: 0.08s; }
.cp-reveal:nth-child(3) { transition-delay: 0.16s; }
.cp-reveal:nth-child(4) { transition-delay: 0.24s; }
.cp-reveal:nth-child(5) { transition-delay: 0.32s; }
.cp-reveal:nth-child(6) { transition-delay: 0.40s; }
.cp-reveal:nth-child(7) { transition-delay: 0.48s; }
.cp-reveal:nth-child(8) { transition-delay: 0.56s; }

/* Stats band counter */
.cp-stat__value {
    display: inline-block;
    transition: transform 0.4s ease;
}
.cp-stat:hover .cp-stat__value { transform: scale(1.12); }

/* Botones con micro-interacción mejorada */
.cp-btn {
    position: relative;
    overflow: hidden;
}
.cp-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background 0.2s ease;
}
.cp-btn:hover::after { background: rgba(255,255,255,0.08); }
.cp-btn:active { transform: scale(0.97) translateY(1px); }

/* Phase cards stagger */
.cp-phase-card {
    position: relative;
    overflow: hidden;
}
.cp-phase-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,80,160,0.03), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.cp-phase-card:hover::after { opacity: 1; }
.cp-phase-card__icon {
    transition: transform 0.25s ease, background 0.25s ease;
}
.cp-phase-card:hover .cp-phase-card__icon {
    transform: scale(1.12) rotate(-3deg);
    background: var(--cp-blue-100);
}

/* Feature cards */
.cp-feature-card__icon {
    transition: transform 0.25s ease, background 0.25s ease;
}
.cp-feature-card:hover .cp-feature-card__icon {
    transform: scale(1.1);
    background: var(--cp-blue-100);
}
.cp-feature-card {
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}
.cp-feature-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

/* Nav scroll effect (manejado por JS) */
.cp-nav.scrolled {
    box-shadow: var(--shadow-md);
    background: rgba(255,255,255,0.98);
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .cp-phases { grid-template-columns: repeat(2, 1fr); }
    .cp-features { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .cp-hero__inner { grid-template-columns: 1fr; }
    .cp-hero__visual { display: none; }
    .cp-stats-band__inner { grid-template-columns: repeat(2, 1fr); }
    .cp-cta-box { flex-direction: column; }
}
@media (max-width: 540px) {
    .cp-phases { grid-template-columns: 1fr; }
    .cp-features { grid-template-columns: 1fr; }
    .cp-stats-band__inner { grid-template-columns: 1fr 1fr; }
}

@media (prefers-reduced-motion: reduce) {
    .cp-stat-card, .cp-hero__glow, .cp-cta-box__glow {
        animation: none !important;
    }
    .cp-reveal {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}
</style>

@push('scripts')
<script>
// Scroll reveal con IntersectionObserver
(function () {
    if (!('IntersectionObserver' in window)) return;

    // Añadir clase cp-reveal a elementos candidatos
    document.querySelectorAll('.cp-phase-card, .cp-feature-card').forEach(el => {
        el.classList.add('cp-reveal');
    });

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('cp-revealed');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.cp-reveal').forEach(el => io.observe(el));

    // Nav shadow on scroll
    const nav = document.querySelector('.cp-nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    }

    // Stats counter animation
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const raw = el.textContent.trim();
            const num = parseInt(raw.replace(/\D/g, ''), 10);
            if (!isNaN(num) && num > 0) {
                const prefix = raw.startsWith('+') ? '+' : '';
                const suffix = raw.endsWith('%') ? '%' : '';
                const duration = 1200;
                const start = performance.now();
                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = prefix + Math.round(eased * num) + suffix;
                    if (progress < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            }
            statsObserver.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.cp-stat__value').forEach(el => statsObserver.observe(el));
})();
</script>
@endpush
