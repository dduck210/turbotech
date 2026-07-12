# Phase 01 — Password hashing + existing-row migration

**Context:** [plan.md](plan.md) · Workstream 1 (security) · **Priority P1** · Owner approval required

## Overview
Passwords are stored and compared in **plaintext** everywhere. Introduce `password_hash()` on all
write paths and `password_verify()` on all check paths, plus a one-time migration hashing the
plaintext rows already in the `user` table (can't just swap the compare — existing rows would fail login).

## Key insights (verified)
- Client model `src/Model/User.php`:
  - `check()` `:36-39` — `SELECT ... AND password = ?` (plaintext compare).
  - `register()` `:24-27` — inserts raw `$password`.
  - `updatePassword()` `:98-101` — `UPDATE ... SET password = ?` raw.
  - `resetPassword()` `:108-111` — `UPDATE ... SET password = ?` raw.
- Admin model `admin/model/user.php`:
  - `check_user_admin()` `:30-33` — `SELECT ... AND password = ? AND role = '1'` (plaintext compare).
  - `update_user()` `:14-23` — writes raw `$password` (admin editing accounts).
- Single `user` table backs both client accounts and admins (`role='1'`) — one migration covers both.
- Current stored values are plaintext, i.e. **already known**, so migration = read column, overwrite
  with `password_hash($existingPlaintext)`. Trivial and reversible only via the pre-run backup.

## Data flow
```
Write:  form password ─> password_hash(PASSWORD_DEFAULT) ─> user.password (hash)
Login:  form password + user.password(hash) ─> password_verify() ─> bool
Migrate: user.password(plaintext) ─> password_hash() ─> user.password(hash)   [one-time, idempotent]
```

## Requirements
- Functional: after migration, every existing client + admin account logs in with the SAME password.
- Register/reset/update-password store hashes; login uses `password_verify`.
- Non-functional: migration idempotent (re-runnable, skips rows already hashed).

## Related code files
- Modify: `src/Model/User.php` — `register` (hash on insert), `check` (fetch by user, then
  `password_verify`; remove `AND password = ?` from SQL), `updatePassword`, `resetPassword` (hash).
- Modify: `admin/model/user.php` — `check_user_admin` (fetch by user+role, then `password_verify`),
  `update_user` (hash when password field non-empty; if admin edit allows blank = "unchanged", guard it).
- Create: `scripts/migrate-passwords-to-hash.php` — backup note + idempotent hash loop.
- Read for context: callers of `check` / `check_user_admin` (login controllers) to confirm they only
  branch on truthy/falsy return (no reliance on the compared password value).

## Implementation steps
1. **Backup first** (owner or agent): `mysqldump codemoi2 user > user_backup_YYYYMMDD.sql`.
2. Change `check()`: `SELECT * FROM user WHERE user_name = ? OR email_user = ?` (drop password from
   WHERE), then in PHP `return ($row && password_verify($password, $row['password'])) ? $row : false;`.
3. Change `register()`/`updatePassword()`/`resetPassword()`: `password_hash($password, PASSWORD_DEFAULT)`
   before the query.
4. Mirror in `admin/model/user.php`: `check_user_admin` fetch-then-verify (keep `role='1'` in SQL);
   `update_user` hash the password param (decide blank-means-unchanged: if blank, omit password from
   the UPDATE — enumerate the admin update form to confirm whether blank submit is possible).
5. Write `scripts/migrate-passwords-to-hash.php`: for each `user` row, if
   `password_get_info($row['password'])['algo'] === 0` (not a hash) → `UPDATE` with
   `password_hash($row['password'], PASSWORD_DEFAULT)`. Skip otherwise (idempotent).
6. `php -l` all touched files. Run migration on local DB. Verify one client + one admin login.

## Todo
- [x] Backup `user` table (`scripts/user_backup_20260712_033014.sql`, gitignored)
- [x] `User::check` → fetch + `password_verify`; drop password from WHERE
- [x] `User::register/updatePassword/resetPassword` → `password_hash`
- [x] `check_user_admin` → fetch + verify; `update_user` → hash (blank-unchanged rule)
- [x] `scripts/migrate-passwords-to-hash.php` (idempotent)
- [x] Run migration + verify client & admin login
- [x] `php -l` clean

## Unplanned fix discovered during execution
- **`user.password` column was `VARCHAR(50)`** — too short for a 60-char bcrypt hash, silently
  truncating it on write. First migration run corrupted every row (truncated + then double-hashed on
  a re-run before this was caught). Recovered via the pre-migration backup, then
  `ALTER TABLE user MODIFY password VARCHAR(255) NOT NULL` before re-running. Verified with
  `LENGTH(password) = 60` on every row post-migration.
- **`admin/view/update_user.php` was echoing the target user's live plaintext password back into the
  edit form** (`value="<?= $password ?>"`), which is also why the field was `required|min:6` (there was
  always a value to resubmit). Changed to a blank `type="password"` field with a "leave blank to keep
  current password" placeholder; `update_user()` and the admin dispatcher now treat an empty submission
  as "don't change the password" instead of hashing an empty string over it.

## Success criteria
- `SELECT password FROM user LIMIT 5` → all `$2y$...` (no plaintext).
- Existing accounts log in unchanged (client + admin). Re-running migration changes nothing.

## Risk assessment
- **HIGH/Med — lockout if compare not swapped everywhere:** enumerate ALL password-compare sites
  (verified: only `User::check:36` + `check_user_admin:30`). Both must switch to `password_verify` in
  the same change as the migration. Mitigation: backup + single-session rollout + login smoke test.
- **Med/Low — admin blank-password update** overwriting with a hash of "" . Mitigation: step 4 guard.
- **Low — argon2 unavailable on XAMPP.** Mitigation: use `PASSWORD_DEFAULT` (bcrypt) unless owner
  requires argon2 and PHP build supports it.

## Security considerations
- Never log plaintext. Delete the SQL backup only after login verification. `scripts/` stays outside webroot.

## Next steps
- Independent of other security phases. Owner must approve migration approach before step 5 runs.
