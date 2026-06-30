// Homepage — hero, Somos, services grid, stats band, Confinoticias, sustainability CTA.
function PhotoPlaceholder({ label, h = 280, dark = true, icon = 'fa-industry' }) {
  return (
    <div style={{
      height: h, borderRadius: 'var(--radius-xl)', overflow: 'hidden', position: 'relative',
      background: dark
        ? 'linear-gradient(135deg, var(--cp-blue-800), var(--cp-blue-600))'
        : 'linear-gradient(135deg, var(--cp-gray-200), var(--cp-gray-100))',
      display: 'flex', alignItems: 'center', justifyContent: 'center',
    }}>
      <div style={{ position: 'absolute', inset: 0, opacity: 0.12,
        backgroundImage: 'radial-gradient(circle at 1px 1px, #fff 1.5px, transparent 0)', backgroundSize: '22px 22px' }} />
      <div style={{ textAlign: 'center', color: dark ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)', zIndex: 1 }}>
        <i className={`fa-solid ${icon}`} style={{ fontSize: 34, marginBottom: 10, display: 'block' }}></i>
        <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12, letterSpacing: '0.04em' }}>{label}</span>
      </div>
    </div>
  );
}

function Home({ go }) {
  const { Button, ServiceCard, Stat, Card, Badge } = window.ConfipetrolDesignSystem_9a2081;
  const wrap = { maxWidth: 1240, margin: '0 auto', padding: '0 28px' };
  const eyebrow = { fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 14, letterSpacing: '0.08em',
    textTransform: 'uppercase', color: 'var(--color-accent)', margin: '0 0 12px' };

  const services = [
    ['fa-screwdriver-wrench', 'Mantenimiento', 'Mantenimiento integral para prolongar la vida útil de infraestructuras críticas.'],
    ['fa-gauge-high', 'Confiabilidad y activos', 'Técnicas predictivas y CBM que maximizan la disponibilidad de tus equipos.'],
    ['fa-helmet-safety', 'Paradas de planta', 'Planeación y ejecución de paradas mayores con seguridad y cumplimiento.'],
    ['fa-building-shield', 'Facility Management', 'Gestión integral de instalaciones para operaciones eficientes y seguras.'],
    ['fa-gears', 'Overhaul', 'Reparación mayor de equipo rotativo y maquinaria crítica.'],
    ['fa-list-check', 'Otros servicios', 'Soluciones especializadas a la medida de cada sector industrial.'],
  ];
  const news = [
    ['Minería', 'success', '24.01.2025', 'Nueva adjudicación en Codelco Salvador', 'Consolidamos nuestra presencia en la industria minera en Chile.'],
    ['Reconocimiento', 'brand', '12.01.2025', 'Reconocimiento de Newmont Yanacocha', 'Por nuestra destacada labor en responsabilidad social y desarrollo de capacidades.'],
    ['Sostenibilidad', 'accent', '10.12.2024', 'Premio al Desarrollo Sostenible 2024', 'Reducimos más de 82.5 toneladas de CO₂ con un Sistema de Gestión de Energía ISO 50001.'],
  ];

  return (
    <main>
      {/* HERO */}
      <section style={{ background: 'linear-gradient(120deg, var(--cp-blue-900) 0%, var(--cp-blue-700) 60%, var(--cp-blue-600) 100%)', color: '#fff', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -120, right: -80, width: 520, height: 520, borderRadius: '50%',
          background: 'radial-gradient(circle, rgba(245,138,31,0.22), transparent 70%)' }} />
        <div style={{ ...wrap, padding: '88px 28px 96px', display: 'grid', gridTemplateColumns: '1.05fr 0.95fr', gap: 56, alignItems: 'center', position: 'relative' }}>
          <div>
            <p style={{ ...eyebrow, color: 'var(--cp-orange-400)' }}>Operación &amp; Mantenimiento</p>
            <h1 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 52, lineHeight: 1.04,
              letterSpacing: '-0.02em', color: '#fff', margin: '0 0 18px' }}>
              Una entidad,<br />un propósito,<br /><span style={{ color: 'var(--cp-orange-400)' }}>un equipo</span></h1>
            <p style={{ fontSize: 19, lineHeight: 1.6, color: 'rgba(255,255,255,0.85)', maxWidth: 460, margin: '0 0 30px' }}>
              Transformamos la industria con soluciones en operación y mantenimiento de clase mundial.</p>
            <div style={{ display: 'flex', gap: 14 }}>
              <Button variant="accent" size="lg" onClick={() => go('services')}>Ver servicios</Button>
              <Button variant="secondary" size="lg" iconLeft={<i className="fa-solid fa-play"></i>}
                style={{ background: 'rgba(255,255,255,0.1)', color: '#fff', border: '1px solid rgba(255,255,255,0.3)' }}>Ver video</Button>
            </div>
          </div>
          <PhotoPlaceholder label="Imagen: planta industrial / operación en campo" h={340} icon="fa-oil-well" />
        </div>
      </section>

      {/* SOMOS */}
      <section style={{ background: 'var(--surface-card)' }}>
        <div style={{ ...wrap, padding: '80px 28px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 56, alignItems: 'center' }}>
          <PhotoPlaceholder label="Imagen: equipo Confipetrol" h={300} dark={false} icon="fa-people-group" />
          <div>
            <p style={eyebrow}>Somos Confipetrol</p>
            <h2 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 34, letterSpacing: '-0.02em', color: 'var(--text-strong)', margin: '0 0 16px' }}>
              Aliado estratégico de la industria en Latinoamérica</h2>
            <p style={{ fontSize: 16.5, lineHeight: 1.7, color: 'var(--text-body)', margin: '0 0 24px' }}>
              Empresa multinacional especializada en la operación y mantenimiento de activos industriales para los
              sectores de minería, oil &amp; gas y energía. Garantizamos resultados medibles y sostenibles con
              tecnología de última generación y un equipo altamente capacitado.</p>
            <Button variant="primary" onClick={() => go('about')} iconRight={<span>→</span>}>Conócenos</Button>
          </div>
        </div>
      </section>

      {/* SERVICIOS */}
      <section style={{ background: 'var(--surface-page)' }}>
        <div style={{ ...wrap, padding: '80px 28px' }}>
          <div style={{ textAlign: 'center', marginBottom: 44 }}>
            <p style={eyebrow}>Nuestros servicios</p>
            <h2 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 34, letterSpacing: '-0.02em', color: 'var(--text-strong)', margin: 0 }}>
              Soluciones integrales de mantenimiento</h2>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 22 }}>
            {services.map(([icon, title, desc]) => (
              <ServiceCard key={title} icon={<i className={`fa-solid ${icon}`}></i>} title={title} description={desc}
                href="#" cta="Conócelo" />
            ))}
          </div>
        </div>
      </section>

      {/* STATS BAND */}
      <section style={{ background: 'var(--cp-blue-800)', color: '#fff' }}>
        <div style={{ ...wrap, padding: '56px 28px', display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 32 }}>
          {[['+20', 'Años de experiencia'], ['6', 'Países en Latam'], ['+5.000', 'Colaboradores'], ['99.2%', 'Disponibilidad de activos']].map(([v, l]) => (
            <div key={l} style={{ textAlign: 'center' }}>
              <div style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 46, lineHeight: 1, color: 'var(--cp-orange-400)' }}>{v}</div>
              <div style={{ fontSize: 15, color: 'rgba(255,255,255,0.82)', marginTop: 8 }}>{l}</div>
            </div>
          ))}
        </div>
      </section>

      {/* CONFINOTICIAS */}
      <section style={{ background: 'var(--surface-card)' }}>
        <div style={{ ...wrap, padding: '80px 28px' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: 36 }}>
            <div>
              <p style={eyebrow}>Confinoticias</p>
              <h2 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 32, letterSpacing: '-0.02em', color: 'var(--text-strong)', margin: 0 }}>
                Lo último de Confipetrol</h2>
            </div>
            <Button variant="ghost" onClick={() => go('news')}>Ver todas →</Button>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 22 }}>
            {news.map(([tag, tone, date, title, excerpt]) => (
              <Card key={title} padded={false} hoverable style={{ display: 'flex', flexDirection: 'column' }}>
                <PhotoPlaceholder label="Imagen de noticia" h={150} icon="fa-newspaper" />
                <div style={{ padding: 20, display: 'flex', flexDirection: 'column', gap: 10, flex: 1 }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <Badge tone={tone}>{tag}</Badge>
                    <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12, color: 'var(--text-muted)' }}>{date}</span>
                  </div>
                  <h4 style={{ fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 18, color: 'var(--text-strong)', margin: 0, lineHeight: 1.3 }}>{title}</h4>
                  <p style={{ fontSize: 14, color: 'var(--text-muted)', lineHeight: 1.55, margin: 0 }}>{excerpt}</p>
                </div>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* SOSTENIBILIDAD CTA */}
      <section style={{ background: 'var(--surface-page)' }}>
        <div style={{ ...wrap, padding: '0 28px 80px' }}>
          <div style={{ borderRadius: 'var(--radius-2xl)', overflow: 'hidden', position: 'relative',
            background: 'linear-gradient(110deg, var(--cp-blue-700), var(--cp-blue-900))', color: '#fff',
            padding: '52px 56px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 40 }}>
            <div style={{ position: 'absolute', right: -40, bottom: -60, width: 320, height: 320, borderRadius: '50%',
              background: 'radial-gradient(circle, rgba(245,138,31,0.25), transparent 70%)' }} />
            <div style={{ position: 'relative', maxWidth: 620 }}>
              <p style={{ ...eyebrow, color: 'var(--cp-orange-400)' }}>Sostenibilidad</p>
              <h2 style={{ fontFamily: 'var(--font-display)', fontWeight: 800, fontSize: 32, letterSpacing: '-0.02em', color: '#fff', margin: '0 0 12px' }}>
                Nuestro compromiso con un futuro sostenible</h2>
              <p style={{ fontSize: 16.5, lineHeight: 1.6, color: 'rgba(255,255,255,0.85)', margin: 0 }}>
                Descarga nuestro Reporte de Sostenibilidad y descubre cómo generamos impacto positivo en la industria y las comunidades.</p>
            </div>
            <div style={{ position: 'relative', flexShrink: 0 }}>
              <Button variant="accent" size="lg" iconLeft={<i className="fa-solid fa-file-arrow-down"></i>}>Ver reporte</Button>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
}
window.PhotoPlaceholder = PhotoPlaceholder;
window.Home = Home;
