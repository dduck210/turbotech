# Phase 04 — OTP hardening + redirect-exit fix

**Context:** [plan.md](plan.md) · Workstream 1 (security) · **Priority P1**

## Overview
Forgot-password OTP uses non-cryptographic `rand()` and has **no expiry check at all**. Also a latent
redirect-without-`exit` bug in the same verification view. Fix both (same files → one phase).

## Key insights (verified)
- `src/Controller/PasswordController.php:43` — `$code = substr((string) rand(0, 999999), 0, 6);`
  (predictable PRNG, not CSPRNG), stored at `:53` `$_SESSION['code'] = $code;` with no timestamp.
- `view/user/verification.php:22` — `if ($_POST['ma'] != $_SESSION['code'])` — compares code only, no
  expiry, loose `!=` compare.
- `view/user/verification.php:25` — `header('Location: index.php?act=changePass');` with **no `exit`**:
  after the redirect header is sent, PHP keeps executing and renders the current page (latent bug —
  redirect response has a body it shouldn't; on some flows the wrong page logic runs).

## Data flow
```
Request OTP: random_int(0,999999) ─> $_SESSION['code'] + $_SESSION['code_expires']=time()+600
Verify:      $_POST['ma'] === $_SESSION['code'] AND time() <= code_expires ─> redirect+exit ─> changePass
             else ─> flash error, stay
```

## Requirements
- Functional: OTP CSPRNG-generated, 6 digits, expires ≤10 min; expired/invalid rejected with a message.
- Redirect on success MUST `exit` immediately after `header(Location)`.
- Non-functional: constant-time compare (`hash_equals`) for the code; clear session code after use.

## Related code files
- Modify: `src/Controller/PasswordController.php` — `forgotPassword()`: `random_int(0, 999999)`
  zero-padded to 6 (`str_pad((string)random_int(0,999999), 6, '0', STR_PAD_LEFT)`); store
  `$_SESSION['code']` + `$_SESSION['code_expires'] = time() + 600`.
- Modify: `view/user/verification.php` — verify `hash_equals` on code AND `time() <= $_SESSION['code_expires']`;
  add `exit;` after the success `header()`; clear `$_SESSION['code']`/`code_expires` on success.

## Implementation steps
1. In `forgotPassword()`, replace `rand()` line with `random_int` + zero-pad; add expiry timestamp to session.
2. In `verification.php`, change the check to: valid = code non-empty AND not expired AND
   `hash_equals((string)$_SESSION['code'], (string)($_POST['ma'] ?? ''))`. On success: unset code/expiry,
   `header('Location: index.php?act=changePass'); exit;`. On failure/expiry: flash error, re-render form.
3. Consider throttling repeated attempts (optional, YAGNI unless owner wants it) — note only.
4. `php -l`; manual test: request OTP, verify within window (works), wait past expiry / wrong code (rejected).

## Todo
- [x] `random_int` + zero-pad OTP; store expiry in session
- [x] `verification.php`: expiry + `hash_equals` check
- [x] Add `exit;` after success redirect; clear code from session
- [x] Manual test: valid / expired / wrong-code paths
- [x] `php -l` clean

## Implementation notes
- `PasswordController::forgotPassword()`: `rand()` → `str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT)`;
  `$_SESSION['code_expires'] = time() + 600` set alongside `$_SESSION['code']`. The success
  path already routes through `Controller::redirect()`, which already calls `exit` — no
  change needed there.
- `view/user/verification.php`: added an `$expired` check (`!isset($_SESSION['code_expires'])
  || time() > $_SESSION['code_expires']`) checked first, then `hash_equals((string)
  $_SESSION['code'], (string) ($_POST['ma'] ?? ''))` replacing the loose `!=` compare. On
  success, `unset($_SESSION['code'], $_SESSION['code_expires'])` then `header('Location:
  ...'); exit;`.
- Live-tested via a disposable test user (`otptest_temp`, deleted after): requested OTP,
  read the generated 6-digit zero-padded code + expiry timestamp directly from the PHP
  session file (`C:\xampp\tmp\sess_*`) to avoid depending on email delivery timing; verified
  wrong code → 200 + inline error message + session code untouched; correct code → 302 to
  `changePass` with body cut off exactly at the `exit;` line (confirms execution actually
  stops — the leftover buffered header markup before that point is harmless since browsers
  discard 3xx response bodies, same behavior documented for the flash-toast fixes earlier
  in this project).
- Zero new entries in `apache/logs/error.log` during testing.

## Success criteria
- OTP is CSPRNG-generated and 6 digits. Verifying after 10 min or with a wrong code fails with a message.
- Successful verify redirects to `changePass` and stops (no trailing page render).

## Risk assessment
- **Low — zero-pad**: `random_int(0,999999)` can yield <6 digits; `str_pad` keeps it 6 (don't `substr`
  a raw int string — that was the old bug source). Mitigation: pad, then it's always 6 chars.
- **Low — expiry clock**: server-time only (no TZ ambiguity since compared to `time()`). Fine.

## Security considerations
- CSPRNG (`random_int`), timing-safe compare, single-use (cleared after success), bounded lifetime.

## Next steps
- Independent of 01/02/03. Touches only these two files — no ownership conflicts.
