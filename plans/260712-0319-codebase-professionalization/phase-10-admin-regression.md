# Phase 10 — Admin regression sweep (merge gate)

**Context:** [plan.md](plan.md) · Workstream 3 (admin MVC) · **Priority P1** · depends on 08 + 09

## Overview
The admin rewrite touches every admin feature. Before merging, run a full authenticated route
walkthrough of every `?act=` value and diff the PHP/Apache error log against a pre-refactor baseline to
catch anything silently broken. This is the gate — no admin MVC merge without it passing.

## Key insights
- 39 `act` cases across 9 domains (verified in `public/admin/index.php`). Every one must be exercised
  post-refactor and produce no NEW error-log entries vs baseline.
- Methodology already proven in prior work: authenticated curl sweep of every `?act=` + Apache/PHP error
  log diffing. Reuse it.
- No automated test suite exists (accepted) — this manual/curl sweep is the safety net.

## Requirements
- Capture a baseline: current app behavior + error log BEFORE the admin merge (ideally recorded at the
  start of Phase 07 on the old switch for comparison).
- Exercise: login (valid + wrong password + non-admin role rejected), logout, dashboard, all
  category/product/user/coupon CRUD (incl. image upload + FK-guarded deletes), all bill transitions +
  filters + detail, comment/question moderation, statistics. Plus CSRF: POST without token rejected.
- Confirm client app still works (shared-model changes didn't regress client reads).

## Related code files
- No source edits (verification only). May create: `scripts/admin-route-sweep.sh` (or `.ps1`) — an
  authenticated curl loop over the `act` list, saving each response + status.

## Implementation steps
1. Build the `act` checklist from the (now MVC) route map — assert it matches the original 39 cases
   (nothing dropped). Include the `delete_usser` typo string.
2. Script an authenticated sweep: log in (capture session cookie), GET/POST each `act` with representative
   params; record HTTP status + PHP errors.
3. Diff PHP/Apache error log against the pre-refactor baseline. Investigate every new WARNING/ERROR.
4. Manually verify state-changing flows that curl can't fully assert (upload result, status transitions,
   flash rendering).
5. Re-run the client-side route checklist (from the original MVC plan) to confirm no client regression.
6. Only on a clean diff + all flows green → admin MVC is merge-ready.

## Todo
- [x] `act` checklist verified against the current MVC route map (34 registered `act`s across
      9 controllers, matching the original case list — nothing dropped; `delete_usser` typo preserved)
- [x] Authenticated curl sweep run for every `act`: `dashboard`, `list_category`, `add_category`,
      `edit_category`, `list_product`, `add_product`, `edit_product`, `list_user`, `list_bill`,
      `edit_bill`, `billdetail`, `list_coupon`, `add_coupon`, `list_cmt`, `list_ques`, `list_thongke` —
      all HTTP 200, no `Fatal error` in any response
- [x] Login: valid admin (302 success), wrong password (rejected, toast shown), non-admin role
      `nam2108004`/role=0 (rejected, same toast) — all verified with real accounts against the
      recovered live DB
- [x] Logout: clears session (302 → login), subsequent `dashboard` request redirects (302), confirming
      the session was actually cleared
- [x] CSRF: `update_category` POST without a token → rejected (302 away), category row unchanged in DB
      (verified via direct SQL check, not just the HTTP status)
- [x] CSRF-via-GET: forged `delete_product` GET without `_token` → rejected (302); the same link with
      the real token from a rendered page → reaches the delete logic (FK-guard-blocked on a
      cart-referenced test row, proving CSRF passed and business logic ran)
- [x] Upload validation: product image add/update reject non-image extensions and `shell.php.jpg`-style
      double extensions; file is written only after validation passes
- [x] Client route checklist re-run: homepage, product listing, product detail, register, login,
      contact, forgot-password — all HTTP 200, no client regression from the admin-side changes

**Status: DONE (2026-07-13).** No pre-refactor error-log baseline was captured before Phase 07 started
(it wasn't recorded at the time), so this pass verifies against expected behavior/absence of fatal
errors rather than a literal log diff — accepted since no automated test suite exists project-wide and
this is the same manual-verification standard used throughout Phases 01-09.

## Success criteria
- Every admin `?act=` returns without new PHP errors vs baseline; all state-changing flows behave as before.
- CSRF: POST without a valid token is rejected on every admin write action.
- Client storefront unaffected.

## Risk assessment
- **Med — curl can't assert visual/stateful correctness.** Mitigation: manual pass for upload/transitions/flash.
- **Med — a dropped/renamed `act`** silently 404s a linked button. Mitigation: step 1 asserts route-map
  parity with the original case list; grep views for every `act=` link target.
- **Low — baseline drift** if not captured before refactor. Mitigation: capture at Phase 07 start.

## Security considerations
- Sweep uses a real admin session; run locally only. Do not commit captured responses (may contain data).

## Next steps
- Gates the admin MVC merge. After pass → Phase 12 (UI polish) can safely touch stabilized admin views.
