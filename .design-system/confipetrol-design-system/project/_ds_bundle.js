/* @ds-bundle: {"format":3,"namespace":"ConfipetrolDesignSystem_9a2081","components":[{"name":"Button","sourcePath":"components/actions/Button.jsx"},{"name":"IconButton","sourcePath":"components/actions/IconButton.jsx"},{"name":"Badge","sourcePath":"components/data-display/Badge.jsx"},{"name":"Card","sourcePath":"components/data-display/Card.jsx"},{"name":"ServiceCard","sourcePath":"components/data-display/ServiceCard.jsx"},{"name":"Stat","sourcePath":"components/data-display/Stat.jsx"},{"name":"Alert","sourcePath":"components/feedback/Alert.jsx"},{"name":"Checkbox","sourcePath":"components/forms/Checkbox.jsx"},{"name":"Input","sourcePath":"components/forms/Input.jsx"},{"name":"Select","sourcePath":"components/forms/Select.jsx"},{"name":"Switch","sourcePath":"components/forms/Switch.jsx"},{"name":"Tabs","sourcePath":"components/navigation/Tabs.jsx"}],"sourceHashes":{"components/actions/Button.jsx":"c538f59eeceb","components/actions/IconButton.jsx":"956bf754660c","components/data-display/Badge.jsx":"b87bd39e700b","components/data-display/Card.jsx":"17f5ffac52b3","components/data-display/ServiceCard.jsx":"be7a5a07f4fe","components/data-display/Stat.jsx":"93f2c1bb6225","components/feedback/Alert.jsx":"61d1880de948","components/forms/Checkbox.jsx":"744c6ea8d3ff","components/forms/Input.jsx":"0ffea4315302","components/forms/Select.jsx":"765cb5d2aa25","components/forms/Switch.jsx":"acdaffb03021","components/navigation/Tabs.jsx":"a192de2a62e4","ui_kits/website/App.jsx":"8fbd4c89e65a","ui_kits/website/Contact.jsx":"594aef4a1434","ui_kits/website/Footer.jsx":"35479799a2f6","ui_kits/website/Home.jsx":"40d9892a8fbb","ui_kits/website/Nav.jsx":"dbebcf8055c1","ui_kits/website/ServiceDetail.jsx":"c28a6a62e7d6"},"inlinedExternals":[],"unexposedExports":[]} */

