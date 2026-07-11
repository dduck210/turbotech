# Phase 06 — Local regression verification

**Context:** [plan.md](plan.md) · depends on [phase-02](phase-02-env-driven-db-config.md),
[phase-03](phase-03-durable-sessions.md), [phase-04](phase-04-blob-uploads.md)

## Overview
- **Priority:** P1 (gate before any deploy)
- **Status:** pending
- **Description:** Prove every changed layer works on the existing XAMPP setup — both with local
  defaults (dev unchanged) and with cloud/env values — before touching Vercel. Regression risk is
  HIGH because phases 02-04 touch auth, cart, checkout, admin CRUD, and rendering.

## Key insights
- All changes were designed with local fallback defaults (empty DB env → localhost; `SESSION_DRIVER`
  default `files`; unset Blob token → local disk). So two test passes are needed: **(A) defaults
  pass** (nothing set — must behave exactly like today) and **(B) cloud pass** (env pointed at cloud
  DB + `SESSION_DRIVER=db` + Blob token — proves the Vercel path works before Vercel).

## Test matrix
| Area | Default pass (A) | Cloud/env pass (B) | Method |
|------|------------------|--------------------|--------|
| DB connect (`Database`) | local `codemoi2` | cloud MySQL | `php -r` SELECT 1 both paths |
| DB connect (`pdo.php`) | local | cloud | admin page load |
| Session persist | files (login works) | db rows created, login+cart persist | login → add cart → new process |
| Product image upload | disk write + render | Blob URL + render | admin add/update product |
| Avatar upload | disk write + render | Blob URL + render | edit account |
| Existing image render | unchanged | migrated URLs render | browse storefront |
| Checkout + coupon | works | works | end-to-end order |
| QR payment page | renders (VietQR) | renders | open qr page |

## Requirements
- Functional: full user journey (browse → cart → checkout+coupon → order) and admin journey (login →
  product CRUD with image) pass in BOTH modes.
- Non-functional: `php -l` clean on every modified/created PHP file.

## Related code files (read/execute only — no source edits in this phase)
- Exercise: `public/index.php`, `public/admin/index.php`, `src/Core/{Config,Database,DbSessionHandler,
  Blob}.php`, `admin/model/pdo.php`, `src/Controller/AccountController.php`, `scripts/
  migrate-uploads-to-blob.php`.

## Implementation steps
1. `php -l` sweep on all changed/new PHP files.
2. **Pass A (defaults):** no DB/session/blob env set. Run XAMPP; execute full test matrix column A.
   Confirm byte-for-byte-equivalent behavior to pre-change (login, cart, upload to disk, render).
3. **Pass B (cloud):** set local `.env` with cloud DB creds, `SESSION_DRIVER=db`, Blob token. Create
   `sessions` table locally (or point at cloud). Run migration script (dry-run then execute against a
   COPY / the cloud DB). Execute test matrix column B.
4. Verify session rows appear in `sessions` table; verify uploaded image is a Blob URL and renders;
   verify migrated existing images render.
5. Delegate to `tester` agent for a structured pass if available; fix + re-run until green.

## Todo
- [ ] `php -l` all changed/new files
- [ ] Pass A: full matrix with defaults (dev-unchanged proof)
- [ ] Pass B: full matrix with cloud env
- [ ] Migration script dry-run + execute + verify renders
- [ ] Session rows verified in DB
- [ ] All green (no failing checks) before Phase 07

## Success criteria
- Both passes complete the full user + admin journey with zero regressions.
- Session persistence and Blob upload confirmed working in Pass B (the actual Vercel code path).

## Risk assessment
- **HIGH — hidden `$_SESSION`/render coupling** surfaces only end-to-end. Mitigation: full journey
  test, not unit-only.
- **MED — testing against cloud DB mutates data.** Mitigation: run Pass B migration/order tests
  against a throwaway copy or clearly-marked test data; don't corrupt production dataset.
- **LOW — local PHP version** (8.0.30) differs from chosen Vercel runtime. Mitigation: if feasible,
  run Pass A/B on the target PHP version too.

## Security considerations
- Do not commit the local `.env` used for Pass B.

## Next steps
- Gate: only proceed to Phase 07 when both passes are green.
