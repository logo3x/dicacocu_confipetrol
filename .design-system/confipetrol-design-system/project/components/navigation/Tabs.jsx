import React from 'react';

/**
 * Underline tabs. Controlled (`value`/`onChange`) or uncontrolled (`defaultValue`).
 */
export function Tabs({ tabs = [], value, defaultValue, onChange, style = {} }) {
  const [internal, setInternal] = React.useState(defaultValue ?? (tabs[0] && tabs[0].id));
  const active = value !== undefined ? value : internal;
  const select = (id) => { if (value === undefined) setInternal(id); if (onChange) onChange(id); };

  return (
    <div style={{ borderBottom: '2px solid var(--border-subtle)', display: 'flex', gap: 'var(--space-5)', ...style }}>
      {tabs.map((t) => {
        const on = t.id === active;
        return (
          <button key={t.id} type="button" onClick={() => select(t.id)}
            style={{
              position: 'relative', border: 0, background: 'transparent', cursor: 'pointer',
              padding: '0 0 0.75rem', marginBottom: '-2px',
              fontFamily: 'var(--font-display)', fontSize: 'var(--fs-md)',
              fontWeight: on ? 'var(--fw-bold)' : 'var(--fw-medium)',
              color: on ? 'var(--color-primary)' : 'var(--text-muted)',
              display: 'inline-flex', alignItems: 'center', gap: '0.4rem',
              transition: 'color var(--dur-fast) var(--ease-out)',
            }}
            onMouseEnter={(e) => { if (!on) e.currentTarget.style.color = 'var(--text-strong)'; }}
            onMouseLeave={(e) => { if (!on) e.currentTarget.style.color = 'var(--text-muted)'; }}
          >
            {t.icon}{t.label}
            <span style={{
              position: 'absolute', left: 0, right: 0, bottom: '-2px', height: 3,
              borderRadius: 'var(--radius-pill)',
              background: on ? 'var(--color-accent)' : 'transparent',
              transition: 'background var(--dur-fast) var(--ease-out)',
            }} />
          </button>
        );
      })}
    </div>
  );
}
