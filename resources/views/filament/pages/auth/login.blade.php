<div class="sgd-login-screen">
    <style>
    /* ===== SGD DICACOCU — Login (dos columnas, pantalla completa) ===== */

    :root {
        --sgd-dark:    #060F26;
        --sgd-navy:    #0A1F44;
        --sgd-blue:    #0D2A5E;
        --sgd-mid:     #0050A0;
        --sgd-orange:  #E8871A;
        --sgd-gold:    #F5A940;
        --sgd-light:   #FFD08A;
        --sgd-ink:     #0B1220;
        --sgd-border:  #E4E9F2;
        --sgd-border-strong: #CBD5E7;
        --sgd-muted:   #5B6B8C;
    }

    html, body {
        height: 100%;
    }

    /* ── Layout de dos columnas a pantalla completa ── */
    .sgd-login-screen {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
    }

    @media (max-width: 960px) {
        .sgd-login-screen {
            grid-template-columns: 1fr;
        }
        .sgd-login-visual {
            min-height: 38vh;
        }
    }

    /* ── Columna izquierda: panel de marca ── */
    .sgd-login-visual {
        position: relative;
        overflow: hidden;
        background: linear-gradient(155deg,
            #04102A 0%,
            #071840 32%,
            #0A1F55 62%,
            #0D2A6A 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: clamp(2rem, 4vw, 4.5rem);
        color: #fff;
    }

    .sgd-grid-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 56px 56px;
        pointer-events: none;
    }

    .sgd-login-visual::before {
        content: '';
        position: absolute;
        top: -18%;
        right: -14%;
        width: 46vw;
        height: 46vw;
        max-width: 620px;
        max-height: 620px;
        border-radius: 50%;
        background: radial-gradient(circle,
            rgba(232,135,26,0.24) 0%,
            rgba(232,135,26,0.08) 42%,
            transparent 72%);
        pointer-events: none;
        animation: sgdGlow 10s ease-in-out infinite alternate;
    }

    .sgd-login-visual::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -12%;
        width: 34vw;
        height: 34vw;
        max-width: 460px;
        max-height: 460px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,80,160,0.45) 0%, transparent 65%);
        pointer-events: none;
        animation: sgdGlow 13s 2.5s ease-in-out infinite alternate-reverse;
    }

    .sgd-visual-brand,
    .sgd-visual-message,
    .sgd-visual-pillars {
        position: relative;
        z-index: 1;
    }

    .sgd-visual-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: sgdSlideDown 0.55s cubic-bezier(0.16,1,0.3,1) both;
    }

    .sgd-visual-brand img {
        height: 34px;
        width: auto;
        filter: brightness(0) invert(1);
        opacity: 0.95;
    }

    .sgd-visual-brand span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.8125rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.55);
        border-left: 1px solid rgba(255,255,255,0.2);
        padding-left: 0.75rem;
    }

    .sgd-visual-message {
        max-width: 30rem;
        animation: sgdFadeUp 0.6s 0.15s cubic-bezier(0.16,1,0.3,1) both;
    }

    .sgd-visual-message h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: clamp(1.75rem, 3vw, 2.75rem);
        letter-spacing: -0.02em;
        line-height: 1.12;
        margin: 0 0 1rem;
        text-wrap: balance;
    }

    .sgd-visual-message p {
        font-size: 0.9375rem;
        line-height: 1.6;
        color: rgba(226,235,250,0.72);
        margin: 0;
        max-width: 26rem;
    }

    .sgd-visual-accent-line {
        width: 3rem;
        height: 3px;
        background: linear-gradient(90deg, var(--sgd-orange), var(--sgd-gold));
        border-radius: 2px;
        margin-bottom: 1.25rem;
        box-shadow: 0 0 12px rgba(232,135,26,0.5);
    }

    /* ── Pilares DICACOCO como elemento visual ── */
    .sgd-visual-pillars {
        display: flex;
        gap: clamp(1rem, 2vw, 2rem);
        animation: sgdFadeUp 0.6s 0.35s cubic-bezier(0.16,1,0.3,1) both;
    }

    .sgd-pillar {
        flex: 1;
        min-width: 0;
        padding: 0.9rem 0.75rem;
        border-radius: 0.75rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
    }

    .sgd-pillar__letter {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.125rem;
        color: var(--sgd-gold);
        line-height: 1;
        margin-bottom: 0.35rem;
    }

    .sgd-pillar__label {
        font-size: 0.6875rem;
        font-weight: 600;
        line-height: 1.3;
        color: rgba(226,235,250,0.6);
    }

    /* ── Columna derecha: formulario ── */
    .sgd-login-form-col {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(2rem, 5vw, 4rem);
        background: #F7F9FC;
    }

    .sgd-login-form-wrap {
        width: 100%;
        max-width: 25rem;
        animation: sgdScaleIn 0.5s 0.1s cubic-bezier(0.16,1,0.3,1) both;
    }

    .sgd-form-header {
        margin-bottom: 2rem;
    }

    .sgd-form-header img {
        height: 30px;
        width: auto;
        margin-bottom: 1.5rem;
    }

    .sgd-form-header h2 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: -0.02em;
        color: var(--sgd-ink);
        margin: 0 0 0.35rem;
    }

    .sgd-form-header p {
        font-size: 0.875rem;
        color: var(--sgd-muted);
        margin: 0;
    }

    /* ── Textos del formulario Filament ── */
    .sgd-login-form-wrap label,
    .sgd-login-form-wrap .fi-fo-field-wrp-label,
    .sgd-login-form-wrap .fi-fo-field-wrp-label span,
    .sgd-login-form-wrap [class*="fi-fo"] label {
        color: var(--sgd-ink) !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 600 !important;
        font-size: 0.8125rem !important;
    }

    /* ── Inputs ── */
    .sgd-login-form-wrap input[type="email"],
    .sgd-login-form-wrap input[type="password"],
    .sgd-login-form-wrap input[type="text"] {
        background: #fff !important;
        border: 1.5px solid var(--sgd-border-strong) !important;
        color: var(--sgd-ink) !important;
        border-radius: 0.5rem !important;
        font-size: 0.9375rem !important;
        padding-block: 0.625rem !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }
    .sgd-login-form-wrap input::placeholder {
        color: #9AA7C2 !important;
    }
    .sgd-login-form-wrap input:focus,
    .sgd-login-form-wrap input:focus-visible {
        border-color: var(--sgd-orange) !important;
        box-shadow: 0 0 0 3px rgba(232,135,26,0.16) !important;
        outline: none !important;
    }

    .sgd-login-form-wrap [data-icon],
    .sgd-login-form-wrap .fi-input-suffix-item svg {
        color: #9AA7C2 !important;
    }

    /* ── Botón de acceso ── */
    .sgd-login-form-wrap .fi-btn,
    .sgd-login-form-wrap button[type="submit"] {
        background: linear-gradient(135deg, #C97315 0%, var(--sgd-orange) 45%, var(--sgd-gold) 100%) !important;
        border: none !important;
        border-radius: 0.5rem !important;
        color: #fff !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 700 !important;
        font-size: 0.9375rem !important;
        letter-spacing: 0.01em !important;
        box-shadow: 0 4px 16px rgba(232,135,26,0.35) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease !important;
    }
    .sgd-login-form-wrap .fi-btn:hover,
    .sgd-login-form-wrap button[type="submit"]:hover {
        filter: brightness(1.06) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 22px rgba(232,135,26,0.42) !important;
    }
    .sgd-login-form-wrap .fi-btn:active,
    .sgd-login-form-wrap button[type="submit"]:active {
        transform: translateY(0) !important;
        filter: brightness(0.96) !important;
    }

    /* ── Links ── */
    .sgd-login-form-wrap a {
        color: #C2670F !important;
        font-weight: 600 !important;
        transition: color 0.15s !important;
    }
    .sgd-login-form-wrap a:hover { color: var(--sgd-orange) !important; }

    /* ── Checkbox "Recuérdame" ── */
    .sgd-login-form-wrap .fi-checkbox-input {
        border-color: var(--sgd-border-strong) !important;
        background: #fff !important;
    }
    .sgd-login-form-wrap .fi-checkbox-input:checked {
        background-color: var(--sgd-orange) !important;
        border-color: var(--sgd-orange) !important;
    }

    /* ── Mensajes de error ── */
    .sgd-login-form-wrap .fi-fo-field-wrp-error-message,
    .sgd-login-form-wrap [class*="error"] p {
        color: #C0362C !important;
        font-size: 0.8125rem !important;
    }
    .sgd-login-form-wrap input.fi-input-invalid,
    .sgd-login-form-wrap input[aria-invalid="true"] {
        border-color: #E0574A !important;
        box-shadow: 0 0 0 3px rgba(224,87,74,0.14) !important;
    }

    /* ── Keyframes ── */
    @keyframes sgdGlow {
        from { opacity: 1;   transform: translate(0,0)   scale(1); }
        to   { opacity: 0.6; transform: translate(2%,2%) scale(1.05); }
    }
    @keyframes sgdSlideDown {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes sgdScaleIn {
        from { opacity: 0; transform: scale(0.97) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .sgd-login-visual::before, .sgd-login-visual::after { animation: none !important; }
        .sgd-visual-brand, .sgd-visual-message, .sgd-visual-pillars, .sgd-login-form-wrap {
            animation: none !important; opacity: 1 !important; transform: none !important;
        }
    }
    </style>

    {{-- Columna izquierda: panel visual de marca --}}
    <div class="sgd-login-visual">
        <div class="sgd-grid-overlay" aria-hidden="true"></div>

        <div class="sgd-visual-brand">
            <img src="{{ asset('images/confipetrol-logo-white.png') }}" alt="Confipetrol S.A.">
            <span>SGD DICACOCU</span>
        </div>

        <div class="sgd-visual-message">
            <div class="sgd-visual-accent-line"></div>
            <h1>Disciplina Operativa, respaldada por la gestión documental.</h1>
            <p>Accede al sistema de gestión documental de Confipetrol para consultar, verificar y divulgar los procedimientos que sostienen cada operación en campo.</p>
        </div>

        <div class="sgd-visual-pillars">
            @foreach([['DI','Disponibilidad'],['CA','Calidad'],['CO','Comunicación'],['CU','Cumplimiento']] as [$l,$n])
            <div class="sgd-pillar">
                <div class="sgd-pillar__letter">{{ $l }}</div>
                <div class="sgd-pillar__label">{{ $n }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Columna derecha: formulario --}}
    <div class="sgd-login-form-col">
        <div class="sgd-login-form-wrap">
            <div class="sgd-form-header">
                <img src="{{ asset('images/confipetrol-logo.png') }}" alt="Confipetrol S.A.">
                <h2>Entre a su cuenta</h2>
                <p>Sistema de Gestión Documental</p>
            </div>

            {{ $this->content }}
        </div>
    </div>
</div>
