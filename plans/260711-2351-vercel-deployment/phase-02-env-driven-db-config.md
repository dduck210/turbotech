# Phase 02 — Env-driven DB config

**Context:** [plan.md](plan.md) · depends on [phase-01](phase-01-external-mysql.md)

## Overview
- **Priority:** P1
- **Status:** pending
- **Description:** Make both DB connection paths read host/name/user/pass from env vars, with the
  current hardcoded values as local-dev fallback defaults so XAMPP keeps working unchanged.

## Key insights
- `Config.php` already has a working `.env` reader: `Config::env($key, $default)` at
  `src/Core/Config.php:71-78` (reads `.env` file, then `getenv()`, then default). SMTP/bank settings
  already use it. DB settings are the outlier — still class constants `DB_HOST/DB_NAME/DB_USER/DB_PASS`
  at `Config.php:18-21`. **DRY win: reuse the existing `env()` mechanism, don't add a new one.**
- `Database.php:22` builds DSN from `Config::DB_HOST` etc. (constants). Must switch to method calls.
- `admin/model/pdo.php:8-10` is fully independent, hardcodes `127.0.0.1;dbname=codemoi2`, user `root`,
  empty pass. It does NOT use `Config` at all (procedural legacy file). It must read the same env vars.
- Callers of the constants: grep confirms only `Database.php:22` reads `Config::DB_*`. No other
  reader — safe to convert constants to methods (or keep constants as defaults behind methods).

## Data flow
`.env` / OS env / Vercel env ──> `Config::db*()` methods ──> `Database.php` DSN & `pdo.php` DSN
──> MySQL (localhost locally, cloud on Vercel). Same code, host decided purely by env.

## Requirements
- Functional: with no env set, connect to local `codemoi2` exactly as today (zero-config XAMPP dev).
  With env set, connect to cloud DB. No `$_SESSION`/query/caller changes.
- Non-functional: `admin/model/pdo.php` must not `require` the `Codemoi\Core\Config` autoloader if
  that breaks its standalone legacy include chain — verify it can reach the autoloader; if not,
  give `pdo.php` a tiny self-contained `getenv()`-with-default reader (KISS, avoids coupling).

## Related code files
- Modify: `src/Core/Config.php` (add `dbHost/dbName/dbUser/dbPass/charset` methods reading env with
  current literals as defaults; keep constants or fold into defaults)
- Modify: `src/Core/Database.php:22,24` (use `Config::dbHost()` etc.)
- Modify: `admin/model/pdo.php:8-10` (read env: prefer `Config::db*()` if autoloader reachable, else
  local `getenv('DB_HOST') ?: 'localhost'` fallbacks)
- Modify: `.env.example` (add `DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS` documented keys)
- Modify (local only, gitignored): `.env` (real cloud creds for local test against cloud)

## Implementation steps
1. Add `Config::dbHost()`, `dbName()`, `dbUser()`, `dbPass()`, `dbPort()`, `charset()` — each
   `return self::env('DB_HOST', 'localhost')` etc. Defaults = current literals (`localhost`,
   `codemoi2`, `root`, ``, `3306`, `utf8`). Note `Config::env()` currently returns string; `dbPort`
   casts to int like `smtpPort()` at `Config.php:41-44`.
2. Update `Database.php` DSN to include port + use methods:
   `'mysql:host='.Config::dbHost().';port='.Config::dbPort().';dbname='.Config::dbName().';charset='.Config::charset()`
   and `new PDO($dsn, Config::dbUser(), Config::dbPass())`.
3. Determine whether `admin/model/pdo.php` include chain has Composer autoload available (trace the
   `require` chain from `public/admin/index.php:3` → `admin/controller/controller.php`). If yes, use
   `Config::db*()`. If no, add a minimal env-with-default block at the top of `pdo_get_connection()`.
4. If Phase 01 host requires TLS: add `PDO::MYSQL_ATTR_SSL_CA` option gated on an env flag
   (`DB_SSL_CA` set → apply); default unset → plain (local).
5. Add DB keys to `.env.example` (empty/placeholder), and to local `.env` with the real cloud creds
   for cross-testing.

## Todo
- [ ] Add `Config::db*()` env-reading methods with literal defaults
- [ ] Switch `Database.php` DSN to methods + port
- [ ] Env-drive `admin/model/pdo.php` (Config or local fallback)
- [ ] Optional TLS-CA env gate
- [ ] Update `.env.example` (+ local `.env`)
- [ ] `php -l` on all three files

## Success criteria
- With empty DB env: app connects to local `codemoi2` (unchanged behavior).
- With cloud DB env set: `php -r` smoke connect via both `Database::query('SELECT 1')` and
  `pdo_query('SELECT 1')` succeeds against cloud.

## Risk assessment
- **MED — `pdo.php` autoloader reachability:** legacy file may not have `Config` in scope.
  Mitigation: step 3 traces the include chain; local fallback reader if not reachable.
- **LOW — port default:** current code omits port (defaults 3306). Adding explicit `port=3306`
  default is behavior-equivalent locally. Verify local socket vs TCP (`localhost` may use socket on
  some XAMPP; keep `localhost` default so socket path preserved).
- **LOW — two divergent readers** drifting later (DRY violation). Mitigation: prefer single
  `Config::db*()` if autoloader reachable; document the fallback as intentional.

## Security considerations
- Real creds only in gitignored `.env` / Vercel env. `.env.example` holds placeholders only.

## Next steps
- Blocks Phase 03 (session handler uses the same connection), Phase 05 (env var list), Phase 06.
