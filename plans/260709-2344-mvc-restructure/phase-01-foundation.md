# Phase 01 — Foundation (autoload root, Config, Core\Database)

## Context Links
- `composer.json` (PSR-4 `84904\Codemoi\ => src/`), `vendor/composer/autoload_psr4.php` (verified maps to `baseDir/src`).
- Old DB layer: `model/pdo.php:7-120` (`pdo_get_connection`, `pdo_query`, `pdo_query_one`, `pdo_query_value`, `pdo_execute`, `pdo_execute_return_lastInsertId`).

## Overview
- **Priority:** P1 (blocks all). **Status:** complete.
- **Note:** namespace root corrected to `Codemoi\` (not `84904\Codemoi\`) per orchestrator fix to
  `composer.json` PSR-4 mapping (`84904` is an invalid PHP namespace segment). All classes below use
  `namespace Codemoi\Core;` / `\Codemoi\Core\Database`.
- Stand up the class root under `src/` and a `Core\Database` class that centralizes the PDO helpers.
  Old `model/pdo.php` stays untouched and live — nothing else changes this phase.

## Key Insights
- **No `composer` binary in this env** (verified: not in PATH, no `composer.phar`). Do NOT change the
  PSR-4 mapping — it cannot be regenerated. Instead place classes under the already-mapped `src/`.
  PSR-4 resolves NEW `.php` files under `src/` at runtime with zero regen.
- `src/css`, `src/js`, `src/image` remain in place (subdirs don't collide with `src/Core` etc.).
- DB name is `codemoi2`, host `localhost` (client). Keep exactly (`model/pdo.php:8`).
- Every existing helper already uses bound `?` params — port verbatim, no SQL string-building.

## Requirements
- `Core\Database` exposes the same 6 operations as static methods (`query`, `queryOne`, `queryValue`,
  `execute`, `executeReturnId`) plus a private shared `connection()`.
- A `Config` holder for DB creds (single source, no scattered literals).
- try/catch around execute (rethrow `PDOException`) matching current behavior.

## Architecture
- Data flow: `Controller → Model → Core\Database::query($sql, ...$args) → PDO(prepared) → rows`.
- One connection per call (mirrors current `pdo_get_connection()` per-call pattern — do NOT change
  lifetime now; changing to a shared/persistent connection is out of scope/YAGNI).

## Related Code Files
- **Create:** `src/Core/Config.php`, `src/Core/Database.php`.
- **Read for parity:** `model/pdo.php`.
- **Delete:** none this phase.

## Implementation Steps
1. `src/Core/Config.php` — `namespace 84904\Codemoi\Core;` class `Config` with `const DB_HOST='localhost'`,
   `DB_NAME='codemoi2'`, `DB_USER='root'`, `DB_PASS=''`, `CHARSET='utf8'`.
2. `src/Core/Database.php` — `namespace 84904\Codemoi\Core;` class `Database`:
   - `private static function connection(): PDO` — build DSN from `Config`, set `ERRMODE_EXCEPTION`.
   - `query`, `queryOne` (FETCH_ASSOC), `queryValue` (first value), `execute`, `executeReturnId` —
     each `(string $sql, ...$args)`, port logic from `model/pdo.php` verbatim (prepared + execute).
3. Lint both files.
4. Smoke test: scratch script `require 'vendor/autoload.php'; var_dump(\84904\Codemoi\Core\Database::query('SELECT 1 v'));`
   run via `php.exe`; confirm autoload resolves + DB connects. Delete scratch after.

## Todo List
- [x] `src/Core/Config.php`
- [x] `src/Core/Database.php`
- [x] Lint pass
- [x] Scratch autoload + DB smoke test, then remove scratch

## Success Criteria
- `php.exe -l` clean on both files.
- Scratch script prints a row via `Database::query` (autoload + PDO both work).
- Existing site still loads unchanged (`index.php?act=product` renders) — old `pdo.php` still in use.

## Risk Assessment
- **Autoload path mismatch (Low/High):** wrong namespace→dir. Mitigate: match `84904\Codemoi\Core` → `src/Core/`.
- **DB creds drift (Low/Med):** copy host/name/user exactly from `model/pdo.php:8`.

## Security Considerations
- Keep prepared statements; never interpolate. DB creds stay server-side (already the case).

## Next Steps
- Phase 02 builds models on `Core\Database`.
