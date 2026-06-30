// Top navigation bar — white, logo left, menu center, orange CTA right.
function Nav({ route, go }) {
  const { Button } = window.ConfipetrolDesignSystem_9a2081;
  const items = [
    ['home', 'Inicio'], ['about', 'Nosotros'], ['services', 'Servicios'],
    ['sustainability', 'Responsabilidad'], ['news', 'Noticias'],
  ];
  return (
    <header style={{
      position: 'sticky', top: 0, zIndex: 50, background: 'rgba(255,255,255,0.92)',
      backdropFilter: 'blur(10px)', borderBottom: '1px solid var(--border-subtle)',
    }}>
      {/* utility strip */}
      <div style={{ background: 'var(--cp-blue-800)', color: 'rgba(255,255,255,0.82)' }}>
        <div style={{ maxWidth: 1240, margin: '0 auto', padding: '6px 28px', display: 'flex',
          justifyContent: 'flex-end', gap: 20, fontSize: 12.5, fontFamily: 'var(--font-display)', fontWeight: 500 }}>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-earth-americas" style={{ color: 'var(--cp-orange-400)' }}></i> Global</span>
          <span style={{ opacity: 0.4 }}>|</span>
          <span>CO · PE · CL · BO · EC · VE</span>
          <span style={{ opacity: 0.4 }}>|</span>
          <span style={{ cursor: 'pointer' }}>ES</span><span style={{ opacity: 0.5, cursor: 'pointer' }}>EN</span>
        </div>
      </div>
      {/* main bar */}
      <div style={{ maxWidth: 1240, margin: '0 auto', padding: '14px 28px', display: 'flex',
        alignItems: 'center', justifyContent: 'space-between', gap: 24 }}>
        <a href="#" onClick={(e) => { e.preventDefault(); go('home'); }} style={{ display: 'flex' }}>
          <img src="../../assets/confipetrol-logo.png" alt="Confipetrol" style={{ height: 38 }} />
        </a>
        <nav style={{ display: 'flex', gap: 28 }}>
          {items.map(([id, label]) => (
            <a key={id} href="#" onClick={(e) => { e.preventDefault(); go(id); }}
              style={{
                fontFamily: 'var(--font-display)', fontSize: 15, fontWeight: 600,
                color: route === id ? 'var(--color-primary)' : 'var(--text-body)',
                position: 'relative', paddingBottom: 4,
                borderBottom: route === id ? '2px solid var(--color-accent)' : '2px solid transparent',
              }}>{label}</a>
          ))}
        </nav>
        <Button variant="accent" size="sm" onClick={() => go('contact')}
          iconLeft={<i className="fa-solid fa-phone"></i>}>Contáctanos</Button>
      </div>
    </header>
  );
}
window.Nav = Nav;
