// Service detail page — e.g. Mantenimiento. Hero banner + intro + capability tabs + CTA.
function ServiceDetail({ go }) {
  const { Button, Tabs, Card, Badge, Stat } = window.ConfipetrolDesignSystem_9a2081;
  const PhotoPlaceholder = window.PhotoPlaceholder;
  const wrap = { maxWidth: 1240, margin: '0 auto', padding: '0 28px' };
  const eyebrow = { fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 14, letterSpacing: '0.08em',
    textTransform: 'uppercase', color: 'var(--color-accent)', margin: '0 0 12px' };
  const [tab, setTab] = React.useState('mecanico');

  const capabilities = {
    mecanico: ['Mantenimiento de equipo rotativo y estático', 'Alineación láser y balanceo dinámico', 'Inspección de integridad mecánica'],
    electrico: ['Mantenimiento de subestaciones y tableros', 'Termografía y análisis de motores', 'Instrumentación y control'],
    predictivo: ['Análisis de vibraciones y aceite', 'Monitoreo de condición (CBM)', 'Planes basados en confiabilidad (RCM)'],
  };

  return (
    <main>
      {/* breadcrumb + hero */}
      <section style={{ background: 'linear-gradient(120deg, var(--cp-blue-900), var(--cp-blue-700))', color: '#fff' }}>
        <div style={{ ...wrap, padding: '24px 28px 0', fontSize: 13.5, color: 'rgba(255,255,255,0.7)', fontFamily: 'var(--font-display)' }}>
          <span style={{ cursor: 'pointer' }} onClick={() => go('home')}>Inicio</span>
          <span style={{ margin: '0 8px', opacity: 0.5 }}>/</span>
          <span style={{ cursor: 'pointer' }} onClick={() => go('services')}>Servicios</span>
          <span style={{ margin: '0 8px', opacity: 0.5 }}>/</span>
          <span style={{ color: 'var(--cp-orange-400)' }}>Mantenimiento</span>
        </div>
        <div style={{ ...wrap, padding: '40px 28px 60px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 48, alignItems: 'center' }}>
          <div>
            <p style={{ ...eyebrow, color: 'var(--cp-orange-400)' }}>Servicio</p>
            <h1 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 46, lineHeight: 1.05, letterSpacing: '-0.02em', color: '#fff', margin: '0 0 16px' }}>
              Mantenimiento industrial</h1>
            <p style={{ fontSize: 18, lineHeight: 1.6, color: 'rgba(255,255,255,0.85)', maxWidth: 480, margin: '0 0 26px' }}>
              Mantenimiento integral para prolongar la vida útil de infraestructuras críticas y asegurar su
              funcionamiento óptimo, con seguridad y confiabilidad.</p>
            <Button variant="accent" size="lg" onClick={() => go('contact')}>Solicitar propuesta</Button>
          </div>
          <PhotoPlaceholder label="Imagen: técnicos en mantenimiento" h={300} icon="fa-screwdriver-wrench" />
        </div>
      </section>

      {/* capabilities */}
      <section style={{ background: 'var(--surface-card)' }}>
        <div style={{ ...wrap, padding: '72px 28px', display: 'grid', gridTemplateColumns: '0.9fr 1.1fr', gap: 56, alignItems: 'start' }}>
          <div>
            <p style={eyebrow}>Qué hacemos</p>
            <h2 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 30, letterSpacing: '-0.02em', color: 'var(--text-strong)', margin: '0 0 16px' }}>
              Cobertura técnica completa</h2>
            <p style={{ fontSize: 16, lineHeight: 1.7, color: 'var(--text-body)', margin: 0 }}>
              Nuestros equipos multidisciplinarios cubren el ciclo completo de mantenimiento mecánico, eléctrico
              y predictivo, aplicando técnicas de confiabilidad reconocidas internacionalmente.</p>
            <div style={{ display: 'flex', gap: 10, marginTop: 22, flexWrap: 'wrap' }}>
              <Badge tone="brand">ISO 55001</Badge>
              <Badge tone="brand">ISO 50001</Badge>
              <Badge tone="success">OSHA</Badge>
              <Badge tone="accent">RCM</Badge>
            </div>
          </div>
          <Card elevated accentTop>
            <Tabs value={tab} onChange={setTab} tabs={[
              { id: 'mecanico', label: 'Mecánico' },
              { id: 'electrico', label: 'Eléctrico' },
              { id: 'predictivo', label: 'Predictivo' },
            ]} />
            <ul style={{ listStyle: 'none', padding: 0, margin: '22px 0 0', display: 'flex', flexDirection: 'column', gap: 14 }}>
              {capabilities[tab].map((c) => (
                <li key={c} style={{ display: 'flex', gap: 12, alignItems: 'flex-start', fontSize: 16, color: 'var(--text-body)' }}>
                  <i className="fa-solid fa-circle-check" style={{ color: 'var(--color-success)', marginTop: 4 }}></i>{c}
                </li>
              ))}
            </ul>
          </Card>
        </div>
      </section>

      {/* outcome stats */}
      <section style={{ background: 'var(--surface-page)' }}>
        <div style={{ ...wrap, padding: '56px 28px', display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 28 }}>
          <Card><Stat value="99.2%" label="Disponibilidad" sublabel="promedio de planta" accent="brand" /></Card>
          <Card><Stat value="-18%" label="Costos de O&M" sublabel="vs. línea base del cliente" /></Card>
          <Card><Stat value="+82.5t" label="CO₂ reducido" sublabel="programa de eficiencia energética" accent="brand" /></Card>
        </div>
      </section>
    </main>
  );
}
window.ServiceDetail = ServiceDetail;
