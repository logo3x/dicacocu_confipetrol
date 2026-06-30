**IconButton** — square, icon-only action. Always pass `label` (becomes `aria-label`).

```jsx
<IconButton label="Buscar" variant="secondary">
  <i className="fa-solid fa-magnifying-glass"></i>
</IconButton>
<IconButton label="Menú" variant="ghost"><i className="fa-solid fa-bars"></i></IconButton>
```

Variants: `primary` `accent` `secondary` `ghost`. Sizes: `sm` (34px) `md` (44px) `lg` (54px) — md meets the 44px touch target.
