import React from 'react';

/**
 * Surface container. `elevated` adds shadow; `accentTop` adds the orange brand bar.
 */
export function Card({
  children, elevated = false, accentTop = false, padded = true,
  hoverable = false, style = {}, ...rest
}) {
  const [hover, setHover] = React.useState(false);
  const base = {
    position: 'relative', background: 'var(--surface-card)',
    border: '1px solid var(--border-subtle)', borderRadius: 'var(--radius-lg)',
    overflow: 'hidden',
    padding: padded ? 'var(--space-5)' : 0,
    boxShadow: hover && hoverable ? 'var(--shadow-lg)' : elevated ? 'var(--shadow-md)' : 'var(--shadow-xs)',
    transform: hover && hoverable ? 'translateY(-3px)' : 'none',
    transition: 'box-shadow var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out)',
    ...style,
  };
  return (
    <div style={base}
      onMouseEnter={() => hoverable && setHover(true)}
      onMouseLeave={() => hoverable && setHover(false)}
      {...rest}
    >
      {accentTop && (
        <span style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 4, background: 'var(--color-accent)' }} />
      )}
      {children}
    </div>
  );
}
