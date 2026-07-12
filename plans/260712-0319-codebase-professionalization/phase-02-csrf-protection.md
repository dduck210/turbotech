# Phase 02 — CSRF protection

**Context:** [plan.md](plan.md) · Workstream 1 (security) · **Priority P1**

## Overview
Zero CSRF protection exists (verified: 0 `csrf`/`_token` references codebase-wide). Every
state-changing form — login, register, change-password, checkout, and all admin CRUD — is forgeable.
Add a shared token generate/validate helper and wire it into every POST form + its handler.

## Key insights (verified)
- Sessions are already active on both apps (used for `$_SESSION['user']`, flash, cart), so a
  session-stored token is the KISS approach — no DB, no dependency.
- Two front controllers dispatch POSTs: `public/index.php` (client) and `public/admin/index.php`
  (admin). Forms live in `view/*.php` and `admin/view/*.php`.
- A single helper reused by both apps satisfies DRY (place in `src/Core/`, PSR-4 `Codemoi\Core\`).

## Data flow
```
GET form render ─> Csrf::field() emits <input type=hidden name=_token value=SESSION_TOKEN>
POST submit    ─> front controller ─> Csrf::verify($_POST['_token']) ─> 403/flash-error if mismatch
```

## Requirements
- Functional: every POST that mutates state requires a valid token; GET (idempotent reads) unaffected.
- Token per session (rotate on login/logout); constant-time compare (`hash_equals`).
- Non-functional: helper reusable by both apps; graceful failure = flash error + redirect back, not a
  white-screen (preserve existing UX).

## Related code files
- Create: `src/Core/Csrf.php` — `token()` (get/create in `$_SESSION`), `field()` (hidden input HTML),
  `verify(?string)` (`hash_equals`), `rotate()`.
- Modify: `public/index.php` — verify token at top of POST handling (before dispatch to write actions).
- Modify: `public/admin/index.php` — same (NOTE: sequential vs Phase 06/07 on this file; Phase 07
  will move this into the `AdminController` base — keep the check here until then).
- Modify: every form-bearing view — inject `<?= Csrf::field() ?>`. Enumerate POST forms:
  `view/user/*` (login, register, verification, change-pass), checkout/cart forms in `view/*`,
  `public/view/comment-form.php`, and all `admin/view/{add_*,update_*,login}.php`.

## Implementation steps
1. Write `src/Core/Csrf.php`. `token()`: `$_SESSION['_token'] ??= bin2hex(random_bytes(32));`.
   `verify($t)`: `is_string($t) && hash_equals($_SESSION['_token'] ?? '', $t)`.
2. Add a POST-guard in each front controller: if `$_SERVER['REQUEST_METHOD']==='POST'` and
   `!Csrf::verify($_POST['_token'] ?? null)` → set `flash_error`, redirect back (or 403). Place BEFORE
   any write action runs.
3. Enumerate every POST `<form>` (grep `method=.post` across `view/` + `admin/view/`), add
   `Csrf::field()` inside each. List total count in the todo before editing.
4. Rotate token on successful login and on logout (in auth controllers / admin login case).
5. `php -l`; curl test: POST without token → rejected; with valid token → succeeds.

## Todo
- [ ] `src/Core/Csrf.php` (token/field/verify/rotate)
- [ ] Enumerate all POST forms (record count) and inject `Csrf::field()`
- [ ] Client front-controller POST guard (`public/index.php`)
- [ ] Admin front-controller POST guard (`public/admin/index.php`)
- [ ] Rotate on login/logout
- [ ] curl: missing/invalid token rejected; valid accepted

## Success criteria
- `curl -X POST .../index.php?act=login` without `_token` → rejected (flash/403), does not mutate state.
- All existing forms still submit successfully via browser (token present).

## Risk assessment
- **Med — a missed form** silently breaks that action once the guard is on. Mitigation: enumerate via
  grep (don't hand-wave "all forms"); test each POST route in the walkthrough before finishing.
- **Low — AJAX POSTs** (coupon apply, cart qty) must send the token too. Mitigation: expose token to JS
  via a `<meta name="csrf-token">` and include it in fetch headers/body; enumerate AJAX POST callers.
- **Med — double-touch with Phase 07** on `public/admin/index.php`. Mitigation: sequential; 07 centralizes.

## Security considerations
- `random_bytes` (CSPRNG). `hash_equals` (timing-safe). Token not logged, not in GET URLs.

## Next steps
- Independent of 01/03/04. Coordinate file order with Phase 06/07 on `public/admin/index.php`.
