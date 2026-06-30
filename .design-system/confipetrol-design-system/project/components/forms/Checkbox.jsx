import React from 'react';

/** Checkbox with label. Controlled via `checked` + `onChange`. */
export function Checkbox({ label, checked = false, onChange, disabled = false, id, style = {} }) {
  const cid = id || (label ? 'cp-cb-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  return (
    <label htmlFor={cid} style={{
      display: 'inline-flex', alignItems: 'center', gap: '0.6rem', cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.5 : 1, fontFamily: 'var(--font-body)', fontSize: 'var(--fs-md)', color: 'var(--text-body)', ...style,
    }}>
      <span style={{
        width: 20, height: 20, flexShrink: 0,
        display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
        borderRadius: 'var(--radius-xs)',
        border: `2px solid ${checked ? 'var(--color-primary)' : 'var(--border-strong)'}`,
        background: checked ? 'var(--color-primary)' : 'var(--surface-card)',
        color: '#fff', transition: 'all var(--dur-fast) var(--ease-out)',
      }}>
        {checked && <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6.5L4.8 9.2L10 3.4" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/></svg>}
      </span>
      <input id={cid} type="checkbox" checked={checked} onChange={onChange} disabled={disabled}
        style={{ position: 'absolute', opacity: 0, width: 0, height: 0 }} />
      {label}
    </label>
  );
}
