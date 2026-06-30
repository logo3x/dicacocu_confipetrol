**Select** — native dropdown styled to match `Input`.

```jsx
<Select label="País" value={pais} onChange={e => setPais(e.target.value)}
        options={['Colombia','Perú','Chile','Bolivia','Ecuador']} />
```

`options` accepts plain strings or `{value, label}` objects.
