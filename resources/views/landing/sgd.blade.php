<x-layouts.landing title="Sistema SGD" description="Conoce el Sistema de Gestión Documental DICACOCU de Confipetrol">

    <section style="padding-block: 5rem; background: var(--surface-page);">
        <div class="cp-container">
            <p class="cp-eyebrow">El sistema</p>
            <h1 style="font-size: clamp(2rem,4vw,3rem); letter-spacing:-0.02em; margin: 0.5rem 0 1.5rem;">Sistema de Gestión Documental</h1>
            <div class="cp-accent-rule"></div>
            <p style="font-size:1.125rem; color:var(--text-muted); margin-top:1.5rem; max-width:640px; line-height:1.7;">
                El SGD DICACOCU es una plataforma privada de gestión documental diseñada para Confipetrol,
                alineada al ciclo operacional de Ecopetrol S.A. Centraliza, versiona y controla todos los
                documentos operacionales con trazabilidad completa.
            </p>
            <div style="margin-top:2.5rem;">
                <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--primary cp-btn--lg">
                    <i class="fa-solid fa-right-to-bracket"></i> Acceder al sistema
                </a>
            </div>
        </div>
    </section>

</x-layouts.landing>
