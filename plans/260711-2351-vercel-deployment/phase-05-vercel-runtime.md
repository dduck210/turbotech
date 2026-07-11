# Phase 05 — vercel.json + PHP runtime + env vars

**Context:** [plan.md](plan.md) · depends on [phase-02](phase-02-env-driven-db-config.md),
[phase-03](phase-03-durable-sessions.md), [phase-04](phase-04-blob-uploads.md)

## Overview
- **Priority:** P1
- **Status:** pending
- **Description:** Add `vercel.json` using a community PHP runtime, route client vs admin vs static
  assets, ensure `composer install` runs at build, and enumerate the env vars to set in the dashboard.

## Key insights
- No `vercel.json` and no Vercel CLI yet (verified). PHP has no official Vercel runtime → use community
  `vercel-php` builder (`vercel-php@0.7.x` / current). Pin the version.
- `vendor/` is gitignored (verified `.gitignore:1`) → Composer autoload absent at build unless
  `composer install` runs. `vercel-php` runs composer if `composer.json` present at project root
  (verified present). `composer.json` `require:{}` has no PHP constraint — set one matching the
  runtime's PHP (dev is PHP 8.0.30; pick a runtime PHP the app is tested on, e.g. 8.1/8.2, and run
  Phase 06 on that version locally if possible).
- Entrypoints: `public/index.php` (client) and `public/admin/index.php` (admin). Static assets under
  `public/assets/*` (verified dir exists). Access boundary on Vercel = these routes, NOT `.htaccess`
  (root `.htaccess`/`public/.htaccess` are Apache-only, harmless/ignored on Vercel — keep as-is).

## Data flow
Request ──> `vercel.json` routes:
- `/admin/(.*)` ──> `public/admin/index.php`
- `/assets/(.*)` ──> static file `public/assets/$1`
- `/(.*)` ──> `public/index.php`
Build: `composer install` → `vendor/` bundled into the function.

## Requirements
- Functional: all client routes hit `public/index.php`; `/admin*` hits admin; `/assets/*` served
  statically; `vendor/` present at runtime.
- Non-functional: pinned runtime + PHP version; env vars available to functions.

## Related code files
- Create: `vercel.json` — `functions`/`builds` with `vercel-php` for the two PHP entrypoints, routes
  as above, and a static build for `public/assets`.
- Create: `.vercelignore` — exclude `plans/`, `.claude/`, `Turbotech.sql`, local `.env`, dev-only dirs
  to shrink the deployment.
- Modify: `composer.json` — add `"require": {"php": ">=8.1"}` (or chosen runtime version) + ensure
  autoload maps unchanged.

## Env vars to set in Vercel dashboard (Project → Settings → Environment Variables)
From current local `.env` + new keys (mirror everything the app reads):
- DB (Phase 02): `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, optional `DB_SSL_CA`
- Session (Phase 03): `SESSION_DRIVER=db`
- Blob (Phase 04): `BLOB_READ_WRITE_TOKEN`
- SMTP (existing): `SMTP_HOST`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_PORT`, `SMTP_FROM_EMAIL`,
  `SMTP_FROM_NAME`
- Bank/VietQR (existing): `BANK_CODE`, `BANK_ACCOUNT_NO`, `BANK_ACCOUNT_NAME`

Note: `Config::env()` reads a `.env` file first, then `getenv()`. On Vercel there is no `.env` file
(gitignored) → it falls through to `getenv()` which reads dashboard env vars. Verify `getenv()` sees
Vercel env (it should; Vercel injects into process env). [Confirm in Phase 07 smoke.]

## Implementation steps
1. Write `vercel.json` with `vercel-php` builder pinned, routes for admin/assets/catch-all, and
   composer install at build.
2. Write `.vercelignore`.
3. Set `composer.json` php constraint; `composer install` locally to refresh `vendor/` on that version.
4. Document the full env var list (above) for the user / for `vercel env add`.
5. Local `vercel dev` (after CLI install in Phase 07) optional sanity — or defer all Vercel exec to 07.

## Todo
- [ ] Create `vercel.json` (pinned runtime, routes)
- [ ] Create `.vercelignore`
- [ ] Set `composer.json` PHP constraint + refresh `vendor/`
- [ ] Finalize env var list
- [ ] User creates env vars in dashboard (or agent via `vercel env` in Phase 07)

## Success criteria
- `vercel.json` validates; build runs `composer install`; routes resolve client/admin/assets.
- (Verified live in Phase 07.)

## Risk assessment
- **HIGH — community PHP runtime unsupported/broken** for our PHP version. Mitigation: pin a known-good
  `vercel-php` + PHP version; **fallback host** documented: if runtime fails, deploy to
  Railway/Render (full PHP, keeps all Phase 02-04 changes which are host-agnostic) — the
  re-architecture is not wasted.
- **MED — `getenv()` vs Vercel env** injection quirk (some runtimes populate `$_ENV`/`$_SERVER` not
  `getenv()`). Mitigation: if `getenv()` misses, extend `Config::env()` to also check
  `$_ENV`/`$_SERVER` (small, backward-compatible). Test in Phase 07.
- **MED — `vendor/` not built** if composer step skipped. Mitigation: confirm builder runs composer;
  else add explicit build command.
- **LOW — deployment size** bloated by `plans/`, `.sql`. Mitigation: `.vercelignore`.

## Security considerations
- All secrets via dashboard env, never committed. Confirm `.env` in `.vercelignore` (it's gitignored
  already, but not uploaded).

## Rollback
- Remove/rename `vercel.json` to disable Vercel build; app still runs on Apache/XAMPP unchanged.

## Next steps
- Blocks Phase 07 (deploy). Requires Phase 06 green first.
