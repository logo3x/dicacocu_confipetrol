import React from 'react';

/**
 * Icon-only square button. Pass an SVG/icon node as children.
 */
export function IconButton({
  children,
  variant = 'secondary',
  size = 'md',
  label,
  disabled = false,
  onClick,
  style = {},
  ...rest
}) {
  const sizes = { sm: 34, md: 44, lg: 54 };
  const dim = sizes[size] || sizes.md;

  const variants = {
    primary:   { background: 'var(--color-primary)', color: 'var(--text-on-brand)', border: '1px solid var(--color-primary)', hover: 'var(--color-primary-hover)' },
    accent:    { background: 'var(--color-accent)',  color: 'var(--text-on-brand)', border: '1px solid var(--color-accent)',  hover: 'var(--color-accent-hover)' },
    secondary: { background: 'var(--surface-card)',  color: 'var(--color-primary)', border: '1px solid var(--border-default)', hover: 'var(--cp-blue-50)' },
    ghost:     { background: 'transparent',          color: 'var(--text-body)',     border: '1px solid transparent',          hover: 'var(--cp-gray-100)' },
  };
  const v = variants[variant] || variants.secondary;

  const base = {
    display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
    width: dim, height: dim, padding: 0,
    borderRadius: 'var(--radius-md)', cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.5 : 1,
    transition: 'background var(--dur-fast) var(--ease-out)',
    ...v, ...style,
  };
  delete base.hover;

  return (
    <button
      type="button" aria-label={label} disabled={disabled} onClick={onClick} style={base}
      onMouseEnter={(e) => { if (!disabled) e.currentTarget.style.background = v.hover; }}
      onMouseLeave={(e) => { if (!disabled) e.currentTarget.style.background = v.background; }}
      {...rest}
    >
      {children}
    </button>
  );
}
