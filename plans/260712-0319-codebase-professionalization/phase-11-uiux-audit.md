# Phase 11 — UI/UX audit (read-only)

**Context:** [plan.md](plan.md) · Workstream 4 (UI/UX) · **Priority P3** · Owner reviews output · read-only

## Overview
No specific UI complaints have been raised. Rather than guessing fixes, first AUDIT the current UI and
produce a concrete, prioritized issue list for the owner to review. This phase edits NOTHING — it
produces `reports/uiux-audit.md`. Fixes happen in Phase 12 only for owner-approved items.

## Key insights (verified)
- Two Tailwind build inputs exist: `src/css/tailwind-input.css` (client) and
  `src/css/admin-tailwind-input.css` (admin) — check for design-token drift (colors, spacing, fonts)
  between them.
- Confirm-dialog + SweetAlert2 toast system is DONE and correct — do NOT flag or touch it.
- Candidate areas to inspect (from prior context, unconfirmed as problems — audit determines):
  mobile responsiveness consistency client vs admin; loading states on AJAX actions (coupon apply, cart
  qty edit); empty-state handling (coupons table is currently empty — verify the empty state looks
  intentional, not broken); spacing/typography consistency across the two Tailwind builds.

## Requirements
- Deliverable: `reports/uiux-audit.md` — a prioritized list of concrete issues, each with: page/file,
  what's wrong, severity (High/Med/Low), and a proposed fix. No code changes.
- Cover both client and admin, desktop + mobile breakpoints.

## Related code files (read-only)
- Read: `src/css/tailwind-input.css`, `src/css/admin-tailwind-input.css` (token drift).
- Read/inspect rendered: key client pages (home, product list/detail, cart, checkout, account, comment)
  and admin pages (dashboard, list_*, add_*/update_* forms, billdetail).
- Read: AJAX callers (coupon apply, cart qty) to note missing loading/disabled states.

## Implementation steps
1. Compare the two Tailwind inputs for token drift; note divergences.
2. Screenshot/inspect key client + admin pages at desktop + mobile widths; note layout/responsive breaks.
3. Check empty states (empty coupons table, empty cart, no-results search) — intentional vs broken-looking.
4. Check AJAX actions for loading/disabled feedback (coupon apply, cart qty edit).
5. Compile `reports/uiux-audit.md` with prioritized, actionable items. Present to owner for triage.

## Todo
- [ ] Tailwind token-drift comparison
- [ ] Client pages responsive/visual pass (desktop + mobile)
- [ ] Admin pages responsive/visual pass (desktop + mobile)
- [ ] Empty-state review (coupons/cart/search)
- [ ] AJAX loading-state review
- [ ] `reports/uiux-audit.md` compiled + owner triage

## Success criteria
- A prioritized issue list exists; owner has marked which items proceed to Phase 12.

## Risk assessment
- **Low — subjectivity.** Mitigation: describe issues concretely (file + breakpoint + expected vs actual);
  let owner prioritize rather than the agent assuming.
- **Low — scope creep** into a redesign. Mitigation: this is a polish pass, not a redesign — flag only
  rough edges, not aesthetic rewrites.

## Security considerations
- None (read-only).

## Next steps
- Feeds Phase 12. Can run anytime (independent); schedule Phase 12 after admin MVC (10) so admin views are stable.
