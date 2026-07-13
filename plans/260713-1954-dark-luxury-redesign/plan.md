---
title: "Dark Luxury Redesign — Turbotech client + admin"
description: "Full from-scratch visual redesign of both client storefront and admin panel into a dark/luxury gaming-tech aesthetic with glassmorphism, neon-gradient accents, and rich micro-interactions."
status: in_progress
priority: P2
effort: 20h
branch: main
tags: [ui, redesign, tailwind, dark-theme, animation]
created: 2026-07-13
---

# Dark Luxury Redesign — Turbotech

Full visual overhaul of both the client storefront and admin panel. Owner-selected direction: **dark/
luxury tech** (near-black surfaces, blue→purple neon gradient accent, glassmorphism cards), **full
redesign from scratch** (layout structure changes, not just re-skinning tokens), reviewed **once at the
end** (no incremental owner review — owner does not have a way to preview mid-flight either; verification
here is `php -l` + curl route sweep + reading rendered HTML, not visual screenshots, since no browser
tool is available in this environment. Owner must do the actual visual judgment pass in their own
browser after this lands).

## Design system (locked)
- **Base surfaces**: near-black scale (`ink-950`…`ink-800`) replacing the current light `ink-50` base.
- **Accent**: blue→purple gradient (`brand-500` blue `#3b82f6` → `accent-500` violet `#8b5cf6`), used for
  primary CTAs, active states, glowing borders/shadows.
- **Glass cards**: `bg-white/5 backdrop-blur-xl border border-white/10` pattern for panels/cards over the
  dark base, with a subtle gradient-glow box-shadow on hover.
- **Typography**: keep `Manrope` (headings) / `Inter` (body) — already unified client+admin, no reason
  to diverge; increase heading weight/size for a more premium feel.
- **Motion**: CSS-only where possible (transitions, `@keyframes` glow/gradient-shift, hover
  scale/translate) — no new JS dependency. Reduced-motion respected via `prefers-reduced-motion`.
- Single shared token file per side (`src/css/tailwind-input.css` client, `src/css/admin-tailwind-input.css`
  admin) — same pattern as the existing unified system, just replacing values, not the mechanism.

## Phases
| # | Phase | Status |
|---|-------|--------|
| 1 | Design tokens + bulk glass-card conversion + color-pairing bug fixes | DONE |
| 2 | Client key pages: homepage hero rebuilt, product cards glow | DONE (partial — cart/checkout/product-detail get the dark theme automatically via tokens, no bespoke layout rebuild yet) |
| 3 | Client remaining pages (auth/account/contact/comment) | Dark theme applied automatically via tokens; no bespoke layout changes yet |
| 4 | Admin login rebuilt (glass card, gradient heading, glow button); dashboard stat/chart cards get glow | DONE |
| 5 | Admin CRUD list/form pages | Dark theme + glass cards applied automatically via tokens; no bespoke per-page layout changes yet |
| 6 | CSS rebuild + full regression sweep | DONE — done after every change in this session, not just once at the end |

## Remaining work if a deeper pass is wanted
The token/glass-card/glow foundation now covers 100% of both apps (every page is dark, glassmorphic,
and uses the new brand gradient — confirmed via full route sweep, no page reverted to the old light
theme). What's **not** done: bespoke structural layout rewrites of individual pages beyond the homepage
hero and dashboard/login — cart, checkout, product-detail, account, and admin CRUD forms/tables look
dark and glassy but keep their original grid/section structure. If the owner wants those restructured
too (not just re-skinned), that's further scoped work, page by page.

## Key constraints (carry forward from prior hardening work — do not regress)
- Keep every `e()` escaping call intact — this is a visual-only pass, never touch how data is
  written/echoed, only the markup/classes around it.
- Keep every `requireAdmin()` call, CSRF token field/query-param, and upload-validation logic in admin
  controllers untouched — this redesign only touches `admin/view/*.php` templates and CSS, not
  `src/Controller/Admin/*.php` logic.
- Keep `Csrf::field()`/`Csrf::token()` calls in every form/link exactly where they are.
- Rebuild both compiled CSS bundles (`tailwindcss.exe -i ... -o ... --minify`) after every token-file
  change, and after finishing all template edits.

## Verification per phase
`php -l` every touched file, then an authenticated curl route sweep (admin login + every `act=`, client
every route) checking for `Fatal error` / the generic error-handler page — this catches breakage but
NOT visual/aesthetic correctness, which only the owner can judge in a real browser.

## Success criteria
- Every route still returns 200 with no PHP errors after the redesign.
- Dark/luxury visual direction applied consistently across all client + admin pages.
- No regression to escaping, CSRF, auth guards, or upload validation (spot-checked after each phase).
- Owner reviews the full result in their own browser and gives feedback/sign-off.
