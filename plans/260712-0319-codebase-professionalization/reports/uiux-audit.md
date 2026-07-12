# UI/UX Audit — Turbotech (Phase 11)

**Date:** 2026-07-13
**Method:** Code/template-level audit (Tailwind token diff, template grep, rendered-HTML inspection via
curl). **No browser screenshot tooling was available in this environment** (no Node.js/chromium-cli
installed) — findings below are derived from source inspection and raw HTML output, not visual
screenshots. If a real visual pass is wanted later, re-run with a browser available.

Read-only per Phase 11 — no code changed. Confirm-dialog + SweetAlert2 toast system was confirmed
already correct in prior work and is NOT re-flagged here.

## Findings (prioritized)

### 1. [Medium] Admin and client use two different design-token systems
- **Where:** `src/css/tailwind-input.css` (client) vs `src/css/admin-tailwind-input.css` (admin)
- **What:** Client defines a custom neutral scale (`--color-ink-50`...`--color-ink-900`) and uses
  `Manrope` for headings. Admin has no `ink-*` scale at all and uses Tailwind's built-in `slate-*`
  palette instead (confirmed: all 22 admin view files use `text-slate-*`/`bg-slate-*`/`border-slate-*`,
  0 use `ink-*`), and uses `Outfit` for headings instead of `Manrope`.
- **Impact:** The two halves of the same product read as visually distinct systems — a user (or staff
  member) moving between storefront and admin sees a different neutral gray tone and different heading
  typeface. Not broken, but inconsistent branding.
- **Proposed fix:** Either (a) accept this as intentional (admin is an internal tool, doesn't need to
  match the storefront), or (b) add the `ink-*` scale + `Manrope` to `admin-tailwind-input.css` and
  swap `slate-*` → `ink-*` across admin views to unify. Option (b) is a larger, mechanical Phase 12 task
  (22 files) — recommend owner decide if it's worth the diff size before scheduling.

### 2. [Medium] Coupon-apply button gives no feedback while the AJAX request is in flight
- **Where:** `view/cart/bill.php:226-258` (`#coupon-apply-btn` click handler)
- **What:** The button stays fully clickable and unstyled during the `$.ajax` call — no
  spinner/disabled state/text change. A slow network leaves the user unsure anything happened, and
  nothing prevents a double-click firing two overlapping requests (server-side coupon logic isn't
  obviously idempotent against this).
- **Proposed fix:** Disable the button + swap its label to "Đang áp dụng..." on click, re-enable in both
  the `success` and `error` callbacks.

### 3. [Low-Medium] Cart quantity edit has the same missing-feedback gap
- **Where:** `view/cart/viewcart.php:116-140` (`saveCart()`, bound via `onchange` on the qty `<input>`)
- **What:** Changing quantity fires an AJAX POST then `location.reload()` on success — the eventual
  reload does provide *eventual* feedback, but there's a dead window between the `onchange` firing and
  the reload where the input just sits there with no visual indicator (no dimming, no spinner).
- **Proposed fix:** Dim/disable the input (or the whole cart-line row) immediately in the `onchange`
  handler, before the AJAX call starts.

### 4. [Low] Empty-state polish is inconsistent between client and admin
- **Where:** `view/product/product-list.php:154-163` (no-search-results) vs
  `admin/view/list_coupon.php:95-99` (no-coupons)
- **What:** Client's empty state is a full treatment — icon (`fa-face-sad-tear`), heading, descriptive
  paragraph, card styling. Admin's is a single bare table-cell line: `Chưa có mã giảm giá nào.` No icon,
  no visual weight.
- **Note:** This is NOT a broken/blank empty state (a real concern was flagged as a risk in the phase
  doc) — it's just less polished than the client's. Low severity, cosmetic only.
- **Proposed fix:** Reuse the client's empty-state card pattern in `list_coupon.php` (and any other
  admin list view without one) for visual consistency — small, mechanical, low-risk Phase 12 item.

### 5. [Low] Admin views have far less mobile-specific tuning than the client
- **Where:** all `admin/view/*.php` vs `view/*.php` + `view/*/*.php`
- **What:** Client templates use `sm:`/`md:`/`lg:` responsive prefixes 297 times total (123 `sm:`
  alone). Admin templates use them only 55 times total (2 `sm:`). Admin is effectively desktop-only in
  practice — the viewport meta tag is present (so it won't break outright on mobile), but layouts aren't
  meaningfully adapted for narrow screens.
- **Impact:** Low priority — admin is realistically used by staff on desktop, not a customer-facing
  concern. Flagging for completeness per the audit brief, not recommending action unless staff have
  actually reported using it on mobile.

### 6. [Low] `list_statistic.php` is missing the horizontal-scroll wrapper every sibling list view has
- **Where:** `admin/view/list_statistic.php` (0 occurrences of `overflow-x-auto`) vs every other
  `admin/view/list_*.php` (each has exactly 1)
- **What:** All other admin list/table views wrap their `<table>` in a `overflow-x-auto` container so
  wide tables scroll horizontally on narrow viewports instead of breaking the layout. `list_statistic.php`
  (which also renders wide data tables — top-sellers, inventory) doesn't have this wrapper.
- **Proposed fix:** Add the same `overflow-x-auto` wrapper used in the other list views. Small,
  mechanical, safe Phase 12 fix.

## Not flagged (checked, found correct)
- Confirm-dialog + SweetAlert2 toast system — already correct per prior work, not re-audited.
- Empty cart state (`view/cart/viewcart.php:104`) and empty search-results state — both have friendly,
  intentional messaging, not broken/blank.
- Viewport meta tag present in both client (`view/head.php`) and admin (`admin/view/header.php`) layouts.

## Owner triage
Please mark which items should proceed to Phase 12:
- [ ] #1 — unify admin/client design tokens (large diff, 22 files)
- [ ] #2 — coupon-apply loading state (small)
- [ ] #3 — cart-qty-edit loading state (small)
- [ ] #4 — admin empty-state polish (small)
- [ ] #5 — admin mobile responsiveness (skip unless staff use mobile)
- [ ] #6 — `list_statistic.php` overflow wrapper (trivial, safe to just do)
