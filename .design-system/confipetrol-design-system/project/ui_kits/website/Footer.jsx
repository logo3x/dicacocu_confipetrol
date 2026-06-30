// Site footer — dark blue, columns + social + legal.
function Footer({ go }) {
  const col = { display: 'flex', flexDirection: 'column', gap: 10 };
  const head = { fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 14, letterSpacing: '0.06em',
    textTransform: 'uppercase', color: 'var(--cp-orange-400)', marginBottom: 4 };
  const link = { color: 'rgba(255,255,255,0.78)', fontSize: 14.5, cursor: 'pointer' };
  return (
    <footer style={{ background: 'var(--cp-blue-900)', color: '#fff' }}>
      <div style={{ maxWidth: 1240, margin: '0 auto', padding: '56px 28px 28px',
        display: 'grid', gridTemplateColumns: '1.4fr 1fr 1fr 1fr', gap: 40 }}>
        <div style={col}>
          <img src="../../assets/confipetrol-logo-white.png" alt="Confipetrol" style={{ height: 36, alignSelf: 'flex-start' }} />
          <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 14, lineHeight: 1.6, maxWidth: 280, margin: '6px 0 0' }}>
            Líderes en operación y mantenimiento de activos industriales en Latinoamérica.
          </p>
          <div style={{ display: 'flex', gap: 10, marginTop: 8 }}>
            {['linkedin-in', 'facebook-f'].map((s) => (
              <span key={s} style={{ width: 36, height: 36, borderRadius: 8, background: 'rgba(255,255,255,0.1)',
                display: 'inline-flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer' }}>
                <i className={`fa-brands fa-${s}`}></i></span>
            ))}
          </div>
        </div>
        <div style={col}>
          <div style={head}>Servicios</div>
          {['Mantenimiento', 'Confiabilidad', 'Paradas de planta', 'Facility Management', 'Overhaul'].map((s) => (
            <span key={s} style={link} onClick={() => go('services')}>{s}</span>
          ))}
        </div>
        <div style={col}>
          <div style={head}>Compañía</div>
          {['Nosotros', 'Responsabilidad', 'Noticias', 'Documentación', 'Proveedores'].map((s) => (
            <span key={s} style={link}>{s}</span>
          ))}
        </div>
        <div style={col}>
          <div style={head}>Contacto</div>
          <span style={link}><i className="fa-solid fa-location-dot" style={{ color: 'var(--cp-orange-400)', marginRight: 8 }}></i>Bogotá, Colombia</span>
          <span style={link}><i className="fa-solid fa-phone" style={{ color: 'var(--cp-orange-400)', marginRight: 8 }}></i>+57 (1) 432 0000</span>
          <span style={link}><i className="fa-solid fa-envelope" style={{ color: 'var(--cp-orange-400)', marginRight: 8 }}></i>contacto@confipetrol.com</span>
        </div>
      </div>
      <div style={{ borderTop: '1px solid rgba(255,255,255,0.12)' }}>
        <div style={{ maxWidth: 1240, margin: '0 auto', padding: '18px 28px', display: 'flex',
          justifyContent: 'space-between', flexWrap: 'wrap', gap: 12, fontSize: 13, color: 'rgba(255,255,255,0.6)' }}>
          <span>© 2026 Confipetrol · Parte de Grupo Protexa</span>
          <span style={{ display: 'flex', gap: 18 }}>
            <span style={{ cursor: 'pointer' }}>Política de privacidad</span>
            <span style={{ cursor: 'pointer' }}>Política de cookies</span>
            <span style={{ cursor: 'pointer' }}>Sistema de denuncias</span>
          </span>
        </div>
      </div>
    </footer>
  );
}
window.Footer = Footer;
