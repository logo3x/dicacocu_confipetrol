**Button** — the core action control; `accent` (orange) for the one hero CTA per view, `primary` (blue) for standard confirms.

```jsx
<Button variant="accent" size="lg">Contáctanos</Button>
<Button variant="primary">Ver servicios</Button>
<Button variant="secondary" iconLeft={<Icon name="download" />}>Descargar reporte</Button>
<Button variant="ghost" size="sm">Cancelar</Button>
```

Variants: `primary` · `accent` · `secondary` · `ghost` · `danger`. Sizes: `sm` `md` `lg`. Props: `block`, `disabled`, `iconLeft`, `iconRight`. Reserve `accent` for a single primary call-to-action; don't place two accent buttons side by side.
