import React from 'react';

/** Native select styled to match the brand field treatment. */
export function Select({ label, value, onChange, options = [], disabled = false, id, style = {}, ...rest }) {
  const [focus, setFocus] = React.useState(false);
  const selId = id || (label ? 'cp-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '0.35rem', ...style }}>
      {label && (
        <label htmlFor={selId} style={{
          fontFamily: 'var(--font-display)', fontSize: 'var(--fs-sm)', fontWeight: 'var(--fw-semibold)',
          color: 'var(--text-strong)',
        }}>{label}</label>
      )}
      <div style={{ position: 'relative', display: 'flex' }}>
        <select
          id={selId} value={value} onChange={onChange} disabled={disabled}
          onFocus={() => setFocus(true)} onBlur={() => setFocus(false)}
          style={{
            appearance: 'none', WebkitAppearance: 'none', width: '100%', height: '44px',
            padding: '0 2.5rem 0 0.85rem',
            background: disabled ? 'var(--surface-sunken)' : 'var(--surface-card)',
            border: `1px solid ${focus ? 'var(--border-focus)' : 'var(--border-default)'}`,
            boxShadow: focus ? 'var(--ring-focus)' : 'none',
            borderRadius: 'var(--radius-md)',
            fontFamily: 'var(--font-body)', fontSize: 'var(--fs-md)', color: 'var(--text-strong)',
            cursor: disabled ? 'not-allowed' : 'pointer', outline: 'none',
            transition: 'border-color var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)',
          }}
          {...rest}
        >
          {options.map((o) => {
            const val = typeof o === 'string' ? o : o.value;
            const lab = typeof o === 'string' ? o : o.label;
            return <option key={val} value={val}>{lab}</option>;
          })}
        </select>
        <span style={{
          position: 'absolute', right: '0.85rem', top: '50%', transform: 'translateY(-50%)',
          pointerEvents: 'none', color: 'var(--text-muted)', fontSize: '0.7rem',
        }}>▼</span>
      </div>
    </div>
  );
}
