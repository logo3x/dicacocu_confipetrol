<x-layouts.landing title="Contacto" description="Contacta al equipo de soporte del SGD DICACOCU — Confipetrol">

    <section style="padding-block: 5rem; background: var(--surface-page);">
        <div class="cp-container">
            <p class="cp-eyebrow">Soporte</p>
            <h1 style="font-size: clamp(2rem,4vw,3rem); letter-spacing:-0.02em; margin: 0.5rem 0 1.5rem;">Contacto</h1>
            <div class="cp-accent-rule"></div>
            <p style="font-size:1.125rem; color:var(--text-muted); margin-top:1.5rem; max-width:520px; line-height:1.7;">
                Para soporte o solicitud de acceso al SGD DICACOCU, escríbenos a:
                <a href="mailto:sgd@confipetrol.com" style="color:var(--color-primary); font-weight:600;">sgd@confipetrol.com</a>
            </p>
            <div style="margin-top:2.5rem; display:flex; gap:1rem; flex-wrap:wrap;">
                <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--primary cp-btn--md">
                    <i class="fa-solid fa-right-to-bracket"></i> Acceder al sistema
                </a>
                <a href="{{ route('landing') }}" class="cp-btn cp-btn--ghost cp-btn--md">
                    <i class="fa-solid fa-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </div>
    </section>

</x-layouts.landing>
