import React from 'react';

/**
 * Inline message banner. Tone drives color + default icon.
 */
export function Alert({ tone = 'info', title, children, icon, onClose, style = {} }) {
  const tones = {
    info:    { bg: 'var(--cp-info-50)',    bar: 'var(--color-info)',    fg: 'var(--cp-blue-800)',  ic: 'fa-circle-info' },
    success: { bg: 'var(--cp-success-50)', bar: 'var(--color-success)', fg: 'var(--cp-success-700)', ic: 'fa-circle-check' },
    warning: { bg: 'var(--cp-warning-50)', bar: 'var(--color-warning)', fg: '#8a5e0e',              ic: 'fa-triangle-exclamation' },
    danger:  { bg: 'var(--cp-danger-50)',  bar: 'var(--color-danger)',  fg: '#9a2c2c',              ic: 'fa-circle-exclamation' },
  };
  const t = tones[tone] || tones.info;
  return (
    <div role="alert" style={{
      display: 'flex', gap: 'var(--space-3)', alignItems: 'flex-start',
      background: t.bg, borderLeft: `4px solid ${t.bar}`,
      borderRadius: 'var(--radius-md)', padding: 'var(--space-4)', color: t.fg, ...style,
    }}>
      <span style={{ color: t.bar, fontSize: '1.1rem', lineHeight: 1.4, flexShrink: 0 }}>
        {icon || <i className={`fa-solid ${t.ic}`}></i>}
      </span>
      <div style={{ flex: 1 }}>
        {title && <div style={{ fontFamily: 'var(--font-display)', fontWeight: 'var(--fw-bold)', fontSize: 'var(--fs-md)', marginBottom: children ? '0.2rem' : 0 }}>{title}</div>}
        {children && <div style={{ fontSize: 'var(--fs-sm)', color: 'var(--text-body)', lineHeight: 'var(--lh-normal)' }}>{children}</div>}
      </div>
      {onClose && (
        <button type="button" onClick={onClose} aria-label="Cerrar"
          style={{ border: 0, background: 'transparent', cursor: 'pointer', color: t.fg, opacity: 0.7, fontSize: '0.95rem' }}>
          <i className="fa-solid fa-xmark"></i>
        </button>
      )}
    </div>
  );
}
