---
title: "Deploy Turbotech/Codemoi PHP app to Vercel (serverless re-architecture)"
description: "Re-architect a classic Apache+PHP+MySQL e-commerce app for Vercel serverless: env-driven DB, durable sessions, blob uploads, runtime config, deploy."
status: pending
priority: P2
effort: 14h
branch: main
tags: [deployment, vercel, php, serverless, migration]
created: 2026-07-11
---

# Vercel Deployment Plan — Turbotech/Codemoi

Re-architect the legacy Apache+PHP+MySQL app to run on Vercel serverless. User has confirmed
"try Vercel, accept re-architecture." Target account: https://vercel.com/duongduc210s-projects

## Core problem
Vercel = stateless functions, no persistent disk, no native PHP runtime, no hosted MySQL.
App currently assumes: hardcoded localhost MySQL, file-based sessions, local-disk uploads,
Apache `.htaccess` routing. Each assumption is broken by one phase below.

## Phases

| # | Phase | Status | Depends on | Owner action needed? |
|---|-------|--------|-----------|----------------------|
| 01 | [Provision external MySQL + migrate data](phase-01-external-mysql.md) | pending | — | YES (create cloud DB acct) |
| 02 | [Env-driven DB config](phase-02-env-driven-db-config.md) | pending | 01 | no (agent) |
| 03 | [Durable DB-backed session handler](phase-03-durable-sessions.md) | pending | 02 | no (agent) |
| 04 | [Migrate uploads to Vercel Blob](phase-04-blob-uploads.md) | pending | 02 | YES (create Blob store token) |
| 05 | [vercel.json + PHP runtime + env vars](phase-05-vercel-runtime.md) | pending | 02,03,04 | no (agent) |
| 06 | [Local regression verification](phase-06-local-verification.md) | pending | 02,03,04 | no (agent) |
| 07 | [Deploy + smoke test](phase-07-deploy-smoke-test.md) | pending | 05,06 | YES (`vercel login`) |

## Data flow (target state)
```
Browser ──> vercel.json routes ──> public/index.php (client) | public/admin/index.php (admin)
                                          │
              ┌───────────────────────────┼───────────────────────────┐
              ▼                            ▼                           ▼
   Database.php / pdo.php          DbSessionHandler             Blob upload helper
   (env DSN → cloud MySQL)   (session rows in cloud MySQL)   (Vercel Blob → returns URL)
              │                            │                           │
              ▼                            ▼                           ▼
        cloud MySQL <──── same connection ──── sessions table    stored URL in img_pro / img_user / etc.
```

## File ownership (no two phases touch same file)
- Phase 02: `src/Core/Config.php`, `src/Core/Database.php`, `admin/model/pdo.php`, `.env.example`, `.env`
- Phase 03: NEW `src/Core/DbSessionHandler.php`; session bootstrap edits in `public/index.php`, `public/admin/index.php` (session line only)
- Phase 04: NEW `src/Core/Blob.php`; upload edits in `public/admin/index.php` (upload blocks only), `src/Controller/AccountController.php`; NEW `scripts/migrate-uploads-to-blob.php`
- Phase 05: NEW `vercel.json`, `.vercelignore`; `composer.json` (php version constraint only)
- Phase 06/07: no source edits (verification + deploy only)

Conflict note: Phase 03 and Phase 05 both mention `public/admin/index.php` and `public/index.php` but
touch disjoint regions (session bootstrap vs. nothing in 05). Phase 04 touches upload blocks in
`public/admin/index.php`. Sequence 03 → 04 to avoid overlap on that file; do not parallelize 03/04.

## Global risks
- **HIGH — regression across auth/cart/checkout/admin:** phases 02/03/04 touch the whole app's
  data + session + upload layer. Mitigation: Phase 06 full local regression on XAMPP before any deploy;
  all env reads keep current hardcoded values as local fallback defaults.
- **HIGH — PHP is not officially supported on Vercel:** relies on community `vercel-php` runtime.
  Mitigation: pin runtime version in Phase 05; if runtime fails, fallback host (Railway/Render full PHP)
  documented in Phase 05 risk section.
- **MED — SMTP port 465 may be blocked** on serverless egress. Mitigation: post-deploy test in Phase 07;
  fallback to port 587/API-based mail flagged there. No code change now.
- **MED — per-request PDO connections** (Database.php opens one connection per call) multiply under
  serverless + cloud MySQL latency/connection-limit. Mitigation: pick a DB with generous connection
  limits / built-in pooling (Phase 01); note as perf item, not a blocker.

## Owner (user) action items — cannot be automated by agent
1. Phase 01: create managed MySQL account, hand back connection creds.
2. Phase 04: create Vercel Blob store, hand back `BLOB_READ_WRITE_TOKEN`.
3. Phase 05: set env vars in Vercel dashboard (or approve agent doing it via CLI once linked).
4. Phase 07: run `vercel login` interactively (browser auth).

Everything else (code changes, local testing, migration scripts, `vercel link`/`vercel deploy` once
authed) the agent executes directly.

## Success criteria (measurable)
- Live Vercel URL serves the storefront; login, browse, add-to-cart, checkout-with-coupon,
  admin image upload, and QR payment page all work against cloud MySQL + Blob.
- Local XAMPP still works unchanged (env defaults) — verified in Phase 06.