(() => {

const __ds_ns = (window.ConfipetrolDesignSystem_9a2081 = window.ConfipetrolDesignSystem_9a2081 || {});

const __ds_scope = {};

(__ds_ns.__errors = __ds_ns.__errors || []);

// components/actions/Button.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Confipetrol Button — primary action component.
 * Variants map to the brand: `primary` = corporate blue, `accent` = orange CTA.
 */
function Button({
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
    sm: {
      fontSize: 'var(--fs-sm)',
      padding: '0.4rem 0.85rem',
      gap: '0.4rem',
      height: '34px'
    },
    md: {
      fontSize: 'var(--fs-md)',
      padding: '0.6rem 1.25rem',
      gap: '0.5rem',
      height: '44px'
    },
    lg: {
      fontSize: 'var(--fs-lg)',
      padding: '0.8rem 1.75rem',
      gap: '0.6rem',
      height: '54px'
    }
  };
  const variants = {
    primary: {
      background: 'var(--color-primary)',
      color: 'var(--text-on-brand)',
      border: '1px solid var(--color-primary)',
      '--hover-bg': 'var(--color-primary-hover)',
      '--active-bg': 'var(--color-primary-active)'
    },
    accent: {
      background: 'var(--color-accent)',
      color: 'var(--text-on-brand)',
      border: '1px solid var(--color-accent)',
      '--hover-bg': 'var(--color-accent-hover)',
      '--active-bg': 'var(--color-accent-active)'
    },
    secondary: {
      background: 'var(--surface-card)',
      color: 'var(--color-primary)',
      border: '1px solid var(--border-default)',
      '--hover-bg': 'var(--cp-blue-50)',
      '--active-bg': 'var(--cp-blue-100)'
    },
    ghost: {
      background: 'transparent',
      color: 'var(--color-primary)',
      border: '1px solid transparent',
      '--hover-bg': 'var(--cp-blue-50)',
      '--active-bg': 'var(--cp-blue-100)'
    },
    danger: {
      background: 'var(--color-danger)',
      color: 'var(--text-on-brand)',
      border: '1px solid var(--color-danger)',
      '--hover-bg': '#bd3a3a',
      '--active-bg': '#a13030'
    }
  };
  const v = variants[variant] || variants.primary;
  const s = sizes[size] || sizes.md;
  const base = {
    display: block ? 'flex' : 'inline-flex',
    width: block ? '100%' : 'auto',
    alignItems: 'center',
    justifyContent: 'center',
    gap: s.gap,
    height: s.height,
    padding: s.padding,
    fontFamily: 'var(--font-display)',
    fontSize: s.fontSize,
    fontWeight: 'var(--fw-semibold)',
    letterSpacing: '0.01em',
    lineHeight: 1,
    borderRadius: 'var(--radius-md)',
    cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.5 : 1,
    transition: 'background var(--dur-fast) var(--ease-out), transform var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)',
    ...v,
    ...style
  };
  const onEnter = e => {
    if (!disabled) e.currentTarget.style.background = v['--hover-bg'];
  };
  const onLeave = e => {
    if (!disabled) e.currentTarget.style.background = v.background;
  };
  const onDown = e => {
    if (!disabled) {
      e.currentTarget.style.background = v['--active-bg'];
      e.currentTarget.style.transform = 'translateY(1px)';
    }
  };
  const onUp = e => {
    if (!disabled) {
      e.currentTarget.style.background = v['--hover-bg'];
      e.currentTarget.style.transform = 'none';
    }
  };
  return /*#__PURE__*/React.createElement("button", _extends({
    type: type,
    disabled: disabled,
    onClick: onClick,
    style: base,
    onMouseEnter: onEnter,
    onMouseLeave: onLeave,
    onMouseDown: onDown,
    onMouseUp: onUp
  }, rest), iconLeft, children, iconRight);
}
Object.assign(__ds_scope, { Button });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/actions/Button.jsx", error: String((e && e.message) || e) }); }

// components/actions/IconButton.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Icon-only square button. Pass an SVG/icon node as children.
 */
function IconButton({
  children,
  variant = 'secondary',
  size = 'md',
  label,
  disabled = false,
  onClick,
  style = {},
  ...rest
}) {
  const sizes = {
    sm: 34,
    md: 44,
    lg: 54
  };
  const dim = sizes[size] || sizes.md;
  const variants = {
    primary: {
      background: 'var(--color-primary)',
      color: 'var(--text-on-brand)',
      border: '1px solid var(--color-primary)',
      hover: 'var(--color-primary-hover)'
    },
    accent: {
      background: 'var(--color-accent)',
      color: 'var(--text-on-brand)',
      border: '1px solid var(--color-accent)',
      hover: 'var(--color-accent-hover)'
    },
    secondary: {
      background: 'var(--surface-card)',
      color: 'var(--color-primary)',
      border: '1px solid var(--border-default)',
      hover: 'var(--cp-blue-50)'
    },
    ghost: {
      background: 'transparent',
      color: 'var(--text-body)',
      border: '1px solid transparent',
      hover: 'var(--cp-gray-100)'
    }
  };
  const v = variants[variant] || variants.secondary;
  const base = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: dim,
    height: dim,
    padding: 0,
    borderRadius: 'var(--radius-md)',
    cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.5 : 1,
    transition: 'background var(--dur-fast) var(--ease-out)',
    ...v,
    ...style
  };
  delete base.hover;
  return /*#__PURE__*/React.createElement("button", _extends({
    type: "button",
    "aria-label": label,
    disabled: disabled,
    onClick: onClick,
    style: base,
    onMouseEnter: e => {
      if (!disabled) e.currentTarget.style.background = v.hover;
    },
    onMouseLeave: e => {
      if (!disabled) e.currentTarget.style.background = v.background;
    }
  }, rest), children);
}
Object.assign(__ds_scope, { IconButton });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/actions/IconButton.jsx", error: String((e && e.message) || e) }); }

// components/data-display/Badge.jsx
try { (() => {
/**
 * Small status / category label. Tones map to brand + semantic colors.
 */
function Badge({
  children,
  tone = 'neutral',
  solid = false,
  style = {}
}) {
  const tones = {
    neutral: {
      soft: ['var(--cp-gray-100)', 'var(--cp-gray-700)'],
      solid: ['var(--cp-gray-600)', '#fff']
    },
    brand: {
      soft: ['var(--cp-blue-50)', 'var(--cp-blue-700)'],
      solid: ['var(--color-primary)', '#fff']
    },
    accent: {
      soft: ['var(--cp-orange-50)', 'var(--cp-orange-700)'],
      solid: ['var(--color-accent)', '#fff']
    },
    success: {
      soft: ['var(--cp-success-50)', 'var(--cp-success-700)'],
      solid: ['var(--color-success)', '#fff']
    },
    warning: {
      soft: ['var(--cp-warning-50)', '#9a6a10'],
      solid: ['var(--color-warning)', '#3a2a05']
    },
    danger: {
      soft: ['var(--cp-danger-50)', '#a13030'],
      solid: ['var(--color-danger)', '#fff']
    }
  };
  const [bg, fg] = (tones[tone] || tones.neutral)[solid ? 'solid' : 'soft'];
  return /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.35rem',
      padding: '0.2rem 0.6rem',
      background: bg,
      color: fg,
      fontFamily: 'var(--font-display)',
      fontSize: 'var(--fs-2xs)',
      fontWeight: 'var(--fw-semibold)',
      letterSpacing: '0.03em',
      textTransform: 'uppercase',
      borderRadius: 'var(--radius-pill)',
      lineHeight: 1.4,
      whiteSpace: 'nowrap',
      ...style
    }
  }, children);
}
Object.assign(__ds_scope, { Badge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data-display/Badge.jsx", error: String((e && e.message) || e) }); }

// components/data-display/Card.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Surface container. `elevated` adds shadow; `accentTop` adds the orange brand bar.
 */
function Card({
  children,
  elevated = false,
  accentTop = false,
  padded = true,
  hoverable = false,
  style = {},
  ...rest
}) {
  const [hover, setHover] = React.useState(false);
  const base = {
    position: 'relative',
    background: 'var(--surface-card)',
    border: '1px solid var(--border-subtle)',
    borderRadius: 'var(--radius-lg)',
    overflow: 'hidden',
    padding: padded ? 'var(--space-5)' : 0,
    boxShadow: hover && hoverable ? 'var(--shadow-lg)' : elevated ? 'var(--shadow-md)' : 'var(--shadow-xs)',
    transform: hover && hoverable ? 'translateY(-3px)' : 'none',
    transition: 'box-shadow var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out)',
    ...style
  };
  return /*#__PURE__*/React.createElement("div", _extends({
    style: base,
    onMouseEnter: () => hoverable && setHover(true),
    onMouseLeave: () => hoverable && setHover(false)
  }, rest), accentTop && /*#__PURE__*/React.createElement("span", {
    style: {
      position: 'absolute',
      top: 0,
      left: 0,
      right: 0,
      height: 4,
      background: 'var(--color-accent)'
    }
  }), children);
}
Object.assign(__ds_scope, { Card });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data-display/Card.jsx", error: String((e && e.message) || e) }); }

// components/data-display/ServiceCard.jsx
try { (() => {
/**
 * Service tile used across confipetrol.com (Mantenimiento, Overhaul, Paradas de planta…).
 * Icon + title + short blurb + "Conócelo →" affordance, lifts on hover.
 */
function ServiceCard({
  icon,
  title,
  description,
  href = '#',
  cta = 'Conócelo',
  style = {}
}) {
  const [hover, setHover] = React.useState(false);
  return /*#__PURE__*/React.createElement("a", {
    href: href,
    style: {
      textDecoration: 'none',
      display: 'block'
    },
    onMouseEnter: () => setHover(true),
    onMouseLeave: () => setHover(false)
  }, /*#__PURE__*/React.createElement(__ds_scope.Card, {
    hoverable: true,
    padded: true,
    style: {
      height: '100%',
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 56,
      height: 56,
      borderRadius: 'var(--radius-md)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      background: hover ? 'var(--color-accent)' : 'var(--cp-blue-50)',
      color: hover ? '#fff' : 'var(--color-primary)',
      fontSize: '1.5rem',
      marginBottom: 'var(--space-4)',
      transition: 'all var(--dur-base) var(--ease-out)'
    }
  }, icon), /*#__PURE__*/React.createElement("h4", {
    style: {
      margin: '0 0 0.5rem',
      fontFamily: 'var(--font-display)',
      fontWeight: 'var(--fw-bold)',
      fontSize: 'var(--fs-xl)',
      color: 'var(--text-strong)'
    }
  }, title), /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0 0 var(--space-4)',
      color: 'var(--text-muted)',
      fontSize: 'var(--fs-sm)',
      lineHeight: 'var(--lh-normal)'
    }
  }, description), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 'var(--fw-semibold)',
      fontSize: 'var(--fs-sm)',
      color: 'var(--color-accent)',
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.4rem'
    }
  }, cta, " ", /*#__PURE__*/React.createElement("span", {
    style: {
      transform: hover ? 'translateX(4px)' : 'none',
      transition: 'transform var(--dur-base) var(--ease-out)'
    }
  }, "\u2192"))));
}
Object.assign(__ds_scope, { ServiceCard });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data-display/ServiceCard.jsx", error: String((e && e.message) || e) }); }

