**Tabs** — underline tab bar with the orange active indicator. Controlled or uncontrolled.

```jsx
<Tabs defaultValue="mant" tabs={[
  { id: 'mant', label: 'Mantenimiento' },
  { id: 'over', label: 'Overhaul' },
  { id: 'par',  label: 'Paradas de planta' },
]} onChange={setActive} />
```
