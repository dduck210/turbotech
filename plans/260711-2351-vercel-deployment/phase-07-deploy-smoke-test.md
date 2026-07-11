# Phase 07 — Deploy + smoke test

**Context:** [plan.md](plan.md) · depends on [phase-05](phase-05-vercel-runtime.md),
[phase-06](phase-06-local-verification.md)

## Overview
- **Priority:** P1
- **Status:** pending
- **Description:** Install Vercel CLI, authenticate (user-interactive), link to the target account,
  deploy, set env, and smoke-test the live URL end-to-end.

## Key insights
- No Vercel CLI installed (verified). `vercel login` is browser-interactive → **must be run by the
  user**; an agent cannot complete the OAuth handshake. Everything after auth (`link`, `env`,
  `deploy`) the agent can run.
- Target account/scope: `duongduc210s-projects` (https://vercel.com/duongduc210s-projects).
- Phase 06 must be green first (deploy only verified code).

## Data flow
Local repo ──`vercel deploy`──> Vercel build (`composer install` + `vercel-php`) ──> functions
(`public/index.php`, `public/admin/index.php`) + static `assets` ──> live URL ──> cloud MySQL + Blob.

## Requirements
- Functional: live URL serves storefront + admin; full journeys pass against cloud infra.
- Non-functional: all env vars present in the deployed environment.

## Implementation steps
1. Install CLI: `npm i -g vercel` (agent can run).
2. **[USER ACTION]** User runs `vercel login` interactively and confirms authed (`vercel whoami`).
3. Agent: `vercel link --scope duongduc210s-projects` (select/create project).
4. Agent: set env vars from Phase 05 list via `vercel env add` for `production` (and `preview`), OR
   confirm user set them in dashboard. Include DB, `SESSION_DRIVER=db`, Blob token, SMTP, bank keys.
5. Agent: `vercel deploy` (preview) → inspect build logs (composer ran? runtime resolved?
   `getenv()` sees env?). Fix config issues (Phase 05 fallback for `$_ENV`/`$_SERVER` if `getenv`
   misses). Then `vercel deploy --prod`.
6. **Smoke test the live URL:**
   - Browse products (renders; images from Blob/existing).
   - Register/login (session persists across requests → DB-backed sessions working).
   - Add to cart, view cart (cart persists).
   - Checkout applying a coupon (order created in cloud DB).
   - Admin login → add/update a product WITH image upload (Blob PUT + render).
   - Open QR payment page (VietQR external URL renders).
   - Trigger an email path (e.g. verification) → confirm SMTP send OR log the port-465 block risk.

## Todo
- [ ] `npm i -g vercel`
- [ ] User runs `vercel login` (interactive)
- [ ] `vercel link` to duongduc210s-projects
- [ ] Set all env vars (deployed env)
- [ ] `vercel deploy` preview → fix → `--prod`
- [ ] Smoke test: browse/login/cart/checkout+coupon/admin-upload/QR/email
- [ ] Record live URL + any failing item

## Success criteria
- Live production URL completes every smoke-test step. Session + upload + DB all function against
  cloud. Any failure has a documented cause + next action.

## Risk assessment
- **HIGH — build fails on PHP runtime.** Mitigation: Phase 05 pinned version; fallback host
  (Railway/Render) if unrecoverable — Phase 02-04 changes are host-agnostic and carry over.
- **MED — SMTP port 465 blocked** on Vercel egress → verification emails fail. Mitigation: test in
  step 6; fallback to 587 or an HTTP mail API (config-only via existing `SMTP_*` env). Not a code
  change unless API route chosen.
- **MED — env not visible to `getenv()`.** Mitigation: Phase 05 `Config::env()` extension to read
  `$_ENV`/`$_SERVER`; redeploy.
- **LOW — cold-start latency** (per-request PDO + cloud RTT). Mitigation: note as perf follow-up.

## Security considerations
- Confirm `/admin` is not unintentionally public — legacy admin auth is the only gate; verify it
  still works post-deploy. Secrets only in Vercel env.

## Rollback
- `vercel rollback` to previous deployment, or remove `vercel.json` and continue serving from
  XAMPP/Apache. DB + Blob are external and unaffected by rolling back the app.

## Next steps
- On success: update `docs/project-changelog.md` + `docs/development-roadmap.md` (deployment
  milestone) via `docs-manager`. Record live URL.