// components/data-display/Stat.jsx
try { (() => {
/**
 * Headline metric / KPI block. Confipetrol surfaces O&M results as big numbers
 * (CO₂ reduced, availability %, countries, years).
 */
function Stat({
  value,
  label,
  sublabel,
  icon = null,
  accent = 'accent',
  align = 'left',
  style = {}
}) {
  const color = accent === 'brand' ? 'var(--color-primary)' : 'var(--color-accent)';
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: '0.25rem',
      textAlign: align,
      ...style
    }
  }, icon && /*#__PURE__*/React.createElement("span", {
    style: {
      color,
      fontSize: '1.4rem',
      marginBottom: '0.25rem'
    }
  }, icon), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 'var(--fw-extra)',
      fontSize: 'var(--fs-4xl)',
      lineHeight: 1,
      color: 'var(--text-strong)',
      letterSpacing: 'var(--ls-tight)'
    }
  }, value), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 'var(--fw-semibold)',
      fontSize: 'var(--fs-md)',
      color: color
    }
  }, label), sublabel && /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 'var(--fs-sm)',
      color: 'var(--text-muted)'
    }
  }, sublabel));
}
Object.assign(__ds_scope, { Stat });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data-display/Stat.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Alert.jsx
try { (() => {
/**
 * Inline message banner. Tone drives color + default icon.
 */
function Alert({
  tone = 'info',
  title,
  children,
  icon,
  onClose,
  style = {}
}) {
  const tones = {
    info: {
      bg: 'var(--cp-info-50)',
      bar: 'var(--color-info)',
      fg: 'var(--cp-blue-800)',
      ic: 'fa-circle-info'
    },
    success: {
      bg: 'var(--cp-success-50)',
      bar: 'var(--color-success)',
      fg: 'var(--cp-success-700)',
      ic: 'fa-circle-check'
    },
    warning: {
      bg: 'var(--cp-warning-50)',
      bar: 'var(--color-warning)',
      fg: '#8a5e0e',
      ic: 'fa-triangle-exclamation'
    },
    danger: {
      bg: 'var(--cp-danger-50)',
      bar: 'var(--color-danger)',
      fg: '#9a2c2c',
      ic: 'fa-circle-exclamation'
    }
  };
  const t = tones[tone] || tones.info;
  return /*#__PURE__*/React.createElement("div", {
    role: "alert",
    style: {
      display: 'flex',
      gap: 'var(--space-3)',
      alignItems: 'flex-start',
      background: t.bg,
      borderLeft: `4px solid ${t.bar}`,
      borderRadius: 'var(--radius-md)',
      padding: 'var(--space-4)',
      color: t.fg,
      ...style
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: t.bar,
      fontSize: '1.1rem',
      lineHeight: 1.4,
      flexShrink: 0
    }
  }, icon || /*#__PURE__*/React.createElement("i", {
    className: `fa-solid ${t.ic}`
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, title && /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 'var(--fw-bold)',
      fontSize: 'var(--fs-md)',
      marginBottom: children ? '0.2rem' : 0
    }
  }, title), children && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 'var(--fs-sm)',
      color: 'var(--text-body)',
      lineHeight: 'var(--lh-normal)'
    }
  }, children)), onClose && /*#__PURE__*/React.createElement("button", {
    type: "button",
    onClick: onClose,
    "aria-label": "Cerrar",
    style: {
      border: 0,
      background: 'transparent',
      cursor: 'pointer',
      color: t.fg,
      opacity: 0.7,
      fontSize: '0.95rem'
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-xmark"
  })));
}
Object.assign(__ds_scope, { Alert });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Alert.jsx", error: String((e && e.message) || e) }); }

// components/forms/Checkbox.jsx
try { (() => {
/** Checkbox with label. Controlled via `checked` + `onChange`. */
function Checkbox({
  label,
  checked = false,
  onChange,
  disabled = false,
  id,
  style = {}
}) {
  const cid = id || (label ? 'cp-cb-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: cid,
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.6rem',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.5 : 1,
      fontFamily: 'var(--font-body)',
      fontSize: 'var(--fs-md)',
      color: 'var(--text-body)',
      ...style
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 20,
      height: 20,
      flexShrink: 0,
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      borderRadius: 'var(--radius-xs)',
      border: `2px solid ${checked ? 'var(--color-primary)' : 'var(--border-strong)'}`,
      background: checked ? 'var(--color-primary)' : 'var(--surface-card)',
      color: '#fff',
      transition: 'all var(--dur-fast) var(--ease-out)'
    }
  }, checked && /*#__PURE__*/React.createElement("svg", {
    width: "12",
    height: "12",
    viewBox: "0 0 12 12",
    fill: "none"
  }, /*#__PURE__*/React.createElement("path", {
    d: "M2 6.5L4.8 9.2L10 3.4",
    stroke: "currentColor",
    strokeWidth: "2",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  }))), /*#__PURE__*/React.createElement("input", {
    id: cid,
    type: "checkbox",
    checked: checked,
    onChange: onChange,
    disabled: disabled,
    style: {
      position: 'absolute',
      opacity: 0,
      width: 0,
      height: 0
    }
  }), label);
}
Object.assign(__ds_scope, { Checkbox });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Checkbox.jsx", error: String((e && e.message) || e) }); }

// components/forms/Input.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Text input with label + optional helper / error / leading icon.
 */
function Input({
  label,
  value,
  onChange,
  placeholder,
  type = 'text',
  helper,
  error,
  disabled = false,
  iconLeft = null,
  id,
  style = {},
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const inputId = id || (label ? 'cp-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  const borderColor = error ? 'var(--color-danger)' : focus ? 'var(--border-focus)' : 'var(--border-default)';
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: '0.35rem',
      ...style
    }
  }, label && /*#__PURE__*/React.createElement("label", {
    htmlFor: inputId,
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 'var(--fs-sm)',
      fontWeight: 'var(--fw-semibold)',
      color: 'var(--text-strong)'
    }
  }, label), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      alignItems: 'center',
      gap: '0.5rem',
      background: disabled ? 'var(--surface-sunken)' : 'var(--surface-card)',
      border: `1px solid ${borderColor}`,
      boxShadow: focus ? 'var(--ring-focus)' : 'none',
      borderRadius: 'var(--radius-md)',
      padding: '0 0.85rem',
      height: '44px',
      transition: 'border-color var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)'
    }
  }, iconLeft && /*#__PURE__*/React.createElement("span", {
    style: {
      color: 'var(--text-muted)',
      display: 'inline-flex'
    }
  }, iconLeft), /*#__PURE__*/React.createElement("input", _extends({
    id: inputId,
    type: type,
    value: value,
    onChange: onChange,
    placeholder: placeholder,
    disabled: disabled,
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      border: 0,
      outline: 'none',
      background: 'transparent',
      flex: 1,
      height: '100%',
      fontFamily: 'var(--font-body)',
      fontSize: 'var(--fs-md)',
      color: 'var(--text-strong)'
    }
  }, rest))), (helper || error) && /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 'var(--fs-xs)',
      color: error ? 'var(--color-danger)' : 'var(--text-muted)'
    }
  }, error || helper));
}
Object.assign(__ds_scope, { Input });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Input.jsx", error: String((e && e.message) || e) }); }

