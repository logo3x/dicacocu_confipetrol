<header class="cp-nav">
    {{-- Utility strip --}}
    <div class="cp-nav__strip">
        <div class="cp-container cp-nav__strip-inner">
            <span class="cp-nav__strip-item">
                <i class="fa-solid fa-earth-americas"></i> Global
            </span>
            <span class="cp-nav__strip-divider">|</span>
            <span>CO · PE · CL · BO · EC · VE</span>
            <span class="cp-nav__strip-divider">|</span>
            <a href="#" class="cp-nav__lang active">ES</a>
            <a href="#" class="cp-nav__lang">EN</a>
        </div>
    </div>

    {{-- Main bar --}}
    <div class="cp-container cp-nav__main">
        <a href="{{ route('landing') }}" class="cp-nav__logo">
            <img src="{{ asset('images/confipetrol-logo.png') }}" alt="Confipetrol">
        </a>

        <nav class="cp-nav__links" role="navigation" aria-label="Menú principal">
            <a href="{{ route('landing') }}" class="cp-nav__link {{ request()->routeIs('landing') ? 'cp-nav__link--active' : '' }}">Inicio</a>
            <a href="{{ route('landing.sgd') }}" class="cp-nav__link {{ request()->routeIs('landing.sgd') ? 'cp-nav__link--active' : '' }}">Sistema SGD</a>
            <a href="{{ route('landing.dicacocu') }}" class="cp-nav__link {{ request()->routeIs('landing.dicacocu') ? 'cp-nav__link--active' : '' }}">DICACOCU</a>
            <a href="{{ route('landing.contacto') }}" class="cp-nav__link {{ request()->routeIs('landing.contacto') ? 'cp-nav__link--active' : '' }}">Contacto</a>
        </nav>

        <div class="cp-nav__actions">
            <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--sm">
                <i class="fa-solid fa-right-to-bracket"></i> Acceder al sistema
            </a>
        </div>

        {{-- Mobile toggle --}}
        <button class="cp-nav__toggle" aria-label="Abrir menú" onclick="document.querySelector('.cp-nav__links').classList.toggle('cp-nav__links--open')">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</header>

<style>
.cp-nav {
    position: sticky;
    top: 0;
    z-index: 50;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-subtle);
    box-shadow: var(--shadow-xs);
}

.cp-nav__strip {
    background: var(--cp-blue-800);
    color: rgba(255,255,255,0.82);
}

.cp-nav__strip-inner {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1.25rem;
    padding-block: 0.25rem;
    font-family: var(--font-display);
    font-size: 0.75rem;
    font-weight: 500;
}

.cp-nav__strip-item { display: inline-flex; align-items: center; gap: 0.375rem; }
.cp-nav__strip-item i { color: var(--cp-orange-400); }
.cp-nav__strip-divider { opacity: 0.35; }

.cp-nav__lang {
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
    font-family: var(--font-display);
    font-weight: 600;
    transition: color var(--dur-fast);
}
.cp-nav__lang.active,
.cp-nav__lang:hover { color: #fff; }

.cp-nav__main {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-block: 0.5rem;
}

.cp-nav__logo img {
    display: block;
    height: 36px;
    width: auto;
    object-fit: contain;
}

.cp-nav__links {
    display: flex;
    gap: 1.75rem;
    flex: 1;
    justify-content: center;
}

.cp-nav__link {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-body);
    padding-bottom: 0.25rem;
    border-bottom: 2px solid transparent;
    transition: color var(--dur-fast), border-color var(--dur-fast);
}
.cp-nav__link:hover { color: var(--color-primary); }
.cp-nav__link--active {
    color: var(--color-primary);
    border-bottom-color: var(--color-accent);
}

.cp-nav__toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--text-body);
    cursor: pointer;
    padding: 0.25rem;
    margin-left: auto;
}

/* Buttons (shared) */
.cp-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-weight: 700;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: background var(--dur-fast) var(--ease-out), transform var(--dur-fast);
    text-decoration: none;
    white-space: nowrap;
    border: none;
}
.cp-btn--sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
.cp-btn--md { padding: 0.6875rem 1.375rem; font-size: 0.9375rem; }
.cp-btn--lg { padding: 0.875rem 1.75rem; font-size: 1rem; }

.cp-btn--primary { background: var(--color-primary); color: #fff; }
.cp-btn--primary:hover { background: var(--color-primary-hover); color: #fff; }

.cp-btn--accent { background: var(--color-accent); color: #fff; }
.cp-btn--accent:hover { background: var(--color-accent-hover); color: #fff; }

.cp-btn--ghost { background: transparent; color: var(--color-primary); border: 1.5px solid var(--cp-blue-200); }
.cp-btn--ghost:hover { background: var(--cp-blue-50); }

.cp-btn--white-outline { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.35); }
.cp-btn--white-outline:hover { background: rgba(255,255,255,0.2); color: #fff; }

.cp-btn:active { transform: translateY(1px); }

@media (max-width: 900px) {
    .cp-nav__links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0; right: 0;
        background: #fff;
        flex-direction: column;
        padding: 1rem 1.5rem 1.5rem;
        gap: 0.75rem;
        border-top: 1px solid var(--border-subtle);
        box-shadow: var(--shadow-md);
    }
    .cp-nav__links--open { display: flex; }
    .cp-nav__toggle { display: block; }
}
</style>
