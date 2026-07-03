<x-filament-panels::page.simple>
    <style>
    /* ===== SGD DICACOCU — Login branded ===== */

    /* Fondo corporativo sobre el body de Filament */
    body {
        background: linear-gradient(135deg, #0A1F44 0%, #0D2A5E 45%, #0050A0 100%) !important;
        min-height: 100vh;
        position: relative;
    }
    body::before {
        content: '';
        position: fixed;
        top: -25%;
        right: -12%;
        width: 55vw;
        height: 55vw;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232,135,26,0.16) 0%, transparent 60%);
        pointer-events: none;
        animation: sgdGlow 8s ease-in-out infinite alternate;
        z-index: 0;
    }
    body::after {
        content: '';
        position: fixed;
        bottom: -18%;
        left: -8%;
        width: 42vw;
        height: 42vw;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,50,130,0.5) 0%, transparent 65%);
        pointer-events: none;
        animation: sgdGlow 11s 2s ease-in-out infinite alternate-reverse;
        z-index: 0;
    }
    .sgd-grid-overlay {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.022) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.022) 1px, transparent 1px);
        background-size: 52px 52px;
        pointer-events: none;
        z-index: 0;
    }

    /* Header corporativo encima del card */
    .sgd-login-header {
        text-align: center;
        margin-bottom: 2rem;
        animation: sgdSlideDown 0.55s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
    }
    .sgd-login-logo {
        width: auto;
        height: 50px;
        display: block;
        margin: 0 auto 1.125rem;
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }
    .sgd-login-app-name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: -0.025em;
        color: #fff;
        line-height: 1.1;
        margin: 0 0 0.3rem;
    }
    .sgd-login-app-sub {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.55);
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }
    .sgd-login-accent-line {
        width: 2.75rem;
        height: 3px;
        background: linear-gradient(90deg, #E8871A, #F5A940);
        border-radius: 2px;
        margin: 0.875rem auto 0;
    }

    /* Sobreescribir el card de Filament */
    .fi-simple-main {
        background: rgba(255,255,255,0.05) !important;
        backdrop-filter: blur(20px) saturate(1.3) !important;
        border: 1px solid rgba(255,255,255,0.11) !important;
        border-radius: 1.25rem !important;
        box-shadow:
            0 6px 40px rgba(0,0,0,0.4),
            0 1px 0 rgba(255,255,255,0.06) inset !important;
        animation: sgdScaleIn 0.5s 0.18s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
    }

    /* Textos dentro del card */
    .fi-simple-main label,
    .fi-simple-main .fi-fo-field-wrp-label span,
    .fi-simple-main p:not(.fi-fo-field-wrp-error-message) {
        color: rgba(255,255,255,0.82) !important;
    }

    /* Inputs */
    .fi-simple-main input[type="email"],
    .fi-simple-main input[type="password"],
    .fi-simple-main input[type="text"] {
        background: rgba(255,255,255,0.07) !important;
        border-color: rgba(255,255,255,0.14) !important;
        color: #fff !important;
        border-radius: 0.625rem !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
    }
    .fi-simple-main input::placeholder {
        color: rgba(255,255,255,0.3) !important;
    }
    .fi-simple-main input:focus {
        border-color: rgba(232,135,26,0.65) !important;
        box-shadow: 0 0 0 3px rgba(232,135,26,0.16) !important;
        background: rgba(255,255,255,0.1) !important;
    }

    /* Botón enviar */
    .fi-simple-main .fi-btn-color-primary,
    .fi-simple-main button[type="submit"] {
        background: linear-gradient(135deg, #E8871A 0%, #F5A940 100%) !important;
        border: none !important;
        border-radius: 0.625rem !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 700 !important;
        font-size: 0.9375rem !important;
        box-shadow: 0 4px 18px rgba(232,135,26,0.38) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }
    .fi-simple-main .fi-btn-color-primary:hover,
    .fi-simple-main button[type="submit"]:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 24px rgba(232,135,26,0.5) !important;
    }

    /* Links */
    .fi-simple-main a { color: #F5A940 !important; }
    .fi-simple-main a:hover { color: #FFB84D !important; }

    /* Checkbox "Recuérdame" */
    .fi-simple-main .fi-checkbox-input:checked {
        background-color: #E8871A !important;
        border-color: #E8871A !important;
    }

    /* Error messages */
    .fi-simple-main .fi-fo-field-wrp-error-message { color: #fca5a5 !important; }

    /* Pillars DICACOCU debajo del card */
    .sgd-pillars {
        display: flex;
        justify-content: center;
        gap: 2.25rem;
        margin-top: 2rem;
        animation: sgdFadeUp 0.6s 0.7s cubic-bezier(0.16,1,0.3,1) both;
        position: relative;
        z-index: 1;
    }
    .sgd-pillar { text-align: center; }
    .sgd-pillar__letter {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.25rem;
        color: #E8871A;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .sgd-pillar__label {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(255,255,255,0.42);
    }

    /* Keyframes */
    @keyframes sgdGlow {
        from { transform: translate(0,0) scale(1); opacity: 1; }
        to   { transform: translate(3%,3%) scale(1.06); opacity: 0.7; }
    }
    @keyframes sgdSlideDown {
        from { opacity: 0; transform: translateY(-18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdFadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdScaleIn {
        from { opacity: 0; transform: scale(0.97) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        body::before, body::after { animation: none; }
        .sgd-login-header, .fi-simple-main, .sgd-pillars { animation: none; opacity: 1; transform: none; }
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
