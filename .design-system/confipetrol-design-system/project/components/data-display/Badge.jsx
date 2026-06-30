import React from 'react';

/**
 * Small status / category label. Tones map to brand + semantic colors.
 */
export function Badge({ children, tone = 'neutral', solid = false, style = {} }) {
  const tones = {
    neutral: { soft: ['var(--cp-gray-100)', 'var(--cp-gray-700)'], solid: ['var(--cp-gray-600)', '#fff'] },
    brand:   { soft: ['var(--cp-blue-50)', 'var(--cp-blue-700)'],  solid: ['var(--color-primary)', '#fff'] },
    accent:  { soft: ['var(--cp-orange-50)', 'var(--cp-orange-700)'], solid: ['var(--color-accent)', '#fff'] },
    success: { soft: ['var(--cp-success-50)', 'var(--cp-success-700)'], solid: ['var(--color-success)', '#fff'] },
    warning: { soft: ['var(--cp-warning-50)', '#9a6a10'], solid: ['var(--color-warning)', '#3a2a05'] },
    danger:  { soft: ['var(--cp-danger-50)', '#a13030'], solid: ['var(--color-danger)', '#fff'] },
  };
  const [bg, fg] = (tones[tone] || tones.neutral)[solid ? 'solid' : 'soft'];
  return (
    <span style={{
      display: 'inline-flex', alignItems: 'center', gap: '0.35rem',
      padding: '0.2rem 0.6rem', background: bg, color: fg,
      fontFamily: 'var(--font-display)', fontSize: 'var(--fs-2xs)', fontWeight: 'var(--fw-semibold)',
      letterSpacing: '0.03em', textTransform: 'uppercase',
      borderRadius: 'var(--radius-pill)', lineHeight: 1.4, whiteSpace: 'nowrap', ...style,
    }}>{children}</span>
  );
}
