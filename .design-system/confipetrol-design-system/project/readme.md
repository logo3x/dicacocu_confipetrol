# Confipetrol Design System

A brand + UI design system for **Confipetrol**, a multinational operation & maintenance (O&M) company serving the mining, oil & gas, and energy sectors across Latin America. Use it to build on-brand interfaces, marketing pages, proposals, and prototypes.

> **Sources.** No codebase or Figma was provided — only the corporate logo (`uploads/confipetrol.jpg`). Brand context and structure were derived from the public site **https://confipetrol.com** (and `…/sobre-nosotros/`). Brand colors were sampled directly from the logo. Fonts are the closest Google Fonts matches (flagged below).

---

## Company context

Confipetrol (founded 2005, HQ Bogotá, Colombia) provides integrated **operation & maintenance** of industrial assets — maintenance, reliability & asset management, plant turnarounds (*paradas de planta*), facility management, and overhaul. It operates in **6 Latin-American countries** (Colombia, Perú, Chile, Bolivia, Ecuador, Venezuela), employs 1,000–5,000 people, and in **September 2024 was acquired by Mexico's Grupo Protexa**. Tagline: *"¡Una entidad, un propósito, un equipo!"*

The audience is **industrial / B2B**: plant managers, reliability engineers, procurement, and corporate stakeholders. The brand reads as **trustworthy, technical, safety-first, and results-driven** — not flashy.

---

## CONTENT FUNDAMENTALS

- **Language: Spanish-first.** Primary copy is Spanish (Latam); an English toggle exists. Always author Spanish first; keep accents and ñ. Anglicisms are kept where industry-standard ("Overhaul", "Facility Management", "oil & gas", "CBM").
- **Voice: first-person plural, confident, collective.** The brand speaks as "nosotros" — *"Transformamos la industria…"*, *"Ofrecemos soluciones integrales…"*, *"Salvaguardamos a las personas…"*. It addresses clients implicitly, not with a casual "tú" — tone is corporate-warm, never salesy.
- **Casing.** Headlines in sentence case or with a single emphasized phrase; **eyebrows/labels in UPPERCASE** with wide tracking ("NUESTROS SERVICIOS", "CONFINOTICIAS"). CTAs are often uppercase short verbs ("CONTÁCTANOS", "VER VIDEO", "VER REPORTE").
- **Vocabulary.** Operation & maintenance, confiabilidad, gestión de activos, disponibilidad, seguridad, sostenibilidad, eficiencia operativa, vida útil, activos críticos, clase mundial.
- **Numbers as proof.** Results are quantified: "+82.5 toneladas de CO₂", "ISO 50001", country counts, years of experience. Use the mono typeface for these.
- **No emoji.** None on the corporate site. Do not introduce them. Iconography carries visual accent instead.
- **Vibe.** Serious, dependable, industrial-modern. Safety and sustainability are recurring themes ("Comprometidos con la seguridad, sostenibilidad y eficiencia").

**Example copy lifted from brand:** *"Confipetrol es una empresa multinacional especializada en la operación y mantenimiento de activos industriales para los sectores de minería, oil & gas y energía."*

---

## VISUAL FOUNDATIONS

