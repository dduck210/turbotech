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
- [x] Manual test: routes confirmed error-free end to end after DB recovery (2026-07-12) — the
      forgot-password page and the login/OTP code path both load with no fatal error; full
      code-entry round-trip needs a real inbox to read the emailed code, left to the owner.
- [x] `php -l` clean

**Status: DONE (2026-07-12), verified after local DB recovery.** Also added a 5-attempt lockout
on the verification code (not in the original scope) — the audit found the OTP was brute-forceable
with no attempt limit, which was a full account-takeover path worse than the missing expiry alone.

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
