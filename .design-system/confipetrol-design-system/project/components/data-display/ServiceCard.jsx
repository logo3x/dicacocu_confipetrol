import React from 'react';
import { Card } from './Card.jsx';

/**
 * Service tile used across confipetrol.com (Mantenimiento, Overhaul, Paradas de planta…).
 * Icon + title + short blurb + "Conócelo →" affordance, lifts on hover.
 */
export function ServiceCard({ icon, title, description, href = '#', cta = 'Conócelo', style = {} }) {
  const [hover, setHover] = React.useState(false);
  return (
    <a href={href} style={{ textDecoration: 'none', display: 'block' }}
       onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}>
      <Card hoverable padded style={{ height: '100%', ...style }}>
        <div style={{
          width: 56, height: 56, borderRadius: 'var(--radius-md)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          background: hover ? 'var(--color-accent)' : 'var(--cp-blue-50)',
          color: hover ? '#fff' : 'var(--color-primary)',
          fontSize: '1.5rem', marginBottom: 'var(--space-4)',
          transition: 'all var(--dur-base) var(--ease-out)',
        }}>{icon}</div>
        <h4 style={{ margin: '0 0 0.5rem', fontFamily: 'var(--font-display)', fontWeight: 'var(--fw-bold)',
          fontSize: 'var(--fs-xl)', color: 'var(--text-strong)' }}>{title}</h4>
        <p style={{ margin: '0 0 var(--space-4)', color: 'var(--text-muted)', fontSize: 'var(--fs-sm)',
          lineHeight: 'var(--lh-normal)' }}>{description}</p>
        <span style={{ fontFamily: 'var(--font-display)', fontWeight: 'var(--fw-semibold)',
          fontSize: 'var(--fs-sm)', color: 'var(--color-accent)', display: 'inline-flex', alignItems: 'center', gap: '0.4rem' }}>
          {cta} <span style={{ transform: hover ? 'translateX(4px)' : 'none', transition: 'transform var(--dur-base) var(--ease-out)' }}>→</span>
        </span>
      </Card>
    </a>
  );
}
