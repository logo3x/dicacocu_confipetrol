import React from 'react';

/** Toggle switch. Controlled via `checked` + `onChange`. */
export function Switch({ label, checked = false, onChange, disabled = false, id, style = {} }) {
  const sid = id || (label ? 'cp-sw-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  const toggle = () => { if (!disabled && onChange) onChange(!checked); };
  return (
    <label htmlFor={sid} style={{
      display: 'inline-flex', alignItems: 'center', gap: '0.6rem', cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.5 : 1, fontFamily: 'var(--font-body)', fontSize: 'var(--fs-md)', color: 'var(--text-body)', ...style,
    }}>
      <button
        id={sid} type="button" role="switch" aria-checked={checked} disabled={disabled} onClick={toggle}
        style={{
          width: 44, height: 26, flexShrink: 0, padding: 3, border: 0, cursor: 'inherit',
          borderRadius: 'var(--radius-pill)',
          background: checked ? 'var(--color-accent)' : 'var(--cp-gray-300)',
          display: 'inline-flex', justifyContent: checked ? 'flex-end' : 'flex-start',
          transition: 'background var(--dur-base) var(--ease-out)',
        }}
      >
        <span style={{
          width: 20, height: 20, borderRadius: '50%', background: '#fff',
          boxShadow: 'var(--shadow-sm)', transition: 'all var(--dur-base) var(--ease-out)',
        }} />
      </button>
      {label}
    </label>
  );
}
