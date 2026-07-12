# Phase 05 — DB layer env-config unification

**Context:** [plan.md](plan.md) · Workstream 2 (cleanup) · **Priority P2** · unblocks admin MVC (07)

## Overview
Two DB config sources exist and both hardcode credentials. Make BOTH env-driven using the existing
`Config::env()` reader (with current literals as local-dev defaults). Full convergence onto one
connection layer (`Core\Database`, deleting `admin/model/pdo.php`) happens in Phase 07 — NOT here — to
avoid rewriting all `admin/model/*.php` twice.

## Key insights (verified)
- `src/Core/Config.php:18-21` — DB settings are class constants `DB_HOST='localhost'`, `DB_NAME='codemoi2'`,
  `DB_USER='root'`, `DB_PASS=''` — the ONLY settings not env-driven. SMTP/bank settings at `:28-68`
  already use `self::env(...)`. `env()` reader at `:71`. **DRY win: reuse `env()`, don't add a mechanism.**
- `admin/model/pdo.php:8` — separate hardcoded DSN `mysql:host=127.0.0.1;dbname=codemoi2;charset=utf8`,
  user/pass at `:9-11`, connection at `:12`. Fully independent of `Config` (procedural legacy file).
- Near-duplicate query API in `pdo.php`: `pdo_get_connection:6`, `pdo_execute:22`, `pdo_query:42`,
  `pdo_query_one:64`, `pdo_query_value:86` mirror `Database::execute/query/queryOne`. This DUPLICATION is
  removed in Phase 07 (when admin models become `Model\*`), NOT here.
- **This phase == Vercel plan phase-02** (`plans/260711-2351-vercel-deployment/phase-02-env-driven-db-config.md`).
  Do it once; this file supersedes/satisfies that one. Do not run both.

## Data flow
```
.env / OS env ─> Config::dbHost()/dbName()/dbUser()/dbPass()/dbPort()/charset() ─> Database.php DSN
                                                                                └─> pdo.php DSN
(no env set → local codemoi2 exactly as today)
```

## Requirements
- Functional: with no env set, connect to local `codemoi2` unchanged (zero-config XAMPP). With env set,
  connect elsewhere. No query/caller/session changes.
- Non-functional: single reader preferred (`Config::db*()`) if the autoloader is reachable from
  `pdo.php`'s include chain; else a minimal `getenv()`-with-default block in `pdo.php` (documented as
  intentional local fallback).

## Related code files
- Modify: `src/Core/Config.php` — add `dbHost()/dbName()/dbUser()/dbPass()/dbPort()/charset()` returning
  `self::env('DB_*', <current literal>)`; keep constants as the default values (or fold in).
- Modify: `src/Core/Database.php` — DSN builder uses the new methods (+ explicit port).
- Modify: `admin/model/pdo.php:8-12` — read same env vars (prefer `Config::db*()`; else local fallback).
- Modify: `.env.example` — document `DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS`.

## Implementation steps
1. Add `Config::db*()` methods, defaults = current literals (`localhost`,`codemoi2`,`root`,``,`3306`,`utf8`);
   `dbPort()` casts to int like `smtpPort()` at `Config.php:43`.
2. Update `Database.php` DSN to use methods + `port=`.
3. Trace `pdo.php`'s include chain from `public/admin/index.php` → `admin/controller/controller.php` to
   check autoloader reachability. If reachable → use `Config::db*()`; else add a minimal env-default reader.
4. Add DB keys to `.env.example` (placeholders only — never real creds).
5. `php -l` the three files; smoke: `Database::query('SELECT 1')` and `pdo_query('SELECT 1')` both succeed.

## Todo
- [x] `Config::db*()` env methods with literal defaults
- [x] `Database.php` DSN → methods + port
- [x] `admin/model/pdo.php` env-driven (Config or local fallback)
- [x] `.env.example` DB keys
- [x] Smoke both connection paths; `php -l`

## Implementation notes
- Added `Config::dbHost()/dbPort()/dbName()/dbUser()/dbPass()/charset()`, all `self::env('DB_*',
  <literal default>)` — same pattern as the existing `smtp*()` methods. `dbPort()` casts to
  int like `smtpPort()`. The `DB_*` class constants are kept as the literal defaults (not
  removed), so an absent `.env` behaves identically to before.
- `Database.php`'s DSN builder now calls the `Config::db*()` methods and adds an explicit
  `port=` segment (previously relied on the implicit default).
- Traced `pdo.php`'s include chain (`public/admin/index.php:3` requires `vendor/autoload.php`
  **before** `:20` includes `pdo.php`) — the autoloader IS reachable, so `pdo.php` calls
  `\Codemoi\Core\Config::db*()` directly instead of adding a second env-reading mechanism.
  `127.0.0.1` reconciled to `localhost` as the shared default (matches `Config::DB_HOST`,
  preserves the XAMPP socket-connection path per the phase's risk note).
- Found and fixed one more caller during the include-chain trace: root-level `create_table.php`
  (a one-off migration script) includes `admin/model/pdo.php` directly **without** loading the
  autoloader first — would have fataled with "Class Config not found" the next time it's run.
  Added `require_once __DIR__ . '/vendor/autoload.php';` before its `pdo.php` include.
- `.env.example` documents `DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS/DB_CHARSET` with a comment
  noting they're safe to leave unset for local XAMPP.
- Smoke-tested via PHP CLI (`Database::queryOne('SELECT 1')` and `pdo_query('SELECT 1')` both
  succeed with no env set, connecting to the same local `codemoi2`) and via a live curl sweep
  (admin login redirect, admin `list_product` session guard, client home, client
  `product-list`) — zero new entries in `apache/logs/error.log`.

## Success criteria
- Empty DB env → local `codemoi2` unchanged (client + admin both connect).
- DB env set → both paths connect to the target. `localhost` vs `127.0.0.1` reconciled to one default.

## Risk assessment
- **Med — `pdo.php` autoloader reachability**. Mitigation: step 3 traces; local fallback if not reachable.
- **Low — socket vs TCP** (`localhost` may use a socket on XAMPP). Mitigation: keep `localhost` default so
  the socket path is preserved.
- **Low — divergent readers drifting.** Mitigation: prefer single `Config::db*()`; full unification in 07.

## Security considerations
- Real creds only in gitignored `.env`. `.env.example` holds placeholders.

## Next steps
- Blocks Phase 07 (admin MVC deletes `pdo.php`, moves admin onto `Core\Database`). Independent of security phases.
