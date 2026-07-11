# Phase 03 — Durable DB-backed session handler

**Context:** [plan.md](plan.md) · depends on [phase-02](phase-02-env-driven-db-config.md)

## Overview
- **Priority:** P1
- **Status:** pending
- **Description:** Replace default file-based sessions with a DB-backed `SessionHandlerInterface`
  writing to the same cloud MySQL, wired before `session_start()`. No `$_SESSION[...]` caller changes.

## Key insights
- Default `session.save_handler=files` writes to local disk. Vercel functions have no shared/persistent
  disk across invocations → login state and cart break across cold starts / scale-out.
- `session_start()` called bare in exactly two entrypoints: `public/index.php:18` and
  `public/admin/index.php:2`. All other `$_SESSION` usage is downstream of these entrypoints
  (verified files: `src/Controller/{Account,Cart,Checkout,Password,Product}Controller.php`,
  `src/Model/{Auth,Cart,User}.php`, `view/header.php`, `view/cart/{bill,viewcart}.php`,
  `view/user/{myaccount,verification}.php`, `public/view/comment-form.php`, `public/view/qr.php`).
  **Only the two entrypoints need editing; `$_SESSION` array usage stays identical.**
- DB-backed chosen over Redis: reuses existing MySQL (no new service dependency — KISS/YAGNI),
  and Phase 02 already gives env-driven access to it. Redis/Upstash noted as alternative only.

## Data flow
`session_start()` ──(custom handler)──> read/write/gc rows in `sessions` table on cloud MySQL.
Session id in cookie (unchanged); payload persisted in DB, so any function invocation resolves it.

## Requirements
- Functional: sessions survive across separate function invocations; login + cart persist. Zero
  changes to how controllers/models/views read/write `$_SESSION`.
- Non-functional: handler opens its own PDO (reuse Phase 02 `Config::db*()`), respects session GC.

## Related code files
- Create: `src/Core/DbSessionHandler.php` — implements `SessionHandlerInterface` (open/close/read/
  write/destroy/gc), uses `Config::db*()` for connection; `sessions(id VARCHAR(128) PK, payload
  MEDIUMTEXT, updated_at INT)` — REPLACE INTO on write, DELETE by age in gc.
- Create (schema): add `sessions` table to cloud DB (and local, so local test uses same path) — via
  a small SQL snippet run in Phase 06 setup / documented here.
- Modify: `public/index.php` — before line 18 `session_start()`, register handler
  (`session_set_save_handler(new DbSessionHandler(), true)`), only when a `SESSION_DRIVER=db` env is
  set OR unconditionally (see step 3 decision).
- Modify: `public/admin/index.php` — same registration before line 2 `session_start()`.

## Implementation steps
1. Create `DbSessionHandler` implementing all 6 `SessionHandlerInterface` methods. `read` returns
   `''` when absent (required contract). `write` uses `REPLACE INTO sessions ...`. `gc` deletes rows
   older than `session.gc_maxlifetime`.
2. Create the `sessions` table SQL; run against cloud DB (Phase 01) and local DB.
3. Decide driver toggle: register handler when `Config::env('SESSION_DRIVER','files')==='db'`. Local
   default `files` (unchanged XAMPP behavior); Vercel env sets `db`. This keeps local zero-risk and
   satisfies "local works unchanged" while still testable locally by flipping the env. Register in a
   single shared bootstrap include to avoid duplicating the snippet in both entrypoints (DRY).
4. Add shared bootstrap: since both entrypoints already `require` the autoloader, add a
   `Codemoi\Core\Session::bootstrap()` static that both call immediately before their `session_start()`.
   Keep the `session_start()` calls where they are (don't move ob_start/order).
5. Add `SESSION_DRIVER` to `.env.example`.

## Todo
- [ ] Create `src/Core/DbSessionHandler.php` (6 methods)
- [ ] Create `Session::bootstrap()` toggle helper
- [ ] `sessions` table SQL (cloud + local)
- [ ] Wire bootstrap before `session_start()` in `public/index.php` and `public/admin/index.php`
- [ ] Add `SESSION_DRIVER` to `.env.example`
- [ ] `php -l` both entrypoints + new files

## Success criteria
- With `SESSION_DRIVER=db` locally: log in, reload page in a way that forces a fresh PHP process,
  session persists (row visible in `sessions` table). Cart survives. With `files` (default): unchanged.

## Risk assessment
- **HIGH — breaks login/cart if handler contract wrong** (e.g. `read` returning `false`).
  Mitigation: strict interface compliance; Phase 06 login+cart regression before deploy.
- **MED — session cookie params** (`session.cookie_secure`, SameSite) differ on HTTPS Vercel vs local
  HTTP. Mitigation: set secure cookie params only when `SESSION_DRIVER=db`/HTTPS.
- **MED — handler ordering:** must register BEFORE `session_start()`. Mitigation: bootstrap call
  placed immediately before the existing `session_start()` line; verified only 2 call sites.
- **LOW — GC load** on DB. Mitigation: standard probability-based GC; index on `updated_at`.

## Security considerations
- Session payload now in DB — ensure `sessions` table not web-exposed (it isn't; only via PDO).
- Set `session.cookie_httponly=1`, `cookie_secure=1` (Vercel HTTPS), regenerate id on login
  (verify `Auth` already does; if not, out of scope — note only).

## Rollback
- Flip `SESSION_DRIVER` back to `files` (env change, no redeploy of code needed if env re-read).

## Next steps
- Feeds Phase 05 (env var `SESSION_DRIVER=db`), Phase 06 (regression).
