<x-filament-panels::page.simple>
    <style>
    /* ===== SGD DICACOCU — Login ===== */

    /* ── Variables de paleta ── */
    :root {
        --sgd-dark:    #060F26;
        --sgd-navy:    #0A1F44;
        --sgd-blue:    #0D2A5E;
        --sgd-mid:     #0050A0;
        --sgd-orange:  #E8871A;
        --sgd-gold:    #F5A940;
        --sgd-light:   #FFD08A;
        --sgd-card-bg: #0D1E40;
        --sgd-input:   #112048;
        --sgd-border:  #1E3A6E;
        --sgd-border-focus: #E8871A;
    }

    /* ── Fondo ── */
    html, body {
        background: var(--sgd-dark) !important;
        min-height: 100vh;
    }

    /* Gradiente de fondo rico */
    .fi-simple-layout,
    .fi-simple-layout > div {
        background: linear-gradient(150deg,
            #04102A 0%,
            #071840 30%,
            #0A1F55 60%,
            #0D2A6A 100%) !important;
        min-height: 100vh;
    }

    /* Glow naranja superior derecho */
    body::before {
        content: '';
        position: fixed;
        top: -20%;
        right: -8%;
        width: 50vw;
        height: 50vw;
        border-radius: 50%;
        background: radial-gradient(circle,
            rgba(232,135,26,0.22) 0%,
            rgba(232,135,26,0.08) 40%,
            transparent 70%);
        pointer-events: none;
        z-index: 0;
        animation: sgdGlow 9s ease-in-out infinite alternate;
    }

    /* Glow azul inferior izquierdo */
    body::after {
        content: '';
        position: fixed;
        bottom: -15%;
        left: -6%;
        width: 38vw;
        height: 38vw;
        border-radius: 50%;
        background: radial-gradient(circle,
            rgba(0,80,160,0.5) 0%,
            transparent 65%);
        pointer-events: none;
        z-index: 0;
        animation: sgdGlow 12s 3s ease-in-out infinite alternate-reverse;
    }

    /* Rejilla sutil */
    .sgd-grid-overlay {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
        background-size: 52px 52px;
        pointer-events: none;
        z-index: 0;
    }

    /* ── Header corporativo ── */
    .sgd-login-header {
        text-align: center;
        margin-bottom: 1.75rem;
        animation: sgdSlideDown 0.55s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
    }
    .sgd-login-logo {
        width: auto;
        height: 48px;
        display: block;
        margin: 0 auto 1rem;
        filter: brightness(0) invert(1);
        opacity: 0.93;
        drop-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
    .sgd-login-app-name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: -0.02em;
        color: #fff;
        line-height: 1.1;
        margin: 0 0 0.3rem;
    }
    .sgd-login-app-sub {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.6875rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }
    .sgd-login-accent-line {
        width: 2.5rem;
        height: 3px;
        background: linear-gradient(90deg, var(--sgd-orange), var(--sgd-gold));
        border-radius: 2px;
        margin: 0.75rem auto 0;
        box-shadow: 0 0 12px rgba(232,135,26,0.5);
    }

    /* ── Card del formulario ── */
    .fi-simple-main {
        background: var(--sgd-card-bg) !important;
        border: 1px solid var(--sgd-border) !important;
        border-radius: 1.125rem !important;
        box-shadow:
            0 0 0 1px rgba(255,255,255,0.04) inset,
            0 8px 48px rgba(0,0,0,0.55),
            0 2px 0 rgba(255,255,255,0.05) inset !important;
        animation: sgdScaleIn 0.5s 0.18s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    /* Barra naranja superior del card */
    .fi-simple-main::before {
        content: '';
        display: block;
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            transparent 0%,
            var(--sgd-orange) 20%,
            var(--sgd-gold) 60%,
            transparent 100%);
        z-index: 2;
    }

    /* ── Textos dentro del card ── */
    .fi-simple-main label,
    .fi-simple-main .fi-fo-field-wrp-label,
    .fi-simple-main .fi-fo-field-wrp-label span,
    .fi-simple-main [class*="fi-fo"] label {
        color: #C8D8F0 !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 600 !important;
        font-size: 0.8125rem !important;
    }

    /* ── Inputs ── */
    .fi-simple-main input[type="email"],
    .fi-simple-main input[type="password"],
    .fi-simple-main input[type="text"] {
        background: var(--sgd-input) !important;
        border: 1.5px solid var(--sgd-border) !important;
        color: #E8F0FF !important;
        border-radius: 0.5rem !important;
        font-size: 0.9375rem !important;
        padding-block: 0.625rem !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
    }
    .fi-simple-main input::placeholder {
        color: rgba(160,190,230,0.4) !important;
    }
    .fi-simple-main input:focus,
    .fi-simple-main input:focus-visible {
        background: #162B55 !important;
        border-color: var(--sgd-orange) !important;
        box-shadow: 0 0 0 3px rgba(232,135,26,0.2), 0 0 12px rgba(232,135,26,0.1) !important;
        outline: none !important;
    }

    /* Icono del ojo en password */
    .fi-simple-main [data-icon],
    .fi-simple-main .fi-input-suffix-item svg {
        color: rgba(160,190,230,0.5) !important;
    }

    /* ── Botón de acceso ── */
    .fi-simple-main .fi-btn,
    .fi-simple-main button[type="submit"] {
        background: linear-gradient(135deg, #C97315 0%, var(--sgd-orange) 45%, var(--sgd-gold) 100%) !important;
        border: none !important;
        border-radius: 0.5rem !important;
        color: #fff !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 700 !important;
        font-size: 0.9375rem !important;
        letter-spacing: 0.01em !important;
        box-shadow: 0 4px 20px rgba(232,135,26,0.45), 0 1px 0 rgba(255,255,255,0.15) inset !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.25) !important;
    }
    .fi-simple-main .fi-btn:hover,
    .fi-simple-main button[type="submit"]:hover {
        filter: brightness(1.08) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 28px rgba(232,135,26,0.55), 0 1px 0 rgba(255,255,255,0.15) inset !important;
    }
    .fi-simple-main .fi-btn:active,
    .fi-simple-main button[type="submit"]:active {
        transform: translateY(0) !important;
        filter: brightness(0.95) !important;
    }

    /* ── Links (¿Olvidó contraseña?) ── */
    .fi-simple-main a {
        color: var(--sgd-gold) !important;
        font-weight: 600 !important;
        transition: color 0.15s !important;
    }
    .fi-simple-main a:hover { color: var(--sgd-light) !important; }

    /* ── Checkbox "Recuérdame" ── */
    .fi-simple-main .fi-checkbox-input {
        border-color: var(--sgd-border) !important;
        background: var(--sgd-input) !important;
    }
    .fi-simple-main .fi-checkbox-input:checked {
        background-color: var(--sgd-orange) !important;
        border-color: var(--sgd-orange) !important;
    }

    /* ── Mensajes de error ── */
    .fi-simple-main .fi-fo-field-wrp-error-message,
    .fi-simple-main [class*="error"] p {
        color: #FCA5A5 !important;
        font-size: 0.8125rem !important;
    }
    .fi-simple-main input.fi-input-invalid,
    .fi-simple-main input[aria-invalid="true"] {
        border-color: #F87171 !important;
        box-shadow: 0 0 0 3px rgba(248,113,113,0.18) !important;
    }

    /* ── Pillars DICACOCU ── */
    .sgd-pillars {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        animation: sgdFadeUp 0.6s 0.75s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
    }
    .sgd-pillar { text-align: center; }
    .sgd-pillar__letter {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.25rem;
        background: linear-gradient(135deg, var(--sgd-orange), var(--sgd-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .sgd-pillar__label {
        font-size: 0.5875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        color: rgba(160,190,230,0.45);
    }
    .sgd-pillar-divider {
        width: 1px;
        height: 2rem;
        background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.12), transparent);
        align-self: center;
    }

    /* ── Keyframes ── */
    @keyframes sgdGlow {
        from { opacity: 1;   transform: translate(0,0)   scale(1); }
        to   { opacity: 0.6; transform: translate(2%,2%) scale(1.05); }
    }
    @keyframes sgdSlideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdScaleIn {
        from { opacity: 0; transform: scale(0.96) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        body::before, body::after { animation: none !important; }
        .sgd-login-header, .fi-simple-main, .sgd-pillars { animation: none !important; opacity: 1 !important; transform: none !important; }
    }
    </style>

    {{-- Overlay de rejilla --}}
    <div class="sgd-grid-overlay" aria-hidden="true"></div>

    {{-- Encabezado corporativo --}}
    <div class="sgd-login-header">
        <img src="{{ asset('images/confipetrol-logo-white.png') }}"
             alt="Confipetrol S.A."
             class="sgd-login-logo">
        <p class="sgd-login-app-name">SGD DICACOCU</p>
        <p class="sgd-login-app-sub">Sistema de Gestión Documental</p>
        <div class="sgd-login-accent-line"></div>
    </div>

    {{-- Formulario Filament v5 (renderizado como content schema) --}}
    {{ $this->content }}

    {{-- Pillars DICACOCU --}}
    <div class="sgd-pillars">
        @foreach([['DI','Disponibilidad'],['CA','Calidad'],['CO','Comunicación'],['CU','Cumplimiento']] as [$l,$n])
        <div class="sgd-pillar">
            <div class="sgd-pillar__letter">{{ $l }}</div>
            <div class="sgd-pillar__label">{{ $n }}</div>
        </div>
        @endforeach
    </div>
</x-filament-panels::page.simple>
