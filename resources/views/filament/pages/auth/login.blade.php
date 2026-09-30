<div class="sgd-acceso">
    <style>
    /* ============================================================
       ACCESO — SGD DICACOCU
       Mismo criterio que la página pública: jerarquía por tipografía
       y espacio, sin movimiento decorativo.
       ============================================================ */

    html, body { height: 100%; }

    body {
        margin: 0;
        font-family: 'Source Sans 3', ui-sans-serif, system-ui, sans-serif;
        color: #232830;
        background: #ffffff;
    }

    .sgd-acceso {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
        min-height: 100vh;
    }

    /* ── Columna de marca ── */
    .sgd-marca {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 3rem;
        padding: clamp(2.5rem, 5vw, 4rem);
        background: #08336a;
        color: #ffffff;
        overflow: hidden;
    }

    .sgd-marca__logo {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .sgd-marca__logo img { height: 34px; width: auto; }

    .sgd-marca__sistema {
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        font-size: .8125rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #92bbe2;
        padding-left: 1rem;
        border-left: 1px solid rgba(255, 255, 255, .22);
    }

    .sgd-marca__regla {
        width: 56px;
        height: 4px;
        background: #f58a1f;
        border-radius: 999px;
        margin-bottom: 1.75rem;
    }

    .sgd-marca__titulo {
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 700;
        line-height: 1.18;
        letter-spacing: -.02em;
        color: #ffffff;
        margin: 0 0 1.25rem;
        max-width: 18ch;
    }

    .sgd-marca__desc {
        font-size: 1rem;
        line-height: 1.65;
        color: #c7ddf1;
        max-width: 46ch;
        margin: 0;
    }

    /* Etapas del ciclo */
    .sgd-etapas {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 12px;
        overflow: hidden;
    }

    .sgd-etapa {
        background: #08336a;
        padding: 1rem .875rem;
    }

    .sgd-etapa__sigla {
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        font-size: 1.125rem;
        font-weight: 800;
        color: #f8af4f;
        letter-spacing: -.01em;
    }

    .sgd-etapa__nombre {
        display: block;
        margin-top: .25rem;
        font-size: .75rem;
        line-height: 1.3;
        color: #92bbe2;
    }

    /* ── Columna del formulario ── */
    .sgd-formulario {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(2rem, 5vw, 4rem) clamp(1.5rem, 4vw, 3rem);
        background: #f6f7f9;
    }

    .sgd-formulario__caja {
        width: 100%;
        max-width: 25rem;
    }

    .sgd-formulario__encabezado { margin-bottom: 2rem; }

    .sgd-formulario__encabezado img {
        height: 30px;
        width: auto;
        margin-bottom: 1.75rem;
    }

    .sgd-formulario__titulo {
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -.015em;
        color: #0a2950;
        margin: 0 0 .375rem;
    }

    .sgd-formulario__sub {
        font-size: .9375rem;
        color: #6e7785;
        margin: 0;
    }

    /* Aviso de acceso denegado */
    .sgd-aviso {
        display: flex;
        align-items: flex-start;
        gap: .625rem;
        padding: .8125rem .9375rem;
        margin-bottom: 1.5rem;
        border: 1px solid #fbcb8b;
        border-left: 3px solid #f58a1f;
        border-radius: 8px;
        background: #fff5e9;
        color: #8c470c;
        font-size: .875rem;
        line-height: 1.55;
    }

    .sgd-aviso i { margin-top: .1875rem; color: #db7510; flex-shrink: 0; }

    /* Acceso con la cuenta institucional */
    .sgd-institucional {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .625rem;
        width: 100%;
        padding: .75rem 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #dde2e8;
        border-radius: 8px;
        background: #ffffff;
        color: #363d48;
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        font-size: .9375rem;
        font-weight: 600;
        text-decoration: none;
        transition: background-color .14s ease, border-color .14s ease;
    }

    .sgd-institucional:hover {
        background: #f6f7f9;
        border-color: #c4cad3;
        color: #363d48;
    }

    .sgd-institucional:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(45, 118, 190, .35);
    }

    .sgd-separador {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.5rem;
        font-size: .8125rem;
        color: #9aa3b0;
    }

    .sgd-separador::before,
    .sgd-separador::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #dde2e8;
    }

    /* ── Formulario de Filament ── */
    .sgd-formulario__caja .fi-fo-field-wrp-label,
    .sgd-formulario__caja .fi-fo-field-wrp-label span,
    .sgd-formulario__caja label {
        color: #363d48 !important;
        font-weight: 600;
    }

    .sgd-formulario__caja .fi-input,
    .sgd-formulario__caja input[type='text'],
    .sgd-formulario__caja input[type='email'],
    .sgd-formulario__caja input[type='password'] {
        border-radius: 8px !important;
    }

    .sgd-formulario__caja .fi-btn-primary,
    .sgd-formulario__caja button[type='submit'] {
        background: #f58a1f !important;
        border-color: #f58a1f !important;
        font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: background-color .14s ease !important;
    }

    .sgd-formulario__caja .fi-btn-primary:hover,
    .sgd-formulario__caja button[type='submit']:hover {
        background: #db7510 !important;
        border-color: #db7510 !important;
    }

    /* ── Pantallas pequeñas ── */
    @media (max-width: 900px) {
        .sgd-acceso { grid-template-columns: 1fr; }

        .sgd-marca {
            gap: 2rem;
            padding: 2rem 1.5rem 2.5rem;
        }

        .sgd-marca__titulo { font-size: 1.5rem; max-width: none; }
        .sgd-marca__desc { display: none; }
        .sgd-etapas { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .sgd-etapa { padding: .75rem .5rem; text-align: center; }
        .sgd-etapa__nombre { display: none; }
    }

    @media (max-width: 480px) {
        .sgd-marca__sistema { font-size: .6875rem; }
    }
    </style>

    {{-- Marca --}}
    <section class="sgd-marca">
        <div class="sgd-marca__logo">
            <img src="{{ asset('images/confipetrol-logo-white.png') }}" alt="Confipetrol S.A.">
            <span class="sgd-marca__sistema">SGD DICACOCU</span>
        </div>

        <div>
            <div class="sgd-marca__regla"></div>
            <h1 class="sgd-marca__titulo">
                Los procedimientos que sostienen cada operación en campo.
            </h1>
            <p class="sgd-marca__desc">
                Registro, estandarización, divulgación y verificación de los procedimientos
                de Disciplina Operativa.
            </p>
        </div>

        <div class="sgd-etapas">
            @foreach ([['DI', 'Disponibilidad'], ['CA', 'Calidad'], ['CO', 'Comunicación'], ['CU', 'Cumplimiento']] as [$sigla, $nombre])
                <div class="sgd-etapa">
                    <span class="sgd-etapa__sigla">{{ $sigla }}</span>
                    <span class="sgd-etapa__nombre">{{ $nombre }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Formulario --}}
    <section class="sgd-formulario">
        <div class="sgd-formulario__caja">
            <header class="sgd-formulario__encabezado">
                <img src="{{ asset('images/confipetrol-logo.png') }}" alt="Confipetrol S.A.">
                <h2 class="sgd-formulario__titulo">Entre a su cuenta</h2>
                <p class="sgd-formulario__sub">Sistema de Gestión Documental</p>
            </header>

            @if ($errors->has('email'))
                <div class="sgd-aviso" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            @if (\App\Http\Controllers\Auth\AzureController::estaConfigurado())
                <a href="{{ route('auth.azure') }}" class="sgd-institucional">
                    <svg viewBox="0 0 21 21" width="17" height="17" aria-hidden="true">
                        <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                        <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                        <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                        <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                    </svg>
                    <span>Continuar con el correo institucional</span>
                </a>

                <div class="sgd-separador"><span>o ingrese con su contraseña</span></div>
            @endif

            {{ $this->content }}
        </div>
    </section>
</div>
