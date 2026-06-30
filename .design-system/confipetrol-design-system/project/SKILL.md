---
name: confipetrol-design
description: Use this skill to generate well-branded interfaces and assets for Confipetrol, either for production or throwaway prototypes/mocks/etc. Contains essential design guidelines, colors, type, fonts, assets, and UI kit components for prototyping.
user-invocable: true
---

Read the README.md file within this skill, and explore the other available files.
If creating visual artifacts (slides, mocks, throwaway prototypes, etc), copy assets out and create static HTML files for the user to view. If working on production code, you can copy assets and read the rules here to become an expert in designing with this brand.
If the user invokes this skill without any other guidance, ask them what they want to build or design, ask some questions, and act as an expert designer who outputs HTML artifacts _or_ production code, depending on the need.

Quick orientation:
- `styles.css` is the single CSS entry point (links all tokens + base). Link it and use the CSS custom properties — brand orange `--cp-orange-500` / `#F58A1F`, brand blue `--cp-blue-600` / `#0050A0`.
- Components live in `components/<group>/` as React (`.jsx`) with a `.prompt.md` usage note each. The compiled bundle exposes them on `window.ConfipetrolDesignSystem_9a2081`.
- `ui_kits/website/` is a working recreation of confipetrol.com to reference for layout and tone.
- Fonts: Montserrat (display) / Source Sans 3 (body) / IBM Plex Mono (data) — substitutions; swap if licensed brand fonts are provided. Icons: Font Awesome 6 (CDN). No emoji. Spanish-first copy.
