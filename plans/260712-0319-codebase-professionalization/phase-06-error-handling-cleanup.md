# Phase 06 — Error-handling FK guards + dead-table cleanup

**Context:** [plan.md](plan.md) · Workstream 2 (cleanup) · **Priority P2** · Owner approval for table drops

## Overview
Admin delete actions mostly lack try/catch around DB calls, so deleting a still-referenced row throws
an uncaught `PDOException` and white-screens instead of showing a friendly message. Apply the existing
`delete_product` guard pattern to the other delete actions. Also remove 6 confirmed-unused legacy tables
(owner-gated).

## Key insights (verified)
- Template already in place: `public/admin/index.php:235-256` `delete_product` wraps the delete in
  `try { ... } catch (PDOException $e) { $_SESSION['flash_error'] = 'Không thể xoá...'; }` — the pattern to replicate.
- Delete actions WITHOUT a guard (no `catch` nearby — verified): `delete_cate:122`, `delete_usser:319`,
  `delete_cmt:479`, `delete_ques:524`, `delete_coupon:566`. Each must be audited for FK exposure.
- FK audit needed: determine which of category/user/coupon/comment/question are referenced by other
  tables (e.g. category ← product, coupon ← bill, user ← bill/cart/comment). Rows still referenced =
  DELETE will throw → needs the guard. `comment`/`question` deletes may be leaf (no guard needed) —
  confirm via schema before adding noise (YAGNI: don't wrap deletes that can't fail).
- Flash read-blocks are copy-pasted per action in `public/admin/index.php` (`:80`, `:185`, `:261`,
  `:351`, `:535`). This DUPLICATION is a known smell — do NOT dedup here; it's eliminated for free when
  Phase 07 centralizes flash in the `AdminController` base. Noted so nobody "cleans" it twice.
- **Do NOT touch** the confirm-dialog/toast system (`assets/js/confirm-dialog.js` + SweetAlert2) — done
  and correct as of prior work.

## Dead/unused tables (verified zero code references)
`users`, `migrations`, `failed_jobs`, `password_resets`, `personal_access_tokens`, `history_bank`
(legacy Laravel-shaped leftovers in the dump). **Owner must explicitly confirm before any DROP.**

## Requirements
- Functional: every delete action that CAN hit an FK constraint catches `PDOException` → flash error,
  no white-screen. Deletes that cannot fail stay unguarded (no needless try/catch).
- Table drops only after explicit owner yes; produce a reversible migration (backup dump first).

## Related code files
- Modify: `public/admin/index.php` — add FK-guard try/catch to the delete cases that need it
  (`delete_cate`, `delete_usser`, `delete_coupon`, and `delete_cmt`/`delete_ques` only if FK-exposed).
  **Sequential with Phase 02 (CSRF) and Phase 07 (rewrite) on this file — 07 carries guards into controllers.**
- Create: `scripts/drop-unused-tables.sql` (or `.php`) — backup note + `DROP TABLE IF EXISTS ...` for the 6.

## Implementation steps
1. Inspect schema for FKs referencing category/user/coupon/comment/question (e.g. `SHOW CREATE TABLE`).
   List which delete actions can throw. Enumerate — don't assume.
2. Wrap only the exposable deletes with the `delete_product`-style try/catch + `flash_error` (localized
   Vietnamese message matching existing style).
3. Prepare `scripts/drop-unused-tables.sql`; require a `mysqldump` backup of the 6 tables first.
4. **Present the 6-table list to the owner; get explicit yes** before executing any DROP.
5. `php -l`; test each guarded delete by attempting to remove a still-referenced row (expect flash, not crash).

## Todo
- [x] Schema FK audit → list exposable delete actions
- [x] Add try/catch guards to those delete cases (skip leaf deletes)
- [x] `scripts/drop-unused-tables.sql` + backup step
- [x] Owner confirmation on 6 dead tables (GATE)
- [x] Test guarded deletes (referenced row → flash, not white-screen)
- [x] `php -l` clean

## Implementation notes
- Schema FK audit via `information_schema.KEY_COLUMN_USAGE` found only 4 enforced FK
  constraints in the whole `codemoi2` schema: `cart.id_bill→bill`, `cart.id_pro→product`,
  `comment.id_pro→product`, `product.idcate→category`. **No FK constraint references
  `user`, `coupon`, or `question` at all** — deleting those can never throw a
  `PDOException`, so per the phase's own YAGNI note, `delete_usser`/`delete_coupon`/
  `delete_cmt`/`delete_ques` were left unguarded (confirmed leaf-safe, not assumed).
- Only `delete_cate` needed a guard (`product.idcate` FK). Added the same
  try/catch-on-SQLSTATE-23000 pattern already used by `delete_product`
  (`public/admin/index.php`), with a matching Vietnamese flash message. `list_category`'s
  controller case and `admin/view/list_category.php` didn't wire `flash_error` at all
  before this (only `flash_success`) — added it, matching the `list_product` pattern.
- Live-tested with a disposable test admin account (created, logged in, deleted after):
  deleting category `id_cate=14` (HP, 9 products) → 302 redirect, friendly flash-error
  toast, row still in DB, zero new server errors. Deleting an unreferenced throwaway
  category (`id_cate=999`) → deletes normally. Confirms the guard blocks exactly the
  FK-exposed case and nothing else.
- 6 dead tables (`users`, `migrations`, `failed_jobs`, `password_resets`,
  `personal_access_tokens`, `history_bank`) confirmed zero code references via a
  project-wide grep (the handful of incidental string matches were all unrelated —
  a Font Awesome `fa-users` icon class and an email domain in a PHPMailer doc comment).
  Row counts at drop time: `migrations`=4, `history_bank`=9, the rest empty.
- Owner approved via AskUserQuestion. Backed up all 6 via `mysqldump` to
  `scripts/dead-tables_backup_<timestamp>.sql` (gitignored, matches the existing
  `/scripts/*_backup_*.sql` pattern) before running `scripts/drop-unused-tables.sql`.
  Post-drop regression sweep (client home, product-list, admin login) all 200, zero new
  entries in `apache/logs/error.log`.

## Success criteria
- Deleting a referenced category/user/coupon shows a friendly flash error, app stays up.
- After owner approval + backup: the 6 unused tables dropped; app fully functional (zero references, so
  no behavior change).

## Risk assessment
- **Med — double-touch on `public/admin/index.php`** with 02 and 07. Mitigation: strictly sequential;
  Phase 07 re-implements guards inside controllers, superseding this file.
- **Med — dropping a table that's actually used.** Mitigation: verified zero references + backup dump +
  explicit owner gate; `DROP` reversible from the dump.
- **Low — guarding a leaf delete needlessly** (dead code). Mitigation: FK audit first; only guard exposable deletes.

## Security considerations
- None new. Backup dumps stored outside webroot, deleted after verification.

## Next steps
- Independent of security phases; must precede Phase 07 on `public/admin/index.php` (sequential).
