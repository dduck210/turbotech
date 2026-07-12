---
title: "Codebase Professionalization — Turbotech/Codemoi (security, cleanup, admin MVC, UI polish)"
description: "Harden security (passwords/CSRF/XSS/OTP), unify DB layer, refactor procedural admin to MVC matching the client, and polish UI/UX — phased, low-regression, on legacy PHP+MySQL."
status: pending
priority: P1
effort: 30h
branch: main
tags: [security, refactor, mvc, cleanup, ui-ux, php, legacy]
created: 2026-07-12
---

# Codebase Professionalization — Turbotech/Codemoi

Master plan across four user-chosen workstreams: (1) security hardening, (2) code
standardization/cleanup, (3) admin-panel → MVC refactor (match client architecture), (4) UI/UX polish.
Legacy PHP + MySQL (`codemoi2`), Apache, `public/` webroot. Client side already MVC
(`Codemoi\` PSR-4 → `src/`); admin side is a 627-line procedural `switch` in `public/admin/index.php`.

## Guiding principles
- **YAGNI/KISS/DRY.** Reuse the existing `Config::env()` reader and `Core\Database`; do NOT invent
  new frameworks. No new runtime dependencies. Correction (found in Phase 03): a standalone
  `composer.phar` IS available at `C:\xampp\htdocs\composer.phar` — `dump-autoload` works fine
  (used to wire `src/Core/helpers.php` via Composer's `files` autoload entry).
- **Strangler + always-runnable.** Every phase leaves the app runnable and manually testable on XAMPP.
  Lint with `C:\xampp\php\php.exe -l`. No automated test suite exists.
- **Security first, refactor last.** Fix P1 vulns while the code is small and known; do the risky
  admin rewrite only after the shared data layer is unified (avoids duplicating migration work).

## Phase table (recommended execution order)

| # | Phase | WS | Priority | Depends on | Owner action? |
|---|-------|----|----------|-----------|---------------|
| 01 | [Password hashing + migration](phase-01-password-hashing.md) | 1 | P1 | — | DONE |
| 02 | [CSRF protection](phase-02-csrf-protection.md) | 1 | P1 | — | DONE |
| 03 | [XSS output-escaping audit + fix](phase-03-xss-escaping.md) | 1 | P1 | — | DONE |
| 04 | [OTP hardening + redirect-exit fix](phase-04-otp-hardening.md) | 1 | P1 | — | DONE |
| 05 | [DB layer env-config unification](phase-05-db-config-unification.md) | 2 | P2 | — | DONE |
| 06 | [Error-handling FK guards + dead-table cleanup](phase-06-error-handling-cleanup.md) | 2 | P2 | — | DONE |
| 07 | [Admin MVC foundation](phase-07-admin-mvc-foundation.md) | 3 | P2 | 05 | DONE |
| 08 | [Admin controllers: auth/dashboard + CRUD](phase-08-admin-controllers-crud.md) | 3 | P2 | 07 | DONE |
| 09 | [Admin controllers: bill/moderation/stats](phase-09-admin-controllers-bill-mod.md) | 3 | P2 | 07 | DONE |
| 10 | [Admin regression sweep](phase-10-admin-regression.md) | 3 | P1 | 08,09 | no |
| 11 | [UI/UX audit (read-only)](phase-11-uiux-audit.md) | 4 | P3 | — | YES (review issue list) |
| 12 | [UI/UX polish fixes](phase-12-uiux-polish.md) | 4 | P3 | 10,11 | YES (review before/after) |

## Sequencing rationale
- **01–04 (security)** need only current code — start immediately, any order. 03 (XSS) must land
  **before** 07–09 touch admin so the view-escaping pass is done once (admin MVC keeps `admin/view/*`
  in place, it only changes render call sites).
- **05 (DB env)** unblocks admin MVC: 09 deletes `admin/model/pdo.php` and moves admin onto
  `Core\Database`; that layer must be env-ready first. 05 also **satisfies Vercel plan phase-02**
  (see cross-plan note) — do it once.
- **06** adds FK-violation guards to the current procedural admin `switch` (quick robustness win, do
  not wait for the risky rewrite); 07–09 preserve those guards when porting each delete action.
- **07 → 08 → 09 → 10 (admin MVC)** is the biggest/highest-risk block; strangler-style, controllers
  ported per domain, then a full authenticated regression sweep gates the merge.
- **11 (audit)** is read-only, schedulable anytime; **12 (fixes)** runs after admin MVC so admin
  views are stable and not re-churned.

## Data flow (target state)
```
Client:  public/index.php ─> Codemoi\Core\Router ─> Controller\* ─> Model\* ─┐
                                                                             ├─> Core\Database ─> MySQL codemoi2
Admin:   public/admin/index.php ─> Router ─> Admin\Controller\* ─> Model\* ──┘   (env-driven DSN)
                    │                    │
              CSRF verify          AdminController base: role='1' guard + centralized flash
              (shared token)
Views: view/* (client) + admin/view/* (admin) rendered by Core\View — ALL DB/user output escaped.
Passwords: password_hash on write / password_verify on check (both client + admin auth).
```

## File ownership map (prevent collisions)
Sequential-only conflicts are marked; **do not parallelize** a later phase over an earlier one on the same file.

| File(s) | Phases | Rule |
|---------|--------|------|
| `src/Model/User.php`, `admin/model/user.php` | 01 | password write/read paths |
| `public/index.php` (client front controller — form dispatch), view `*` forms | 02, 03 | 02 wires CSRF verify; 03 escapes output — disjoint regions |
| `public/admin/index.php` | 02 (CSRF), 06 (FK guards), 07–09 (rewrite) | DONE — 07–09 fully superseded the old switch; strangler scaffold retired in 09 |
| `view/*.php`, `admin/view/*.php` | 03 (escape), 12 (styling) | sequential; 03 edits echo output, 12 edits markup/classes |
| `src/Core/Config.php`, `src/Core/Database.php`, `admin/model/pdo.php` | 05 (env), 09 (pdo.php deletion) | DONE — 05 made it env-ready, 09 deleted `pdo.php` (deferred from 07 per that phase's correction note) |
| `src/Controller/PasswordController.php`, `view/user/verification.php` | 04 | OTP + redirect-exit fix |
| `admin/model/{bill,comment,question,statistics}.php` | 06 (guards), 09 (superseded by `Model\*`) | DONE — deleted in 09; `{category,coupon,product,user}.php` deleted in 08 |

## Global risks
- **HIGH — password migration reversibility:** hashing is one-way. A bad migration locks every user
  (incl. admin) out. Mitigation in Phase 01: full `user`-table backup before running; migration is
  idempotent (skips already-hashed rows via `password_get_info`); verify a login for one client + one
  admin account before deleting the backup. Owner approves approach first.
- **HIGH — admin MVC regression surface:** 07–09 touch every admin feature (39 `act` cases, 9 domains).
  Mitigation: strangler per-domain switchover, each domain runnable/testable independently; Phase 10
  authenticated curl sweep of every `?act=` + Apache/PHP error-log diff gates the merge.
- **MED — CSRF wiring vs admin rewrite double-touch:** Phase 02 adds token verify into
  `public/admin/index.php`; 07 rewrites that file. Mitigation: strictly sequential; 07 re-implements
  CSRF verify centrally in the `AdminController` base (net simplification).
- **MED — cross-plan overlap with Vercel deployment plan** (`plans/260711-2351-vercel-deployment/`):
  both edit `Config.php`/`Database.php`/`admin/model/pdo.php` (env DB) and `public/*/index.php`
  (sessions/uploads). Mitigation: run THIS plan first (all changes are local-safe, no infra); Phase 05
  here IS Vercel phase-02 — do not do both. Session/upload re-architecture stays in the Vercel plan.
- **LOW — no automated tests:** all verification is `php -l` + manual/curl route walkthroughs. Accepted;
  building a test harness is out of scope (YAGNI) but flagged as future work.

## Owner (user) action items — cannot be automated
1. **Phase 01:** approve password-migration approach (one-time in-place hash of existing plaintext rows
   vs lazy rehash-on-next-login). Recommended: one-time script (current values are plaintext = already
   known, so `password_hash($plaintext)` per row is trivial and cleaner).
2. **Phase 06:** confirm dropping the 6 unused legacy tables (`users`, `migrations`, `failed_jobs`,
   `password_resets`, `personal_access_tokens`, `history_bank`) — explicit yes required before any DROP.
3. **Phase 11:** review the UI/UX issue list produced by the audit and prioritize which fixes proceed.
4. **Phase 12:** review before/after of UI polish before merge (subjective changes need sign-off).

## Success criteria (measurable)
- No plaintext passwords remain (`SELECT password FROM user` shows only `$2y$`/`$argon` hashes);
  client + admin login still work.
- Every state-changing form rejects requests with a missing/invalid CSRF token (verified by curl).
- Zero unescaped DB/user output in the 42 views (grep audit: every `<?= $dbvar ?>` wrapped or justified).
- OTP is `random_int`-generated and expires (≤10 min); expired codes rejected.
- `admin/model/pdo.php` deleted; single `Core\Database` connection layer; `public/admin/index.php`
  is a thin front controller; no `admin/model/*.php` remain. **Achieved in Phase 09.**
- Phase 10 authenticated sweep: every admin `?act=` returns without new PHP errors vs. pre-refactor log.
- UI audit issue list resolved or explicitly deferred with owner sign-off.
- Local XAMPP behavior and `index.php?act=X` / `admin/index.php?act=X` URLs unchanged throughout.

## Open questions
- Hashing algorithm: `PASSWORD_BCRYPT` (portable, default) vs `PASSWORD_ARGON2ID` (stronger, needs PHP
  build support — verify on XAMPP). Default to `PASSWORD_DEFAULT` unless owner requires argon2.
- Admin namespace: decided in Phase 07 — `Codemoi\Controller\Admin\*` (matches the client `src/Controller/` PSR-4 layout).