- **Two-color core.** Vivid **orange `#F58A1F`** (`--cp-orange-500`) = energy, action, CTAs, the heading accent rule. Deep **blue `#0050A0`** (`--cp-blue-600`) = trust, corporate structure, headings, dark sections. These come straight from the logo (orange "cp" mark, blue wordmark). Use **one accent CTA per view** — don't flood with orange.
- **Neutrals are cool steel** (`--cp-gray-*`), slightly blue-shifted — industrial, not warm. Page background is a near-white `#F6F7F9`; cards are pure white.
- **Type.** Display = **Montserrat** (geometric, 700/800, tight tracking) — echoes the bold geometric CONFIPETROL wordmark. Body = **Source Sans 3** (humanist, relaxed leading, strong Spanish diacritics). Data/specs = **IBM Plex Mono** for KPIs, dates, codes. *(Substitutions — see Fonts note.)*
- **Signature heading motif:** an UPPERCASE orange **eyebrow** above the headline, with a short **56×4px orange accent rule** (`.cp-accent-rule`) underneath.
- **Backgrounds.** Mostly flat white / light-steel. Hero and emphasis sections use **deep-blue gradients** (`blue-900 → blue-600`) with a soft **orange radial glow** in a corner and an optional faint dot grid — never loud, never purple. No heavy textures.
- **Imagery** (on the live site): real photography of plants, field operations, and teams — industrial, daylight, cool-to-neutral tone. In this kit, real photos weren't available to copy, so `PhotoPlaceholder` blocks mark where licensed photography belongs.
- **Corners.** Lightly rounded and corporate, not pill-soft: cards `--radius-lg` (12px), inputs/buttons `--radius-md` (8px). Pills reserved for badges and the accent rule.
- **Cards.** White, 1px `--border-subtle`, `--radius-lg`, resting `--shadow-xs`. Optional **orange top bar** (`accentTop`) for emphasis. Hoverable cards lift `translateY(-3px)` and deepen to `--shadow-lg`.
- **Shadows** are cool and low-spread (rgba of ink/blue), never black-heavy. Brand/accent glow shadows exist for floating CTAs.
- **Borders** carry structure (the system leans on hairline borders + subtle shadow rather than heavy elevation).
- **Motion.** Purposeful and quick: `--dur-fast 140ms` / `--dur-base 220ms`, eased with `--ease-out`. Hovers shift background color and lift; arrows nudge `translateX(4px)`. Buttons press with `translateY(1px)` + darker shade. No bounces, no infinite loops. Respects `prefers-reduced-motion`.
- **Hover states:** darker brand shade for solid buttons; soft blue tint (`blue-50`) for secondary/ghost; cards lift. **Press states:** darker shade + 1px downward nudge.
- **Focus:** 3px soft-blue ring (`--ring-focus`).
- **Transparency/blur:** the sticky nav uses `rgba(white,.92)` + `backdrop-filter: blur` over content; dark gradient overlays sit under hero text. Used sparingly.

---

## ICONOGRAPHY

- **Font Awesome 6** (solid + brands) is the icon system — loaded from CDN (`cdnjs …/font-awesome/6.5.2`). This matches the live site, which is built on **Elementor with Font Awesome** (`e_font_icon_svg`). *Substitution flag: the exact custom icons on confipetrol.com were not extractable; Font Awesome is the documented, closest-match standard.*
- **Style:** solid, single-weight glyphs. Industrial/utility metaphors recur: `fa-screwdriver-wrench`, `fa-gears`, `fa-gauge-high`, `fa-helmet-safety`, `fa-building-shield`, `fa-oil-well`, `fa-industry`, `fa-file-arrow-down`.
- **Color:** icons take brand blue on light surfaces, orange for active/accent, white on dark. Service tiles invert to an orange chip on hover.
- **Brand glyphs:** `fa-brands` LinkedIn + Facebook in the footer.
- **No emoji, no unicode-symbol icons.** Logo assets are real PNGs in `assets/` (see below) — never redraw the mark as SVG.

---

## INDEX — what's in this system

**Root**
- `styles.css` — the single entry point consumers link (import list only).
- `readme.md` — this guide.
- `SKILL.md` — Agent Skill front-matter wrapper.

**`tokens/`** — `fonts.css`, `colors.css`, `typography.css`, `spacing.css`, `base.css` (all reached via `styles.css`).

**`assets/`** — `confipetrol-logo.png` (transparent full lockup), `confipetrol-logo-white.png` (knockout for dark), `confipetrol-mark.png` (orange "cp" symbol).

**`components/`** (namespace `window.ConfipetrolDesignSystem_9a2081`)
- `actions/` — **Button**, **IconButton**
- `forms/` — **Input**, **Select**, **Checkbox**, **Switch**
- `data-display/` — **Card**, **Badge**, **Stat**, **ServiceCard**
- `navigation/` — **Tabs**
- `feedback/` — **Alert**

**`guidelines/`** — foundation specimen cards (Colors, Type, Spacing, Brand) shown in the Design System tab.

**`ui_kits/website/`** — interactive recreation of confipetrol.com (Home, Service detail, Contact + Nav/Footer). See its `README.md`.

---

## Fonts note (action needed)

The live site loads Google Fonts via Elementor but does not expose the exact families. This system substitutes **Montserrat / Source Sans 3 / IBM Plex Mono** (loaded from Google Fonts CDN in `tokens/fonts.css`). **If Confipetrol has licensed brand fonts, send the files** and we'll swap them in (add `@font-face` + update `--font-display/body/mono`).
