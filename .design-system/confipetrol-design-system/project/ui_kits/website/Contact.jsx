// Contact page — form (DS form controls) + contact info column.
function Contact({ go }) {
  const { Button, Input, Select, Checkbox, Card, Alert } = window.ConfipetrolDesignSystem_9a2081;
  const wrap = { maxWidth: 1240, margin: '0 auto', padding: '0 28px' };
  const eyebrow = { fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 14, letterSpacing: '0.08em',
    textTransform: 'uppercase', color: 'var(--color-accent)', margin: '0 0 12px' };
  const [sent, setSent] = React.useState(false);
  const [ok, setOk] = React.useState(false);

  return (
    <main style={{ background: 'var(--surface-page)' }}>
      <section>
        <div style={{ ...wrap, padding: '64px 28px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 56, alignItems: 'start' }}>
          <div>
            <p style={eyebrow}>Contáctanos</p>
            <h1 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 40, letterSpacing: '-0.02em', color: 'var(--text-strong)', margin: '0 0 16px' }}>
              Hablemos de tu operación</h1>
            <p style={{ fontSize: 17, lineHeight: 1.65, color: 'var(--text-body)', margin: '0 0 32px', maxWidth: 460 }}>
              Cuéntanos sobre tus activos y objetivos. Nuestro equipo te contactará para construir una propuesta a la medida.</p>

            <div style={{ display: 'flex', flexDirection: 'column', gap: 22 }}>
              {[['fa-location-dot', 'Sede principal', 'Cra. 15 No. 98-26, Of. 401 · Bogotá, Colombia'],
                ['fa-phone', 'Teléfono', '+57 (1) 432 0000'],
                ['fa-envelope', 'Correo', 'contacto@confipetrol.com']].map(([ic, t, v]) => (
                <div key={t} style={{ display: 'flex', gap: 16, alignItems: 'flex-start' }}>
                  <span style={{ width: 46, height: 46, borderRadius: 'var(--radius-md)', background: 'var(--cp-blue-50)',
                    color: 'var(--color-primary)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, flexShrink: 0 }}>
                    <i className={`fa-solid ${ic}`}></i></span>
                  <div>
                    <div style={{ fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 15, color: 'var(--text-strong)' }}>{t}</div>
                    <div style={{ fontSize: 15, color: 'var(--text-muted)' }}>{v}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <Card elevated accentTop style={{ padding: 'var(--space-7)' }}>
            {sent ? (
              <Alert tone="success" title="¡Gracias por escribirnos!">Hemos recibido tu solicitud. Te contactaremos en menos de 48 horas hábiles.</Alert>
            ) : (
              <form onSubmit={(e) => { e.preventDefault(); if (ok) setSent(true); }} style={{ display: 'flex', flexDirection: 'column', gap: 18 }}>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                  <Input label="Nombre" placeholder="Tu nombre" />
                  <Input label="Empresa" placeholder="Tu empresa" />
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                  <Input label="Correo corporativo" type="email" placeholder="nombre@empresa.com" iconLeft={<i className="fa-solid fa-envelope"></i>} />
                  <Select label="País" options={['Colombia', 'Perú', 'Chile', 'Bolivia', 'Ecuador', 'Venezuela']} />
                </div>
                <Select label="Servicio de interés" options={['Mantenimiento', 'Confiabilidad y gestión de activos', 'Paradas de planta', 'Facility Management', 'Overhaul', 'Otros']} />
                <Input label="Mensaje" placeholder="Cuéntanos sobre tu operación…" />
                <Checkbox label="Acepto la política de tratamiento de datos personales" checked={ok} onChange={(e) => setOk(e.target.checked)} />
                <Button variant="accent" size="lg" type="submit" block disabled={!ok}>Enviar solicitud</Button>
              </form>
            )}
          </Card>
        </div>
      </section>
    </main>
  );
}
window.Contact = Contact;
