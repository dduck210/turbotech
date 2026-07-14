# Boutique Design System

Shared spec for the full client + admin redesign. Both redesign agents
follow this exactly so the two halves of the app end up visually consistent
without needing to coordinate with each other directly.

## Why this exists

The previous "Dark Luxury Tech" pass was criticized by the user for being a
pure color-token swap with no real layout change. This pass must be
different: **restructure markup — spacing, card composition, section
layout, imagery framing, hover interaction — not just rename classes.**
A page that still visually reads as "the same layout, new hex codes" is a
failed phase.

## Tokens (already live in `src/css/tailwind-input.css` /
`src/css/admin-tailwind-input.css` as of phase 1 — do not edit those files,
just use the resulting utility classes)

`ink-*` — warm neutral scale, standard light-to-dark orientation:
- `ink-50` #FBF8F2 — lightest surface (cards, inputs)
- `ink-100` #F4EFE6 — page background
- `ink-200` #ECE4D3 — secondary surface / hover background
- `ink-300` #DCD2BF — thin borders, dividers
- `ink-400` #C4B49B — muted borders, disabled/placeholder icons
- `ink-500` #9C8B72 — secondary/muted text
- `ink-600` #7A6B54 — body text (secondary emphasis)
- `ink-700` #5A4C3A — body text (primary)
- `ink-800` #3A2F22 — headings, strong text
- `ink-900` #2B2118 — darkest — near-black surfaces (footer, admin sidebar), text-on-dark

`brand-*` — bronze/gold, the primary accent (CTAs, active states, links):
50 `#FBF3E7`, 100 `#F3E2C5`, 400 `#C9A464`, 500 `#B08D57`, 600 `#96733F`, 700 `#7A5C32`

`accent-*` — deep copper/terracotta, secondary accent (gradients paired with
brand-*, secondary badges, hover depth):
400 `#C97B5B`, 500 `#A85C3F`, 600 `#8C4530`

Fonts: `--font-heading` is now **Cormorant Garamond** (serif) — use on every
`h1`–`h6`, the logo wordmark, and any "eyebrow"/hero display text.
`--font-sans` stays **Inter** for body copy, forms, tables, buttons.

## Component classes (defined in the token CSS, ready to use)

- `.card-boutique` — replaces `.glass`. Flat `ink-50` surface, 1px
  `ink-300` border, tiny shadow. Use for product cards, info panels,
  admin panels, form containers.
- `.btn-boutique` — replaces `.btn-glow`. Solid `brand-500` background,
  `brand-600` 1px border, white text, hover darkens to `brand-600` — no
  glow shadow, no scale bounce.
- `.text-serif-accent` — replaces `.text-gradient`. Serif font, solid
  `brand-600` color (no gradient text, no clip-text trick — boutique
  brands use color + weight, not neon gradients, for emphasis).
- `.card-hover` — replaces `.card-glow`. On hover: border turns
  `brand-400`, shadow deepens slightly, `translateY(-2px)` max — calm,
  not bouncy.

## Explicitly remove/avoid wherever encountered

- `backdrop-blur`/glassmorphism panels
- Neon gradient backgrounds on `body` (radial glow blobs)
- `rounded-2xl`/`rounded-3xl`/pill buttons as the default — prefer
  `rounded-md`/`rounded-lg`; reserve full-round for true circles (avatars,
  icon buttons)
- Box-shadow "glow" effects (`shadow-[0_0_40px_...]` style patterns)
- Bouncy hover scale/translate combos — keep hover motion subtle

## Layout expectations (not just restyle — restructure)

- Client homepage: rework hero/section composition into an editorial,
  magazine-like layout (asymmetric hero, generous whitespace, serif
  section headers with a thin bronze rule instead of a bento glass grid).
- Product cards: swap the "glow card + gradient overlay" pattern for a
  boutique product card — image with subtle border frame, serif product
  name, price in bronze, quiet hover (border + shadow only).
- Header/nav: keep the same functional structure (logo, nav links,
  search, cart, account) but restyle as a slim boutique storefront bar —
  thin bottom border instead of blurred glass, underline-on-hover nav
  links keep working but in bronze instead of neon gradient.
- Admin sidebar: keep the same navigation items but reskin as a cream
  sidebar with a thin right border, bronze left-accent bar for the
  active item (no gradient wash, no glow).
- Forms/tables (admin CRUD, cart, checkout, account): thin borders,
  small radius, serif section headings, no glass blur.

## Verification each agent must do before reporting done

1. `php -l` every file you touched.
2. `perl -0pi -e` or manual grep to confirm no leftover `backdrop-blur`,
   `glow`, `text-gradient`, `.glass`, or gradient-blob body backgrounds
   remain in the files you own.
3. Do NOT run the Tailwind CLI rebuild yourself — phase 4 does one final
   rebuild after both agents finish, to avoid a race on the compiled CSS
   output file.