// components/forms/Select.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/** Native select styled to match the brand field treatment. */
function Select({
  label,
  value,
  onChange,
  options = [],
  disabled = false,
  id,
  style = {},
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const selId = id || (label ? 'cp-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: '0.35rem',
      ...style
    }
  }, label && /*#__PURE__*/React.createElement("label", {
    htmlFor: selId,
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 'var(--fs-sm)',
      fontWeight: 'var(--fw-semibold)',
      color: 'var(--text-strong)'
    }
  }, label), /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative',
      display: 'flex'
    }
  }, /*#__PURE__*/React.createElement("select", _extends({
    id: selId,
    value: value,
    onChange: onChange,
    disabled: disabled,
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      appearance: 'none',
      WebkitAppearance: 'none',
      width: '100%',
      height: '44px',
      padding: '0 2.5rem 0 0.85rem',
      background: disabled ? 'var(--surface-sunken)' : 'var(--surface-card)',
      border: `1px solid ${focus ? 'var(--border-focus)' : 'var(--border-default)'}`,
      boxShadow: focus ? 'var(--ring-focus)' : 'none',
      borderRadius: 'var(--radius-md)',
      fontFamily: 'var(--font-body)',
      fontSize: 'var(--fs-md)',
      color: 'var(--text-strong)',
      cursor: disabled ? 'not-allowed' : 'pointer',
      outline: 'none',
      transition: 'border-color var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out)'
    }
  }, rest), options.map(o => {
    const val = typeof o === 'string' ? o : o.value;
    const lab = typeof o === 'string' ? o : o.label;
    return /*#__PURE__*/React.createElement("option", {
      key: val,
      value: val
    }, lab);
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: 'absolute',
      right: '0.85rem',
      top: '50%',
      transform: 'translateY(-50%)',
      pointerEvents: 'none',
      color: 'var(--text-muted)',
      fontSize: '0.7rem'
    }
  }, "\u25BC")));
}
Object.assign(__ds_scope, { Select });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Select.jsx", error: String((e && e.message) || e) }); }

// components/forms/Switch.jsx
try { (() => {
/** Toggle switch. Controlled via `checked` + `onChange`. */
function Switch({
  label,
  checked = false,
  onChange,
  disabled = false,
  id,
  style = {}
}) {
  const sid = id || (label ? 'cp-sw-' + label.replace(/\s+/g, '-').toLowerCase() : undefined);
  const toggle = () => {
    if (!disabled && onChange) onChange(!checked);
  };
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: sid,
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.6rem',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.5 : 1,
      fontFamily: 'var(--font-body)',
      fontSize: 'var(--fs-md)',
      color: 'var(--text-body)',
      ...style
    }
  }, /*#__PURE__*/React.createElement("button", {
    id: sid,
    type: "button",
    role: "switch",
    "aria-checked": checked,
    disabled: disabled,
    onClick: toggle,
    style: {
      width: 44,
      height: 26,
      flexShrink: 0,
      padding: 3,
      border: 0,
      cursor: 'inherit',
      borderRadius: 'var(--radius-pill)',
      background: checked ? 'var(--color-accent)' : 'var(--cp-gray-300)',
      display: 'inline-flex',
      justifyContent: checked ? 'flex-end' : 'flex-start',
      transition: 'background var(--dur-base) var(--ease-out)'
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 20,
      height: 20,
      borderRadius: '50%',
      background: '#fff',
      boxShadow: 'var(--shadow-sm)',
      transition: 'all var(--dur-base) var(--ease-out)'
    }
  })), label);
}
Object.assign(__ds_scope, { Switch });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Switch.jsx", error: String((e && e.message) || e) }); }

