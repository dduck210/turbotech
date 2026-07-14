# Phase 01 — Design Tokens + Logo (done, by me directly)

## What changed

- `src/css/tailwind-input.css` and `src/css/admin-tailwind-input.css`:
  full token rewrite per [[design-system.md]] — warm `ink-*` scale
  (standard light-to-dark orientation), `brand-*` (bronze/gold),
  `accent-*` (copper), serif `--font-heading` (Cormorant Garamond).
  Old dark-tech component classes (`.glass`, `.btn-glow`, `.text-gradient`,
  `.card-glow`, `glow-pulse`/`gradient-shift`/`float` keyframes, body
  radial-gradient blobs) are GONE — replaced with `.card-boutique`,
  `.btn-boutique`, `.text-serif-accent`, `.card-hover`.
- Both compile cleanly with `tailwindcss.exe` (verified).
- Logo SVGs recolored bronze/gold gradient (`#96733f`→`#c9a464`), serif
  wordmark: `logo-wordmark-dark.svg` (cream text, for dark surfaces),
  `logo-wordmark-light.svg` (espresso text, for light/cream surfaces),
  `favicon.svg`.

## Known consequence for phase 2/3 agents

Until phases 2 and 3 replace every use of the removed classes
(`.glass`, `.btn-glow`, `.text-gradient`, `.card-glow`, `animate-float`,
`animate-glow-pulse`, `animate-gradient-shift`), those elements will
render unstyled (extra classes Tailwind no longer generates just get
ignored — not a hard error, but visibly wrong). Sweep your owned files
for all four before reporting done.
