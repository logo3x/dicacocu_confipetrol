**Input** — labelled text field with optional helper, error state, and leading icon.

```jsx
<Input label="Correo corporativo" type="email" placeholder="nombre@empresa.com"
       iconLeft={<i className="fa-solid fa-envelope"></i>} />
<Input label="Teléfono" error="Ingresa un número válido" />
```

Pass `error` to show the red invalid state (it replaces `helper`). Default field height is 44px.