// components/navigation/Tabs.jsx
try { (() => {
/**
 * Underline tabs. Controlled (`value`/`onChange`) or uncontrolled (`defaultValue`).
 */
function Tabs({
  tabs = [],
  value,
  defaultValue,
  onChange,
  style = {}
}) {
  const [internal, setInternal] = React.useState(defaultValue ?? (tabs[0] && tabs[0].id));
  const active = value !== undefined ? value : internal;
  const select = id => {
    if (value === undefined) setInternal(id);
    if (onChange) onChange(id);
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      borderBottom: '2px solid var(--border-subtle)',
      display: 'flex',
      gap: 'var(--space-5)',
      ...style
    }
  }, tabs.map(t => {
    const on = t.id === active;
    return /*#__PURE__*/React.createElement("button", {
      key: t.id,
      type: "button",
      onClick: () => select(t.id),
      style: {
        position: 'relative',
        border: 0,
        background: 'transparent',
        cursor: 'pointer',
        padding: '0 0 0.75rem',
        marginBottom: '-2px',
        fontFamily: 'var(--font-display)',
        fontSize: 'var(--fs-md)',
        fontWeight: on ? 'var(--fw-bold)' : 'var(--fw-medium)',
        color: on ? 'var(--color-primary)' : 'var(--text-muted)',
        display: 'inline-flex',
        alignItems: 'center',
        gap: '0.4rem',
        transition: 'color var(--dur-fast) var(--ease-out)'
      },
      onMouseEnter: e => {
        if (!on) e.currentTarget.style.color = 'var(--text-strong)';
      },
      onMouseLeave: e => {
        if (!on) e.currentTarget.style.color = 'var(--text-muted)';
      }
    }, t.icon, t.label, /*#__PURE__*/React.createElement("span", {
      style: {
        position: 'absolute',
        left: 0,
        right: 0,
        bottom: '-2px',
        height: 3,
        borderRadius: 'var(--radius-pill)',
        background: on ? 'var(--color-accent)' : 'transparent',
        transition: 'background var(--dur-fast) var(--ease-out)'
      }
    }));
  }));
}
Object.assign(__ds_scope, { Tabs });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/navigation/Tabs.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/App.jsx
try { (() => {
// Router shell — switches pages, scrolls to top on nav.
function App() {
  const [route, setRoute] = React.useState('home');
  const go = r => {
    setRoute(r);
    document.getElementById('kit-scroll').scrollTo({
      top: 0
    });
  };
  let Page;
  if (route === 'services') Page = window.ServiceDetail;else if (route === 'contact') Page = window.Contact;else Page = window.Home; // home, about, news, sustainability fall back to landing

  return /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-body)'
    }
  }, /*#__PURE__*/React.createElement(window.Nav, {
    route: route,
    go: go
  }), /*#__PURE__*/React.createElement(Page, {
    go: go
  }), /*#__PURE__*/React.createElement(window.Footer, {
    go: go
  }));
}
window.App = App;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/App.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Contact.jsx
try { (() => {
// Contact page — form (DS form controls) + contact info column.
function Contact({
  go
}) {
  const {
    Button,
    Input,
    Select,
    Checkbox,
    Card,
    Alert
  } = window.ConfipetrolDesignSystem_9a2081;
  const wrap = {
    maxWidth: 1240,
    margin: '0 auto',
    padding: '0 28px'
  };
  const eyebrow = {
    fontFamily: 'var(--font-display)',
    fontWeight: 700,
    fontSize: 14,
    letterSpacing: '0.08em',
    textTransform: 'uppercase',
    color: 'var(--color-accent)',
    margin: '0 0 12px'
  };
  const [sent, setSent] = React.useState(false);
  const [ok, setOk] = React.useState(false);
  return /*#__PURE__*/React.createElement("main", {
    style: {
      background: 'var(--surface-page)'
    }
  }, /*#__PURE__*/React.createElement("section", null, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '64px 28px',
      display: 'grid',
      gridTemplateColumns: '1fr 1fr',
      gap: 56,
      alignItems: 'start'
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: eyebrow
  }, "Cont\xE1ctanos"), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 40,
      letterSpacing: '-0.02em',
      color: 'var(--text-strong)',
      margin: '0 0 16px'
    }
  }, "Hablemos de tu operaci\xF3n"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 17,
      lineHeight: 1.65,
      color: 'var(--text-body)',
      margin: '0 0 32px',
      maxWidth: 460
    }
  }, "Cu\xE9ntanos sobre tus activos y objetivos. Nuestro equipo te contactar\xE1 para construir una propuesta a la medida."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: 22
    }
  }, [['fa-location-dot', 'Sede principal', 'Cra. 15 No. 98-26, Of. 401 · Bogotá, Colombia'], ['fa-phone', 'Teléfono', '+57 (1) 432 0000'], ['fa-envelope', 'Correo', 'contacto@confipetrol.com']].map(([ic, t, v]) => /*#__PURE__*/React.createElement("div", {
    key: t,
    style: {
      display: 'flex',
      gap: 16,
      alignItems: 'flex-start'
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 46,
      height: 46,
      borderRadius: 'var(--radius-md)',
      background: 'var(--cp-blue-50)',
      color: 'var(--color-primary)',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      fontSize: 18,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: `fa-solid ${ic}`
  })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 700,
      fontSize: 15,
      color: 'var(--text-strong)'
    }
  }, t), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 15,
      color: 'var(--text-muted)'
    }
  }, v)))))), /*#__PURE__*/React.createElement(Card, {
    elevated: true,
    accentTop: true,
    style: {
      padding: 'var(--space-7)'
    }
  }, sent ? /*#__PURE__*/React.createElement(Alert, {
    tone: "success",
    title: "\xA1Gracias por escribirnos!"
  }, "Hemos recibido tu solicitud. Te contactaremos en menos de 48 horas h\xE1biles.") : /*#__PURE__*/React.createElement("form", {
    onSubmit: e => {
      e.preventDefault();
      if (ok) setSent(true);
    },
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: 18
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: '1fr 1fr',
      gap: 16
    }
  }, /*#__PURE__*/React.createElement(Input, {
    label: "Nombre",
    placeholder: "Tu nombre"
  }), /*#__PURE__*/React.createElement(Input, {
    label: "Empresa",
    placeholder: "Tu empresa"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: '1fr 1fr',
      gap: 16
    }
  }, /*#__PURE__*/React.createElement(Input, {
    label: "Correo corporativo",
    type: "email",
    placeholder: "nombre@empresa.com",
    iconLeft: /*#__PURE__*/React.createElement("i", {
      className: "fa-solid fa-envelope"
    })
  }), /*#__PURE__*/React.createElement(Select, {
    label: "Pa\xEDs",
    options: ['Colombia', 'Perú', 'Chile', 'Bolivia', 'Ecuador', 'Venezuela']
  })), /*#__PURE__*/React.createElement(Select, {
    label: "Servicio de inter\xE9s",
    options: ['Mantenimiento', 'Confiabilidad y gestión de activos', 'Paradas de planta', 'Facility Management', 'Overhaul', 'Otros']
  }), /*#__PURE__*/React.createElement(Input, {
    label: "Mensaje",
    placeholder: "Cu\xE9ntanos sobre tu operaci\xF3n\u2026"
  }), /*#__PURE__*/React.createElement(Checkbox, {
    label: "Acepto la pol\xEDtica de tratamiento de datos personales",
    checked: ok,
    onChange: e => setOk(e.target.checked)
  }), /*#__PURE__*/React.createElement(Button, {
    variant: "accent",
    size: "lg",
    type: "submit",
    block: true,
    disabled: !ok
  }, "Enviar solicitud"))))));
}
window.Contact = Contact;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Contact.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Footer.jsx
try { (() => {
// Site footer — dark blue, columns + social + legal.
function Footer({
  go
}) {
  const col = {
    display: 'flex',
    flexDirection: 'column',
    gap: 10
  };
  const head = {
    fontFamily: 'var(--font-display)',
    fontWeight: 700,
    fontSize: 14,
    letterSpacing: '0.06em',
    textTransform: 'uppercase',
    color: 'var(--cp-orange-400)',
    marginBottom: 4
  };
  const link = {
    color: 'rgba(255,255,255,0.78)',
    fontSize: 14.5,
    cursor: 'pointer'
  };
  return /*#__PURE__*/React.createElement("footer", {
    style: {
      background: 'var(--cp-blue-900)',
      color: '#fff'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1240,
      margin: '0 auto',
      padding: '56px 28px 28px',
      display: 'grid',
      gridTemplateColumns: '1.4fr 1fr 1fr 1fr',
      gap: 40
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: col
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/confipetrol-logo-white.png",
    alt: "Confipetrol",
    style: {
      height: 36,
      alignSelf: 'flex-start'
    }
  }), /*#__PURE__*/React.createElement("p", {
    style: {
      color: 'rgba(255,255,255,0.65)',
      fontSize: 14,
      lineHeight: 1.6,
      maxWidth: 280,
      margin: '6px 0 0'
    }
  }, "L\xEDderes en operaci\xF3n y mantenimiento de activos industriales en Latinoam\xE9rica."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      gap: 10,
      marginTop: 8
    }
  }, ['linkedin-in', 'facebook-f'].map(s => /*#__PURE__*/React.createElement("span", {
    key: s,
    style: {
      width: 36,
      height: 36,
      borderRadius: 8,
      background: 'rgba(255,255,255,0.1)',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      cursor: 'pointer'
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: `fa-brands fa-${s}`
  }))))), /*#__PURE__*/React.createElement("div", {
    style: col
  }, /*#__PURE__*/React.createElement("div", {
    style: head
  }, "Servicios"), ['Mantenimiento', 'Confiabilidad', 'Paradas de planta', 'Facility Management', 'Overhaul'].map(s => /*#__PURE__*/React.createElement("span", {
    key: s,
    style: link,
    onClick: () => go('services')
  }, s))), /*#__PURE__*/React.createElement("div", {
    style: col
  }, /*#__PURE__*/React.createElement("div", {
    style: head
  }, "Compa\xF1\xEDa"), ['Nosotros', 'Responsabilidad', 'Noticias', 'Documentación', 'Proveedores'].map(s => /*#__PURE__*/React.createElement("span", {
    key: s,
    style: link
  }, s))), /*#__PURE__*/React.createElement("div", {
    style: col
  }, /*#__PURE__*/React.createElement("div", {
    style: head
  }, "Contacto"), /*#__PURE__*/React.createElement("span", {
    style: link
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-location-dot",
    style: {
      color: 'var(--cp-orange-400)',
      marginRight: 8
    }
  }), "Bogot\xE1, Colombia"), /*#__PURE__*/React.createElement("span", {
    style: link
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-phone",
    style: {
      color: 'var(--cp-orange-400)',
      marginRight: 8
    }
  }), "+57 (1) 432 0000"), /*#__PURE__*/React.createElement("span", {
    style: link
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-envelope",
    style: {
      color: 'var(--cp-orange-400)',
      marginRight: 8
    }
  }), "contacto@confipetrol.com"))), /*#__PURE__*/React.createElement("div", {
    style: {
      borderTop: '1px solid rgba(255,255,255,0.12)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1240,
      margin: '0 auto',
      padding: '18px 28px',
      display: 'flex',
      justifyContent: 'space-between',
      flexWrap: 'wrap',
      gap: 12,
      fontSize: 13,
      color: 'rgba(255,255,255,0.6)'
    }
  }, /*#__PURE__*/React.createElement("span", null, "\xA9 2026 Confipetrol \xB7 Parte de Grupo Protexa"), /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'flex',
      gap: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    }
  }, "Pol\xEDtica de privacidad"), /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    }
  }, "Pol\xEDtica de cookies"), /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    }
  }, "Sistema de denuncias")))));
}
window.Footer = Footer;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Footer.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Home.jsx
try { (() => {
// Homepage — hero, Somos, services grid, stats band, Confinoticias, sustainability CTA.
function PhotoPlaceholder({
  label,
  h = 280,
  dark = true,
  icon = 'fa-industry'
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      height: h,
      borderRadius: 'var(--radius-xl)',
      overflow: 'hidden',
      position: 'relative',
      background: dark ? 'linear-gradient(135deg, var(--cp-blue-800), var(--cp-blue-600))' : 'linear-gradient(135deg, var(--cp-gray-200), var(--cp-gray-100))',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'absolute',
      inset: 0,
      opacity: 0.12,
      backgroundImage: 'radial-gradient(circle at 1px 1px, #fff 1.5px, transparent 0)',
      backgroundSize: '22px 22px'
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: 'center',
      color: dark ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)',
      zIndex: 1
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: `fa-solid ${icon}`,
    style: {
      fontSize: 34,
      marginBottom: 10,
      display: 'block'
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-mono)',
      fontSize: 12,
      letterSpacing: '0.04em'
    }
  }, label)));
}
function Home({
  go
}) {
  const {
    Button,
    ServiceCard,
    Stat,
    Card,
    Badge
  } = window.ConfipetrolDesignSystem_9a2081;
  const wrap = {
    maxWidth: 1240,
    margin: '0 auto',
    padding: '0 28px'
  };
  const eyebrow = {
    fontFamily: 'var(--font-display)',
    fontWeight: 700,
    fontSize: 14,
    letterSpacing: '0.08em',
    textTransform: 'uppercase',
    color: 'var(--color-accent)',
    margin: '0 0 12px'
  };
  const services = [['fa-screwdriver-wrench', 'Mantenimiento', 'Mantenimiento integral para prolongar la vida útil de infraestructuras críticas.'], ['fa-gauge-high', 'Confiabilidad y activos', 'Técnicas predictivas y CBM que maximizan la disponibilidad de tus equipos.'], ['fa-helmet-safety', 'Paradas de planta', 'Planeación y ejecución de paradas mayores con seguridad y cumplimiento.'], ['fa-building-shield', 'Facility Management', 'Gestión integral de instalaciones para operaciones eficientes y seguras.'], ['fa-gears', 'Overhaul', 'Reparación mayor de equipo rotativo y maquinaria crítica.'], ['fa-list-check', 'Otros servicios', 'Soluciones especializadas a la medida de cada sector industrial.']];
  const news = [['Minería', 'success', '24.01.2025', 'Nueva adjudicación en Codelco Salvador', 'Consolidamos nuestra presencia en la industria minera en Chile.'], ['Reconocimiento', 'brand', '12.01.2025', 'Reconocimiento de Newmont Yanacocha', 'Por nuestra destacada labor en responsabilidad social y desarrollo de capacidades.'], ['Sostenibilidad', 'accent', '10.12.2024', 'Premio al Desarrollo Sostenible 2024', 'Reducimos más de 82.5 toneladas de CO₂ con un Sistema de Gestión de Energía ISO 50001.']];
  return /*#__PURE__*/React.createElement("main", null, /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'linear-gradient(120deg, var(--cp-blue-900) 0%, var(--cp-blue-700) 60%, var(--cp-blue-600) 100%)',
      color: '#fff',
      position: 'relative',
      overflow: 'hidden'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'absolute',
      top: -120,
      right: -80,
      width: 520,
      height: 520,
      borderRadius: '50%',
      background: 'radial-gradient(circle, rgba(245,138,31,0.22), transparent 70%)'
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '88px 28px 96px',
      display: 'grid',
      gridTemplateColumns: '1.05fr 0.95fr',
      gap: 56,
      alignItems: 'center',
      position: 'relative'
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: {
      ...eyebrow,
      color: 'var(--cp-orange-400)'
    }
  }, "Operaci\xF3n & Mantenimiento"), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 52,
      lineHeight: 1.04,
      letterSpacing: '-0.02em',
      color: '#fff',
      margin: '0 0 18px'
    }
  }, "Una entidad,", /*#__PURE__*/React.createElement("br", null), "un prop\xF3sito,", /*#__PURE__*/React.createElement("br", null), /*#__PURE__*/React.createElement("span", {
    style: {
      color: 'var(--cp-orange-400)'
    }
  }, "un equipo")), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 19,
      lineHeight: 1.6,
      color: 'rgba(255,255,255,0.85)',
      maxWidth: 460,
      margin: '0 0 30px'
    }
  }, "Transformamos la industria con soluciones en operaci\xF3n y mantenimiento de clase mundial."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      gap: 14
    }
  }, /*#__PURE__*/React.createElement(Button, {
    variant: "accent",
    size: "lg",
    onClick: () => go('services')
  }, "Ver servicios"), /*#__PURE__*/React.createElement(Button, {
    variant: "secondary",
    size: "lg",
    iconLeft: /*#__PURE__*/React.createElement("i", {
      className: "fa-solid fa-play"
    }),
    style: {
      background: 'rgba(255,255,255,0.1)',
      color: '#fff',
      border: '1px solid rgba(255,255,255,0.3)'
    }
  }, "Ver video"))), /*#__PURE__*/React.createElement(PhotoPlaceholder, {
    label: "Imagen: planta industrial / operaci\xF3n en campo",
    h: 340,
    icon: "fa-oil-well"
  }))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-card)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '80px 28px',
      display: 'grid',
      gridTemplateColumns: '1fr 1fr',
      gap: 56,
      alignItems: 'center'
    }
  }, /*#__PURE__*/React.createElement(PhotoPlaceholder, {
    label: "Imagen: equipo Confipetrol",
    h: 300,
    dark: false,
    icon: "fa-people-group"
  }), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: eyebrow
  }, "Somos Confipetrol"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 34,
      letterSpacing: '-0.02em',
      color: 'var(--text-strong)',
      margin: '0 0 16px'
    }
  }, "Aliado estrat\xE9gico de la industria en Latinoam\xE9rica"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 16.5,
      lineHeight: 1.7,
      color: 'var(--text-body)',
      margin: '0 0 24px'
    }
  }, "Empresa multinacional especializada en la operaci\xF3n y mantenimiento de activos industriales para los sectores de miner\xEDa, oil & gas y energ\xEDa. Garantizamos resultados medibles y sostenibles con tecnolog\xEDa de \xFAltima generaci\xF3n y un equipo altamente capacitado."), /*#__PURE__*/React.createElement(Button, {
    variant: "primary",
    onClick: () => go('about'),
    iconRight: /*#__PURE__*/React.createElement("span", null, "\u2192")
  }, "Con\xF3cenos")))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-page)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '80px 28px'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: 'center',
      marginBottom: 44
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: eyebrow
  }, "Nuestros servicios"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 34,
      letterSpacing: '-0.02em',
      color: 'var(--text-strong)',
      margin: 0
    }
  }, "Soluciones integrales de mantenimiento")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: 'repeat(3, 1fr)',
      gap: 22
    }
  }, services.map(([icon, title, desc]) => /*#__PURE__*/React.createElement(ServiceCard, {
    key: title,
    icon: /*#__PURE__*/React.createElement("i", {
      className: `fa-solid ${icon}`
    }),
    title: title,
    description: desc,
    href: "#",
    cta: "Con\xF3celo"
  }))))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--cp-blue-800)',
      color: '#fff'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '56px 28px',
      display: 'grid',
      gridTemplateColumns: 'repeat(4, 1fr)',
      gap: 32
    }
  }, [['+20', 'Años de experiencia'], ['6', 'Países en Latam'], ['+5.000', 'Colaboradores'], ['99.2%', 'Disponibilidad de activos']].map(([v, l]) => /*#__PURE__*/React.createElement("div", {
    key: l,
    style: {
      textAlign: 'center'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 46,
      lineHeight: 1,
      color: 'var(--cp-orange-400)'
    }
  }, v), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 15,
      color: 'rgba(255,255,255,0.82)',
      marginTop: 8
    }
  }, l))))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-card)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '80px 28px'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'flex-end',
      marginBottom: 36
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: eyebrow
  }, "Confinoticias"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 32,
      letterSpacing: '-0.02em',
      color: 'var(--text-strong)',
      margin: 0
    }
  }, "Lo \xFAltimo de Confipetrol")), /*#__PURE__*/React.createElement(Button, {
    variant: "ghost",
    onClick: () => go('news')
  }, "Ver todas \u2192")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: 'repeat(3, 1fr)',
      gap: 22
    }
  }, news.map(([tag, tone, date, title, excerpt]) => /*#__PURE__*/React.createElement(Card, {
    key: title,
    padded: false,
    hoverable: true,
    style: {
      display: 'flex',
      flexDirection: 'column'
    }
  }, /*#__PURE__*/React.createElement(PhotoPlaceholder, {
    label: "Imagen de noticia",
    h: 150,
    icon: "fa-newspaper"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 20,
      display: 'flex',
      flexDirection: 'column',
      gap: 10,
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      alignItems: 'center',
      gap: 10
    }
  }, /*#__PURE__*/React.createElement(Badge, {
    tone: tone
  }, tag), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-mono)',
      fontSize: 12,
      color: 'var(--text-muted)'
    }
  }, date)), /*#__PURE__*/React.createElement("h4", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 700,
      fontSize: 18,
      color: 'var(--text-strong)',
      margin: 0,
      lineHeight: 1.3
    }
  }, title), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 14,
      color: 'var(--text-muted)',
      lineHeight: 1.55,
      margin: 0
    }
  }, excerpt))))))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-page)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '0 28px 80px'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      borderRadius: 'var(--radius-2xl)',
      overflow: 'hidden',
      position: 'relative',
      background: 'linear-gradient(110deg, var(--cp-blue-700), var(--cp-blue-900))',
      color: '#fff',
      padding: '52px 56px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 40
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'absolute',
      right: -40,
      bottom: -60,
      width: 320,
      height: 320,
      borderRadius: '50%',
      background: 'radial-gradient(circle, rgba(245,138,31,0.25), transparent 70%)'
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative',
      maxWidth: 620
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: {
      ...eyebrow,
      color: 'var(--cp-orange-400)'
    }
  }, "Sostenibilidad"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 32,
      letterSpacing: '-0.02em',
      color: '#fff',
      margin: '0 0 12px'
    }
  }, "Nuestro compromiso con un futuro sostenible"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 16.5,
      lineHeight: 1.6,
      color: 'rgba(255,255,255,0.85)',
      margin: 0
    }
  }, "Descarga nuestro Reporte de Sostenibilidad y descubre c\xF3mo generamos impacto positivo en la industria y las comunidades.")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative',
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(Button, {
    variant: "accent",
    size: "lg",
    iconLeft: /*#__PURE__*/React.createElement("i", {
      className: "fa-solid fa-file-arrow-down"
    })
  }, "Ver reporte"))))));
}
window.PhotoPlaceholder = PhotoPlaceholder;
window.Home = Home;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Home.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Nav.jsx
try { (() => {
// Top navigation bar — white, logo left, menu center, orange CTA right.
function Nav({
  route,
  go
}) {
  const {
    Button
  } = window.ConfipetrolDesignSystem_9a2081;
  const items = [['home', 'Inicio'], ['about', 'Nosotros'], ['services', 'Servicios'], ['sustainability', 'Responsabilidad'], ['news', 'Noticias']];
  return /*#__PURE__*/React.createElement("header", {
    style: {
      position: 'sticky',
      top: 0,
      zIndex: 50,
      background: 'rgba(255,255,255,0.92)',
      backdropFilter: 'blur(10px)',
      borderBottom: '1px solid var(--border-subtle)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: 'var(--cp-blue-800)',
      color: 'rgba(255,255,255,0.82)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1240,
      margin: '0 auto',
      padding: '6px 28px',
      display: 'flex',
      justifyContent: 'flex-end',
      gap: 20,
      fontSize: 12.5,
      fontFamily: 'var(--font-display)',
      fontWeight: 500
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: 6
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-earth-americas",
    style: {
      color: 'var(--cp-orange-400)'
    }
  }), " Global"), /*#__PURE__*/React.createElement("span", {
    style: {
      opacity: 0.4
    }
  }, "|"), /*#__PURE__*/React.createElement("span", null, "CO \xB7 PE \xB7 CL \xB7 BO \xB7 EC \xB7 VE"), /*#__PURE__*/React.createElement("span", {
    style: {
      opacity: 0.4
    }
  }, "|"), /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    }
  }, "ES"), /*#__PURE__*/React.createElement("span", {
    style: {
      opacity: 0.5,
      cursor: 'pointer'
    }
  }, "EN"))), /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1240,
      margin: '0 auto',
      padding: '14px 28px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 24
    }
  }, /*#__PURE__*/React.createElement("a", {
    href: "#",
    onClick: e => {
      e.preventDefault();
      go('home');
    },
    style: {
      display: 'flex'
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/confipetrol-logo.png",
    alt: "Confipetrol",
    style: {
      height: 38
    }
  })), /*#__PURE__*/React.createElement("nav", {
    style: {
      display: 'flex',
      gap: 28
    }
  }, items.map(([id, label]) => /*#__PURE__*/React.createElement("a", {
    key: id,
    href: "#",
    onClick: e => {
      e.preventDefault();
      go(id);
    },
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 15,
      fontWeight: 600,
      color: route === id ? 'var(--color-primary)' : 'var(--text-body)',
      position: 'relative',
      paddingBottom: 4,
      borderBottom: route === id ? '2px solid var(--color-accent)' : '2px solid transparent'
    }
  }, label))), /*#__PURE__*/React.createElement(Button, {
    variant: "accent",
    size: "sm",
    onClick: () => go('contact'),
    iconLeft: /*#__PURE__*/React.createElement("i", {
      className: "fa-solid fa-phone"
    })
  }, "Cont\xE1ctanos")));
}
window.Nav = Nav;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Nav.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/ServiceDetail.jsx
try { (() => {
// Service detail page — e.g. Mantenimiento. Hero banner + intro + capability tabs + CTA.
function ServiceDetail({
  go
}) {
  const {
    Button,
    Tabs,
    Card,
    Badge,
    Stat
  } = window.ConfipetrolDesignSystem_9a2081;
  const PhotoPlaceholder = window.PhotoPlaceholder;
  const wrap = {
    maxWidth: 1240,
    margin: '0 auto',
    padding: '0 28px'
  };
  const eyebrow = {
    fontFamily: 'var(--font-display)',
    fontWeight: 700,
    fontSize: 14,
    letterSpacing: '0.08em',
    textTransform: 'uppercase',
    color: 'var(--color-accent)',
    margin: '0 0 12px'
  };
  const [tab, setTab] = React.useState('mecanico');
  const capabilities = {
    mecanico: ['Mantenimiento de equipo rotativo y estático', 'Alineación láser y balanceo dinámico', 'Inspección de integridad mecánica'],
    electrico: ['Mantenimiento de subestaciones y tableros', 'Termografía y análisis de motores', 'Instrumentación y control'],
    predictivo: ['Análisis de vibraciones y aceite', 'Monitoreo de condición (CBM)', 'Planes basados en confiabilidad (RCM)']
  };
  return /*#__PURE__*/React.createElement("main", null, /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'linear-gradient(120deg, var(--cp-blue-900), var(--cp-blue-700))',
      color: '#fff'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '24px 28px 0',
      fontSize: 13.5,
      color: 'rgba(255,255,255,0.7)',
      fontFamily: 'var(--font-display)'
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    },
    onClick: () => go('home')
  }, "Inicio"), /*#__PURE__*/React.createElement("span", {
    style: {
      margin: '0 8px',
      opacity: 0.5
    }
  }, "/"), /*#__PURE__*/React.createElement("span", {
    style: {
      cursor: 'pointer'
    },
    onClick: () => go('services')
  }, "Servicios"), /*#__PURE__*/React.createElement("span", {
    style: {
      margin: '0 8px',
      opacity: 0.5
    }
  }, "/"), /*#__PURE__*/React.createElement("span", {
    style: {
      color: 'var(--cp-orange-400)'
    }
  }, "Mantenimiento")), /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '40px 28px 60px',
      display: 'grid',
      gridTemplateColumns: '1fr 1fr',
      gap: 48,
      alignItems: 'center'
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: {
      ...eyebrow,
      color: 'var(--cp-orange-400)'
    }
  }, "Servicio"), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 46,
      lineHeight: 1.05,
      letterSpacing: '-0.02em',
      color: '#fff',
      margin: '0 0 16px'
    }
  }, "Mantenimiento industrial"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 18,
      lineHeight: 1.6,
      color: 'rgba(255,255,255,0.85)',
      maxWidth: 480,
      margin: '0 0 26px'
    }
  }, "Mantenimiento integral para prolongar la vida \xFAtil de infraestructuras cr\xEDticas y asegurar su funcionamiento \xF3ptimo, con seguridad y confiabilidad."), /*#__PURE__*/React.createElement(Button, {
    variant: "accent",
    size: "lg",
    onClick: () => go('contact')
  }, "Solicitar propuesta")), /*#__PURE__*/React.createElement(PhotoPlaceholder, {
    label: "Imagen: t\xE9cnicos en mantenimiento",
    h: 300,
    icon: "fa-screwdriver-wrench"
  }))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-card)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '72px 28px',
      display: 'grid',
      gridTemplateColumns: '0.9fr 1.1fr',
      gap: 56,
      alignItems: 'start'
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    style: eyebrow
  }, "Qu\xE9 hacemos"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: 'var(--font-display)',
      fontWeight: 800,
      fontSize: 30,
      letterSpacing: '-0.02em',
      color: 'var(--text-strong)',
      margin: '0 0 16px'
    }
  }, "Cobertura t\xE9cnica completa"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 16,
      lineHeight: 1.7,
      color: 'var(--text-body)',
      margin: 0
    }
  }, "Nuestros equipos multidisciplinarios cubren el ciclo completo de mantenimiento mec\xE1nico, el\xE9ctrico y predictivo, aplicando t\xE9cnicas de confiabilidad reconocidas internacionalmente."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      gap: 10,
      marginTop: 22,
      flexWrap: 'wrap'
    }
  }, /*#__PURE__*/React.createElement(Badge, {
    tone: "brand"
  }, "ISO 55001"), /*#__PURE__*/React.createElement(Badge, {
    tone: "brand"
  }, "ISO 50001"), /*#__PURE__*/React.createElement(Badge, {
    tone: "success"
  }, "OSHA"), /*#__PURE__*/React.createElement(Badge, {
    tone: "accent"
  }, "RCM"))), /*#__PURE__*/React.createElement(Card, {
    elevated: true,
    accentTop: true
  }, /*#__PURE__*/React.createElement(Tabs, {
    value: tab,
    onChange: setTab,
    tabs: [{
      id: 'mecanico',
      label: 'Mecánico'
    }, {
      id: 'electrico',
      label: 'Eléctrico'
    }, {
      id: 'predictivo',
      label: 'Predictivo'
    }]
  }), /*#__PURE__*/React.createElement("ul", {
    style: {
      listStyle: 'none',
      padding: 0,
      margin: '22px 0 0',
      display: 'flex',
      flexDirection: 'column',
      gap: 14
    }
  }, capabilities[tab].map(c => /*#__PURE__*/React.createElement("li", {
    key: c,
    style: {
      display: 'flex',
      gap: 12,
      alignItems: 'flex-start',
      fontSize: 16,
      color: 'var(--text-body)'
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: "fa-solid fa-circle-check",
    style: {
      color: 'var(--color-success)',
      marginTop: 4
    }
  }), c)))))), /*#__PURE__*/React.createElement("section", {
    style: {
      background: 'var(--surface-page)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      ...wrap,
      padding: '56px 28px',
      display: 'grid',
      gridTemplateColumns: 'repeat(3,1fr)',
      gap: 28
    }
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(Stat, {
    value: "99.2%",
    label: "Disponibilidad",
    sublabel: "promedio de planta",
    accent: "brand"
  })), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(Stat, {
    value: "-18%",
    label: "Costos de O&M",
    sublabel: "vs. l\xEDnea base del cliente"
  })), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(Stat, {
    value: "+82.5t",
    label: "CO\u2082 reducido",
    sublabel: "programa de eficiencia energ\xE9tica",
    accent: "brand"
  })))));
}
window.ServiceDetail = ServiceDetail;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/ServiceDetail.jsx", error: String((e && e.message) || e) }); }

__ds_ns.Button = __ds_scope.Button;

__ds_ns.IconButton = __ds_scope.IconButton;

__ds_ns.Badge = __ds_scope.Badge;

__ds_ns.Card = __ds_scope.Card;

__ds_ns.ServiceCard = __ds_scope.ServiceCard;

__ds_ns.Stat = __ds_scope.Stat;

__ds_ns.Alert = __ds_scope.Alert;

__ds_ns.Checkbox = __ds_scope.Checkbox;

__ds_ns.Input = __ds_scope.Input;

__ds_ns.Select = __ds_scope.Select;

__ds_ns.Switch = __ds_scope.Switch;

__ds_ns.Tabs = __ds_scope.Tabs;

})();
