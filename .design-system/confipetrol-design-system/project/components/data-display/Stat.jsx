import React from 'react';

/**
 * Headline metric / KPI block. Confipetrol surfaces O&M results as big numbers
 * (CO₂ reduced, availability %, countries, years).
 */
export function Stat({ value, label, sublabel, icon = null, accent = 'accent', align = 'left', style = {} }) {
  const color = accent === 'brand' ? 'var(--color-primary)' : 'var(--color-accent)';
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '0.25rem', textAlign: align, ...style }}>
      {icon && <span style={{ color, fontSize: '1.4rem', marginBottom: '0.25rem' }}>{icon}</span>}
      <span style={{
        fontFamily: 'var(--font-display)', fontWeight: 'var(--fw-extra)',
        fontSize: 'var(--fs-4xl)', lineHeight: 1, color: 'var(--text-strong)',
        letterSpacing: 'var(--ls-tight)',
      }}>{value}</span>
      <span style={{
        fontFamily: 'var(--font-display)', fontWeight: 'var(--fw-semibold)',
        fontSize: 'var(--fs-md)', color: color,
      }}>{label}</span>
      {sublabel && <span style={{ fontSize: 'var(--fs-sm)', color: 'var(--text-muted)' }}>{sublabel}</span>}
    </div>
  );
}
