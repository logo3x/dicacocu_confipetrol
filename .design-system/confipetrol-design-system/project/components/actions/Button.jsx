import React from 'react';

/**
 * Confipetrol Button — primary action component.
 * Variants map to the brand: `primary` = corporate blue, `accent` = orange CTA.
 */
export function Button({
  children,
  variant = 'primary',
  size = 'md',
  block = false,
  disabled = false,
  iconLeft = null,
  iconRight = null,
  type = 'button',
  onClick,
  style = {},
  ...rest
}) {
  const sizes = {
    sm: { fontSize: 'var(--fs-sm)',  padding: '0.4rem 0.85rem', gap: '0.4rem',  height: '34px' },
    md: { fontSize: 'var(--fs-md)',  padding: '0.6rem 1.25rem', gap: '0.5rem',  height: '44px' },
    lg: { fontSize: 'var(--fs-lg)',  padding: '0.8rem 1.75rem', gap: '0.6rem',  height: '54px' },
  };

  const variants = {
    primary: {
      background: 'var(--color-primary)', color: 'var(--text-on-brand)',
      border: '1px solid var(--color-primary)',
      '--hover-bg': 'var(--color-primary-hover)', '--active-bg': 'var(--color-primary-active)',
    },
    accent: {
      background: 'var(--color-accent)', color: 'var(--text-on-brand)',
      border: '1px solid var(--color-accent)',
      '--hover-bg': 'var(--color-accent-hover)', '--active-bg': 'var(--color-accent-active)',
    },
    secondary: {
      background: 'var(--surface-card)', color: 'var(--color-primary)',
      border: '1px solid var(--border-default)',
      '--hover-bg': 'var(--cp-blue-50)', '--active-bg': 'var(--cp-blue-100)',
    },
    ghost: {
      background: 'transparent', color: 'var(--color-primary)',
      border: '1px solid transparent',
      '--hover-bg': 'var(--cp-blue-50)', '--active-bg': 'var(--cp-blue-100)',
    },
    danger: {
      background: 'var(--color-danger)', color: 'var(--text-on-brand)',
      border: '1px solid var(--color-danger)',
      '--hover-bg': '#bd3a3a', '--active-bg': '#a13030',
    },
  };

  const v = variants[variant] || variants.primary;
  const s = sizes[size] || sizes.md;

  const base = {
    display: block ? 'flex' : 'inline-flex',
    width: block ? '100%' : 'auto',
    alignItems: 'center', justifyContent: 'center',
    gap: s.gap, height: s.height, padding: s.padding,
    fontFamily: 'var(--font-display)', fontSize: s.fontSize, fontWeight: 'var(--fw-semibold)',
    letterSpacing: '0.01em', lineHeight: 1,
    borderRadius: 'var(--radius-md)', cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.5 : 1,
    transition: 'background var(--dur-fast) var(--ease-out), transform var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)',
    ...v, ...style,
  };

  const onEnter = (e) => { if (!disabled) e.currentTarget.style.background = v['--hover-bg']; };
  const onLeave = (e) => { if (!disabled) e.currentTarget.style.background = v.background; };
  const onDown  = (e) => { if (!disabled) { e.currentTarget.style.background = v['--active-bg']; e.currentTarget.style.transform = 'translateY(1px)'; } };
  const onUp    = (e) => { if (!disabled) { e.currentTarget.style.background = v['--hover-bg']; e.currentTarget.style.transform = 'none'; } };

  return (
    <button
      type={type} disabled={disabled} onClick={onClick} style={base}
      onMouseEnter={onEnter} onMouseLeave={onLeave} onMouseDown={onDown} onMouseUp={onUp}
      {...rest}
    >
      {iconLeft}
      {children}
      {iconRight}
    </button>
  );
}
