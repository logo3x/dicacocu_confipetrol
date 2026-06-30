import React from 'react';

/**
 * Text input with label + optional helper / error / leading icon.
 */
export function Input({
  label, value, onChange, placeholder, type = 'text',
  helper, error, disabled = false, iconLeft = null,
  id, style = {}, ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const inputId = id || (label ? 'cp-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  const borderColor = error ? 'var(--color-danger)' : focus ? 'var(--border-focus)' : 'var(--border-default)';

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '0.35rem', ...style }}>
      {label && (
        <label htmlFor={inputId} style={{
          fontFamily: 'var(--font-display)', fontSize: 'var(--fs-sm)', fontWeight: 'var(--fw-semibold)',
          color: 'var(--text-strong)',
        }}>{label}</label>
      )}
      <div style={{
        display: 'flex', alignItems: 'center', gap: '0.5rem',
        background: disabled ? 'var(--surface-sunken)' : 'var(--surface-card)',
        border: `1px solid ${borderColor}`,
        boxShadow: focus ? 'var(--ring-focus)' : 'none',
        borderRadius: 'var(--radius-md)', padding: '0 0.85rem', height: '44px',
        transition: 'border-color var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)',
      }}>
        {iconLeft && <span style={{ color: 'var(--text-muted)', display: 'inline-flex' }}>{iconLeft}</span>}
        <input
          id={inputId} type={type} value={value} onChange={onChange}
          placeholder={placeholder} disabled={disabled}
          onFocus={() => setFocus(true)} onBlur={() => setFocus(false)}
          style={{
            border: 0, outline: 'none', background: 'transparent', flex: 1, height: '100%',
            fontFamily: 'var(--font-body)', fontSize: 'var(--fs-md)', color: 'var(--text-strong)',
          }}
          {...rest}
        />
      </div>
      {(helper || error) && (
        <span style={{ fontSize: 'var(--fs-xs)', color: error ? 'var(--color-danger)' : 'var(--text-muted)' }}>
          {error || helper}
        </span>
      )}
    </div>
  );
}
