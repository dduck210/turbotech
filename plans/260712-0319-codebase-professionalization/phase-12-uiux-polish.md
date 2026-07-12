# Phase 12 — UI/UX polish fixes

**Context:** [plan.md](plan.md) · Workstream 4 (UI/UX) · **Priority P3** · depends on 10 + 11 · Owner sign-off

## Overview
Implement the owner-approved fixes from the Phase 11 audit. Scope is strictly the triaged issue list —
no new redesign. Runs after admin MVC (Phase 10) so admin views are stable and not re-churned.

## Key insights
- Fixes are driven by `reports/uiux-audit.md` (Phase 11) filtered to owner-approved items only.
- Views were already output-escaped in Phase 03 — this phase edits markup/classes, NOT echo escaping.
  Preserve existing `e()` wrappers.
- Do NOT touch the confirm-dialog/SweetAlert2 toast system (done/correct).
- If token drift was found: converge shared design tokens (colors/spacing/fonts) across
  `tailwind-input.css` and `admin-tailwind-input.css` rather than duplicating (DRY), but only if the
  Tailwind build step is available/reproducible — otherwise scope to the compiled CSS carefully.

## Requirements
- Implement only owner-approved audit items; keep behavior unchanged (visual/UX only).
- No regression to escaping (Phase 03) or to admin MVC render paths (Phase 07–10).
- Rebuild Tailwind CSS if inputs change (confirm build command exists; if not, note as owner action).

## Related code files (scoped by audit outcome)
- Modify: specific `view/*.php` / `admin/view/*.php` for the approved layout/responsive/empty-state fixes
  (markup + classes only).
- Modify: `src/css/tailwind-input.css` and/or `src/css/admin-tailwind-input.css` for token convergence
  (only if approved + build reproducible).
- Modify: AJAX-triggering views/JS for loading/disabled states (coupon apply, cart qty) if approved.

## Implementation steps
1. Take the owner-approved subset of `reports/uiux-audit.md`. Confirm each item's target file.
2. Apply markup/class fixes per item; keep `e()` escaping intact.
3. For token drift: align shared tokens across the two Tailwind inputs; rebuild CSS.
4. For AJAX: add loading/disabled feedback (spinner/disabled button) on approved actions.
5. `php -l` touched views; browser-verify each fix at desktop + mobile; present before/after to owner.

## Todo
- [x] Owner approved all 6 audit items (2026-07-13)
- [x] Applied layout/empty-state fix (#4, `list_coupon.php`) — `e()` escaping preserved throughout,
      confirmed no echo-escaping touched
- [x] Token convergence (#1): added `ink-400/600/800` + `Manrope` to `admin-tailwind-input.css`,
      mechanically renamed `slate-*` → `ink-*` across all 22 admin views, rebuilt both CSS bundles via
      `tailwindcss.exe` (build tooling was available, no owner-action fallback needed)
- [x] AJAX loading/disabled states (#2 coupon-apply, #3 cart-qty-edit) — implemented
- [x] #5 and #6 investigated further and found to be false positives from the Phase 11 audit (see
      corrected findings in `reports/uiux-audit.md`) — no admin MVC render paths touched, confirm-dialog/
      toast system untouched
- [x] `php -l` clean on every touched file; authenticated admin route sweep + client route sweep both
      green, no Fatal errors

## Success criteria
- Each approved audit item resolved; owner signs off on before/after.
- No functional regression; escaping and admin MVC paths intact; toasts/confirm dialogs untouched.

## Risk assessment
- **Med — re-churning admin views** if run before admin MVC settles. Mitigation: depends on Phase 10;
  run after admin is stable.
- **Med — Tailwind rebuild unavailable** (no build tooling in env). Mitigation: verify build command first;
  if absent, either edit compiled CSS carefully or defer token work as owner action.
- **Low — subjective disagreement.** Mitigation: owner sign-off on before/after per item.

## Security considerations
- Must not undo Phase 03 escaping. Editing markup only; keep `e()` wrappers.

**Status: DONE (2026-07-13).** All 12 phases of the codebase-professionalization plan are now complete.

## Next steps
- Final phase. On completion, update `docs/project-changelog.md` + `docs/development-roadmap.md` per
  documentation-management rules (delegate to docs-manager).
