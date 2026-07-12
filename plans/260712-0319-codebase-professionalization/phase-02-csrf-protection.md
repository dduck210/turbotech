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
- [x] `src/Core/Csrf.php` (token/field/verify/rotate)
- [x] Enumerate all POST forms (39 forms / 27 files) and inject `Csrf::field()`
- [x] Client front-controller POST guard (`public/index.php`)
- [x] Admin front-controller POST guard (`public/admin/index.php`) — also added the missing
      `vendor/autoload.php` require (wasn't loaded there at all before)
- [x] Rotate on login/logout — logout already used `session_unset()` (clears everything incl. the
      token), so only login needed an explicit `Csrf::rotate()`
- [x] AJAX POST callers (`applycoupon`, `removecoupon`, cart-qty `edit`) send `_token` via a
      `<meta name="csrf-token">` read at call time
- [x] curl: missing/invalid token rejected (flash shown); valid token accepted — tested on client
      login/register/add-to-cart and admin login, plus a full GET-route regression sweep

## Bugs caught during the automated form-patching pass
The regex-based injector (`<form\b[^>]*>` insert-after) had two real false matches, both found and
fixed before commit:
- `view/user/myaccount.php` — matched literal "`<form>`" mentions inside an explanatory HTML
  *comment* (not real tags), injecting 5 stray `Csrf::field()` calls into the middle of that comment's
  text. Comment restored, correct single field kept on the one real per-order cancel `<form>`.
- `view/user/myaccount.php` (same form) and `public/view/comment-form.php` — the real `<form ...>`
  tag's attributes contained an embedded `<?= ... ?>` short-echo tag; the regex's `[^>]*` stopped at
  the `>` inside `?>` instead of the tag's real closing `>`, splitting the attribute string in half.
  Fixed by hand: field moved to right after the tag's actual close.
- Verified no other file has this pattern (checked every insertion is immediately preceded by a line
  ending in `>`).

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
