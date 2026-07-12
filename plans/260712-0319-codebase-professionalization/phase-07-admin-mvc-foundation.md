# Phase 07 — Admin MVC foundation

**Context:** [plan.md](plan.md) · Workstream 3 (admin MVC) · **Priority P2** · depends on Phase 05

## Overview
Stand up the admin-side MVC scaffolding that mirrors the client architecture, so Phases 08–09 can port
each domain onto it. No behavior/URL change; strangler-style (old `switch` stays live until each case is
ported). This phase also completes DB-layer convergence: admin moves onto `Core\Database`, deleting the
duplicate `admin/model/pdo.php`.

## Key insights (verified)
- Client MVC already exists to copy: `src/Core/{Router,Controller,Database,View,Config}`,
  `src/Controller/*`, `src/Model/*`, PSR-4 `Codemoi\ → src/` (composer.json). Reuse it.
- Admin entry `public/admin/index.php` is a 627-line `switch ($_GET['act'])` with 39 cases across ~9
  domains (verified case list): dashboard/login/logout, category CRUD (`add/list/edit/update/delete_cate`),
  product CRUD (`add/list/edit/update/delete_product` + `img_pro` upload), user CRUD, bill/order mgmt
  (`list/edit/update/approve/ship/cancel_bill`, `billdetail`), comment mod (`list/delete_cmt`), question
  mod (`list/delete_ques`), statistics (`list_thongke`), coupon CRUD.
- Render helper `admin/controller/controller.php` = `render($path,$data)` doing `extract($data); include`
  — replace with `Core\View` (isolated scope, no global `extract`).
- Admin models `admin/model/{bill,category,comment,coupon,product,question,statistics,user}.php` are
  plain functions on `pdo_*`. Reuse client `Model\*` where the table/logic matches; ADD admin-only
  methods (CRUD, moderation, stats) rather than duplicate classes.
- Divergence to reconcile: admin `loadall_pro($idcate=0)` differs from client `loadall_pro($kyw,$idcate,$min,$max)`
  — reconcile as additive methods on `Model\Product`, don't fork a second class.
- Admin auth is a separate concern: login requires `role='1'` (`check_user_admin`). Keep admin session
  guard isolated from client `Auth`.
- Flash read-blocks duplicated per action (`public/admin/index.php:80/185/261/351/535`) — centralize in
  the `AdminController` base (eliminates the Phase 06 smell for free).

## Data flow (target)
```
public/admin/index.php (thin bootstrap: session, autoload, CSRF verify, e() helper)
   └─> Router (act-keyed) ─> Admin\Controller\* (extends AdminController)
          AdminController base: role='1' guard + Csrf::verify + centralized flash read
          └─> Model\* (shared reads + admin CRUD/moderation/stats methods)
          └─> View(base = admin/view/) ─> admin/view/*.php
```

## Requirements
- Reuse `src/Core/*` and `src/Model/*` (extend, don't duplicate). Preserve `admin/index.php?act=X` URLs
  and the `role='1'` guard.
- Parameterize `Core\View` base dir (or an `AdminController` path prefix) → `admin/view/` (views stay put).
- `AdminController` base centralizes: auth/role guard, CSRF verify (from Phase 02), flash read/expose.
- Delete `admin/model/pdo.php`; all admin data access via `Core\Database`.

## Related code files
- Create: `src/Controller/Admin/AdminController.php` (base: guard + flash + CSRF + render helper).
  Namespace decision: `Codemoi\Controller\Admin\*` (matches `src/Controller/` PSR-4 layout).
- Create: admin router wiring (reuse `Core\Router`) — a small route map for admin `act` values.
- Modify: `src/Core/View.php` — parameterize base view directory (default client, override admin).
- Modify (scaffold only this phase): `public/admin/index.php` — bootstrap block + delegate to the base;
  individual cases still fall through to old code until 08/09 port them (strangler).
- Extend: `src/Model/Product.php`, `Category.php`, `User.php`, `Coupon.php` — additive admin methods
  (create/update/delete) — actual method bodies land in 08/09; here just confirm the reuse plan.
- Delete: `admin/model/pdo.php` (after admin reads go through `Core\Database`).

## Implementation steps
1. Decide namespace (`Codemoi\Controller\Admin\`). Create `AdminController` base: role guard
   (redirect to admin login if `$_SESSION` admin/role≠1), `Csrf::verify` on POST, `flash()` reader,
   `render($view,$data)` via `Core\View` with admin base dir.
2. Parameterize `Core\View` base directory (constructor arg or setter); default preserves client behavior.
3. Add admin bootstrap to `public/admin/index.php` (session, autoload, `e()` + CSRF include) and a Router
   instance; register ported routes as they come online in 08/09 (strangler: unported `act` → old switch).
4. Migrate admin reads onto `Core\Database`; once no `pdo_*` callers remain, delete `admin/model/pdo.php`.
5. `php -l`; smoke: admin login + one ported screen (e.g. dashboard) works through the new path; all
   other screens still work via the old switch.

## Todo
- [ ] `AdminController` base (guard + CSRF + flash + render)
- [ ] `Core\View` base-dir parameterization (client default preserved)
- [ ] Admin bootstrap + Router in `public/admin/index.php` (strangler fallthrough)
- [ ] Move admin reads to `Core\Database`; delete `admin/model/pdo.php`
- [ ] Smoke: login + dashboard via new path; rest via old switch
- [ ] `php -l` clean

## Success criteria
- Admin login + at least dashboard run through the new MVC path; all other admin screens still function.
- `admin/model/pdo.php` deleted; no `pdo_*` calls remain. Client app unaffected (shared `Model\*` reads intact).

## Risk assessment
- **High/Med — shared-model coupling:** additive admin methods on `Model\*` must not alter client reads.
  Mitigation: additive-only; re-run client route checklist after each change.
- **Med — `Core\View` base-dir change** could break client rendering. Mitigation: default = current client
  path; admin passes an override. Lint + client smoke after.
- **Med — strangler half-state** (some cases new, some old) during 08/09. Mitigation: every merge leaves
  ALL `act` values working (new or old path); Phase 10 verifies the whole set at the end.
- **Med — CSRF/guard double-touch** with Phase 02/06 on `public/admin/index.php`. Mitigation: sequential;
  this phase centralizes both into the base (net simplification).

## Rollback
- Separate commits per scaffold step; revert restores the old switch + `admin/model/pdo.php`. Client unaffected.

## Security considerations
- Role guard centralized (single enforcement point). CSRF verify centralized. Escaping already done (Phase 03).

## Next steps
- Blocks 08 and 09 (controllers slot onto this scaffold). Depends on Phase 05 (env DB) + Phase 03 (views escaped).
