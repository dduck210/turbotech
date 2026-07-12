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
- [ ] Baseline captured (old-switch behavior + error log)
- [ ] `act` checklist == original 39 cases (nothing dropped)
- [ ] Authenticated sweep script run; responses recorded
- [ ] Error-log diff clean (every new entry investigated/resolved)
- [ ] Manual verify: upload, all bill transitions, flashes, CSRF-reject
- [ ] Client route checklist re-run (no client regression)

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
