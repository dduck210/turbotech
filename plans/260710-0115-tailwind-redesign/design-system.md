# Turbotech — Tailwind Design System (Modern/Minimal)

Reference doc for every template redesign in this plan. Follow it exactly so all
pages look like one consistent product, not five different styles stitched
together.

## Setup (already done — do not redo)
- Tailwind CLI standalone binary at repo root: `tailwindcss.exe` (gitignored, not committed).
- Source: `src/css/tailwind-input.css` (theme tokens via `@theme`).
- Compiled output: `src/css/tailwind.css` — **must be rebuilt after editing any template**:
  `./tailwindcss.exe -i src/css/tailwind-input.css -o src/css/tailwind.css`
- `view/head.php` links `src/css/tailwind.css` + Google Fonts (Inter + Manrope) + Font Awesome
  (kept for icons — do not replace icons with a different library).
- Old CSS files (`main.css`, `plugins.min.css`, `footer.css`, `dropdown.css`) are being replaced
  page by page — once ALL templates in your file list are converted, remove any `<i class="...">`
  or wrapper `<div>` classes that only existed for the old CSS, but KEEP Font Awesome icon classes
  (`fa-solid fa-*`, `fa-regular fa-*`) since those still render icons via the CDN link in head.php.

## Design tokens (Tailwind v4 `@theme`, already defined in tailwind-input.css)
- **Fonts:** `font-heading` (Manrope, for h1-h6 and prominent headings) / `font-sans` (Inter, body
  default — `<body>` already sets this, so most elements don't need an explicit font class).
- **Brand accent (blue):** `brand-50` `brand-100` `brand-500` `brand-600` `brand-700` — use
  `brand-600` for primary buttons/links/active states, `brand-700` for hover, `brand-50`/`brand-100`
  for light accent backgrounds (badges, selected filters).
- **Neutrals (slate-like, named `ink`):** `ink-50` (page background) `ink-100` `ink-200` (borders)
  `ink-300` `ink-500` (secondary text) `ink-700` (body text on light) `ink-900` (headings, primary text).
- **Semantic:** use plain Tailwind `red-600`/`red-50` for errors/destructive, `green-600`/`green-50`
  for success/in-stock/discount badges — no custom tokens needed for these.

## Component patterns (use these exact recipes everywhere for consistency)

**Page shell:** `bg-ink-50 text-ink-900 font-sans antialiased`

**Primary button:**
`inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed`

**Secondary/outline button:**
`inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500`

**Destructive button:** same as secondary but `text-red-600 border-red-200 hover:bg-red-50`.

**Card (product card, info card):**
`rounded-2xl border border-ink-200 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden`
- Product image wrapper: `aspect-square bg-ink-100 overflow-hidden` with `img` as
  `h-full w-full object-cover`.
- Card body padding: `p-4`.
- Product name: `font-heading font-semibold text-ink-900 line-clamp-2`.
- Price: `text-brand-600 font-bold` current price, `text-ink-300 line-through text-sm` old price.
- Discount badge: `absolute top-2 left-2 rounded-full bg-red-600 text-white text-xs font-bold px-2 py-1`.

**Text input / select / textarea:**
`block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500`
- Label above every input (never placeholder-only): `block text-sm font-medium text-ink-700 mb-1.5`.
- Error text below field: `mt-1.5 text-sm text-red-600`.

**Section container (max width, consistent gutters):**
`mx-auto max-w-7xl px-4 sm:px-6 lg:px-8`

**Section vertical spacing:** `py-12 md:py-16` between major page sections.

**Badge/pill (category, status):**
`inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700`

**Header/nav:** sticky top bar `sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-ink-200`,
max-width container inside, logo left, nav center, cart+account icons right (icon buttons must be
`h-11 w-11` min tap target per accessibility rule, `flex items-center justify-center rounded-full
hover:bg-ink-100`).

**Empty/no-results state:** centered icon (Font Awesome, `text-5xl text-ink-300`) + heading + short
helper text, inside a card, `py-16 text-center`.

**Alerts (success/error banners from PHP `<script>alert(...)` calls):** LEAVE existing
`<script>alert(...)</script>` JS-alert calls exactly as they are (do not touch controller code or
convert to toasts — that's a backend/JS behavior change outside this visual-only redesign; only
restyle the *static* HTML success/error blocks that already exist inline in templates, e.g. the
`.error`/`.green` classes in `view/head.php` and any `$thongbao`/`$error` message `<p>` blocks —
restyle those specific elements using `rounded-lg border p-3 text-sm` + red/green variants above).

## Accessibility non-negotiables (per project rules — do not skip)
- All interactive elements (buttons, links styled as buttons, icon-only buttons) ≥44×44px tap target.
- Every `<img>` keeps a meaningful `alt` (already present in most templates — preserve/improve, never
  remove or set to empty).
- Every form `<input>`/`<select>`/`<textarea>` has a visible `<label for="...">`, not placeholder-only.
- Focus rings must stay visible (`focus:ring-2 focus:ring-brand-500` on every interactive element —
  never `outline-none` without a replacement focus style).
- Text contrast: body text `text-ink-700`+ on `bg-ink-50`/white backgrounds only (never light-gray-on-
  light-gray).

## Drop legacy jQuery UI plugins
The old theme used `niceSelect` (styled `<select>`) and `slick` (carousel/slider) jQuery plugins,
styled by the now-removed `main.css`/`plugins.min.css`. Do NOT try to restyle these plugins with
Tailwind — drop the plugin usage entirely and replace with plain Tailwind-native markup:
- Any `<select>` that had a `nice-select`/similar wrapper → plain `<select>` styled with the
  text-input recipe above (native select, no JS needed).
- Any carousel/slider (e.g. the "Được đề xuất" sidebar product slider, `slick`-initialized lists) →
  a simple responsive grid or `flex gap-4 overflow-x-auto` horizontal scroll list — no JS plugin,
  no init script needed. Remove the now-dead `data-slick`/carousel-init attributes and any
  `<script>` block that calls `.slick(...)`/`niceSelect()` on the affected elements (grep
  `src/js/main.js` if unsure whether an element is plugin-initialized before deleting its markup
  wrapper — don't break unrelated JS on the same page, e.g. the minicart dropdown toggle, mobile
  menu toggle, and quantity +/- steppers are plain vanilla-JS/CSS `:hover`/click-toggle behaviors
  that must keep working).

## What NOT to change (scope boundary)
- Do NOT change any PHP logic, controller code, route names, form `action`/`method`/`name` attributes,
  or `$_POST`/`$_GET` field names — this is a **visual-only** restyle. Every `<form>` must still submit
  the exact same fields to the exact same endpoints.
- Do NOT rename template files or move them to different paths (`Codemoi\Core\View::render()` and
  direct `include`s reference exact current paths).
- Do NOT touch `admin/` — separate app, out of scope.
- Do NOT touch PHP variable names read from controllers (`$listpro`, `$one_pro`, `$listcate`, etc.) —
  only the HTML/CSS around them.
- Keep Font Awesome icon classes; do not swap icon libraries.
