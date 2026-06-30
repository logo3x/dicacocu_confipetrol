<footer class="cp-footer">
    <div class="cp-container cp-footer__grid">
        <div class="cp-footer__brand">
            <img src="{{ asset('images/confipetrol-logo-white.png') }}" alt="Confipetrol" height="36">
            <p>Sistema de Gestión Documental alineado al ciclo operacional DICACOCU de Ecopetrol S.A.</p>
            <div class="cp-footer__social">
                <a href="https://www.linkedin.com/company/confipetrol" aria-label="LinkedIn" target="_blank" rel="noopener">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                <a href="#" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </div>
        </div>

        <div class="cp-footer__col">
            <div class="cp-footer__heading">Sistema</div>
            <a href="{{ route('landing') }}">Inicio</a>
            <a href="{{ route('landing.sgd') }}">¿Qué es el SGD?</a>
            <a href="{{ route('landing.dicacocu') }}">Ciclo DICACOCU</a>
            <a href="{{ route('filament.admin.auth.login') }}">Acceder al sistema</a>
        </div>

        <div class="cp-footer__col">
            <div class="cp-footer__heading">Compañía</div>
            <a href="https://confipetrol.com" target="_blank" rel="noopener">Confipetrol</a>
            <a href="https://confipetrol.com/sobre-nosotros/" target="_blank" rel="noopener">Nosotros</a>
            <a href="https://confipetrol.com" target="_blank" rel="noopener">Servicios</a>
        </div>

        <div class="cp-footer__col">
            <div class="cp-footer__heading">Contacto</div>
            <span><i class="fa-solid fa-location-dot"></i> Bogotá, Colombia</span>
            <span><i class="fa-solid fa-envelope"></i> sgd@confipetrol.com</span>
        </div>
    </div>

    <div class="cp-footer__bottom">
        <div class="cp-container cp-footer__bottom-inner">
            <span>© {{ date('Y') }} Confipetrol · Parte de Grupo Protexa</span>
            <div class="cp-footer__legal">
                <a href="#">Política de privacidad</a>
                <a href="#">Política de cookies</a>
            </div>
        </div>
    </div>
</footer>

<style>
.cp-footer {
    background: var(--cp-blue-900);
    color: #fff;
    margin-top: 0;
}

.cp-footer__grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr 1fr;
    gap: 2.5rem;
    padding-block: 3.5rem 2rem;
}

.cp-footer__brand img { display: block; margin-bottom: 0.875rem; }
.cp-footer__brand p {
    color: rgba(255,255,255,0.65);
    font-size: 0.875rem;
    line-height: 1.6;
    max-width: 280px;
    margin: 0 0 1rem;
}

.cp-footer__social {
    display: flex;
    gap: 0.625rem;
}
.cp-footer__social a {
    width: 36px; height: 36px;
    border-radius: var(--radius-md);
    background: rgba(255,255,255,0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.8);
    font-size: 0.875rem;
    transition: background var(--dur-fast);
}
.cp-footer__social a:hover { background: var(--cp-orange-500); color: #fff; }

.cp-footer__col {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}
.cp-footer__heading {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.8125rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--cp-orange-400);
    margin-bottom: 0.25rem;
}
.cp-footer__col a,
.cp-footer__col span {
    color: rgba(255,255,255,0.72);
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: color var(--dur-fast);
}
.cp-footer__col a:hover { color: #fff; }
.cp-footer__col i { color: var(--cp-orange-400); font-size: 0.875rem; }

.cp-footer__bottom {
    border-top: 1px solid rgba(255,255,255,0.12);
}
.cp-footer__bottom-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-block: 1.125rem;
    font-size: 0.8125rem;
    color: rgba(255,255,255,0.55);
}
.cp-footer__legal { display: flex; gap: 1.125rem; }
.cp-footer__legal a {
    color: rgba(255,255,255,0.55);
    transition: color var(--dur-fast);
}
.cp-footer__legal a:hover { color: rgba(255,255,255,0.9); }

@media (max-width: 900px) {
    .cp-footer__grid { grid-template-columns: 1fr 1fr; }
    .cp-footer__brand { grid-column: 1 / -1; }
}
@media (max-width: 540px) {
    .cp-footer__grid { grid-template-columns: 1fr; }
}
</style>
