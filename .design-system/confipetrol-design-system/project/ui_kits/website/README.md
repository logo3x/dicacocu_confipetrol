# UI Kit — confipetrol.com (Marketing Website)

Interactive recreation of the Confipetrol corporate marketing site, composed from the design-system primitives.

## Screens
- **Home** (`Home.jsx`) — hero, "Somos Confipetrol", services grid (`ServiceCard`), stats band, Confinoticias cards, sustainability CTA.
- **Service detail** (`ServiceDetail.jsx`) — breadcrumb hero, capability tabs (`Tabs`), certification badges, outcome `Stat`s. Reachable via the "Servicios" nav item.
- **Contact** (`Contact.jsx`) — lead form built from `Input` / `Select` / `Checkbox`, success `Alert`, contact info column.
- **Nav** / **Footer** — shared chrome (utility country/lang strip, sticky bar, dark footer).

## Run
Open `index.html`. Routing is in-memory (`App.jsx`); nav links switch screens. `about`, `news`, and `sustainability` fall back to Home (not yet built out).

## Notes
- **Imagery is placeholder.** `PhotoPlaceholder` renders labeled industrial-blue blocks where real plant/operation photos belong — swap in licensed photography. The live site's photos were not accessible to copy in.
- Icons are Font Awesome 6 (CDN) — the same library Elementor uses on the live site.
- Source of truth: https://confipetrol.com (text + structure). No codebase or Figma was provided, so layout follows the public site's information architecture.
