# Phase 08 — Admin controllers: auth/dashboard + CRUD domains

**Context:** [plan.md](plan.md) · Workstream 3 (admin MVC) · **Priority P2** · depends on Phase 07

## Overview
Port the "simple" admin domains onto the MVC scaffold from Phase 07: auth (login/logout), dashboard, and
the CRUD-shaped domains that map cleanly to shared models — category, product (+ image upload), user,
coupon. Strangler: each ported `act` routes through the new controller; unported ones stay on the old switch.

## Key insights (verified — `public/admin/index.php` case map)
- Auth/dashboard: `login:34`, `logout:30`, `dashboard:22`, `'/':20`. Login uses `check_user_admin`
  (`role='1'`; now `password_verify` after Phase 01).
- Category CRUD: `add_category:59`, `list_category:76`, `edit_category:91`, `update_category:107`,
  `delete_cate:122`.
- Product CRUD: `add_product:131`, `list_product:175`, `edit_product:197`, `update_product:215`,
  `delete_product:235` (already has FK try/catch — preserve). Includes `img_pro` file upload handling.
- User CRUD: `list_user:257`, `edit_user:273`, `update_user:289`, `delete_usser:319` (note the
  misspelled `act` value — MUST keep the exact string to preserve URLs).
- Coupon CRUD: `list_coupon:532`, `add_coupon:543`, `delete_coupon:566`, `edit_coupon:575`, `update_coupon:588`.
- Shared-model reuse: `Model\Product/Category/User/Coupon` already serve client reads; add admin CRUD
  methods (create/update/delete) additively. Reconcile `loadall_pro` signature divergence (Phase 07 note).

## Data flow
```
act ─> Router ─> Admin\Controller\{Auth,Dashboard,Category,Product,User,Coupon}
        ─> Model\* (read + new CRUD methods) ─> Core\Database
        ─> View(admin/view/{login,dashboard,list_*,add_*,update_*})
Upload: multipart ─> ProductController move_uploaded_file ─> stored path in product.img_pro
```

## Requirements
- Preserve every `act` string exactly (incl. `delete_usser` typo) and current redirect/flash behavior.
- Carry forward: CSRF verify (base, Phase 02), FK guards (Phase 06 pattern, esp. `delete_product`,
  `delete_cate`, `delete_usser`, `delete_coupon`), password hashing on `update_user` (Phase 01).
- Image upload logic preserved (target dir, allowed types, filename handling) — no behavior change.

## Related code files
- Create: `src/Controller/Admin/{AuthController,DashboardController,CategoryController,ProductController,
  UserController,CouponController}.php` (each extends `AdminController`; keep files <200 lines — split if needed).
- Extend: `src/Model/{Product,Category,User,Coupon}.php` — additive `create/update/delete` (+ product
  upload path helper). Reuse existing read methods.
- Modify: `public/admin/index.php` — register these routes to the new controllers; remove the ported
  cases from the old switch (strangler).
- Delete (progressively): `admin/model/{category,product,user,coupon}.php` once superseded.
- Reuse: `admin/view/{login,dashboard,list_category,add_category,update_category,list_product,add_product,
  update_product,list_user,update_user,list_coupon,add_coupon,update_coupon}.php` (unchanged; already escaped in Phase 03).

## Implementation steps
1. AuthController (login `check_user_admin`+`password_verify`, logout, session/role set, CSRF, token rotate),
   DashboardController.
2. CategoryController (add/list/edit/update/delete + FK guard), extend `Model\Category` CRUD.
3. ProductController (add/list/edit/update/delete + `img_pro` upload; preserve `delete_product` FK catch),
   extend `Model\Product` CRUD + upload helper. Reconcile `loadall_pro` signatures.
4. UserController (list/edit/update/delete; `update_user` hashes password per Phase 01; FK guard on delete),
   extend `Model\User` CRUD.
5. CouponController (list/add/edit/update/delete + FK guard), extend `Model\Coupon` CRUD.
6. Register routes; strip ported cases from old switch; delete superseded `admin/model/*` files.
7. `php -l` all; walk each ported screen in browser (CRUD round-trips, upload, flash messages).

## Todo
- [x] Auth + Dashboard controllers (login via `password_verify`, CSRF, token rotate) — done in Phase 07
- [x] Category controller + `Model\Category` CRUD (+ FK guard)
- [x] Product controller + `Model\Product` CRUD + upload (+ preserve `delete_product` FK catch)
- [x] User controller + `Model\User` CRUD (hash on update; FK guard)
- [x] Coupon controller + `Model\Coupon` CRUD (no FK guard needed — see notes)
- [x] Routes registered; ported cases removed from old switch; superseded models deleted
- [x] `php -l`; per-screen curl walkthrough

## Implementation notes
- Auth/Dashboard were already ported in Phase 07 (that phase's own pilot requirement) —
  this phase's actual scope was the 4 CRUD domains plus the file deletions Phase 07
  deferred.
- **FK-guard audit (per domain, from the Phase 06 schema audit):** only `category`
  (`product.idcate` FK) and `product` (`cart.id_pro`/`comment.id_pro` FKs) are actually
  FK-referenced — both already had try/catch guards (category from Phase 06, product
  pre-existing) and both were preserved verbatim in the port. `user` and `coupons` have
  **zero** FK constraints referencing them anywhere in the schema, so `delete_usser` and
  `delete_coupon` correctly have no try/catch (confirmed, not assumed — matches the
  Phase 06 finding).
- **Security fix applied to every domain:** `update_category`/`delete_cate`,
  `update_product`/`delete_product`, `update_user`/`delete_usser`, and
  `update_coupon`/`delete_coupon` all had **zero admin-session guard** in the old code —
  only the global CSRF check applied, meaning any session holder who'd merely loaded the
  login page (unauthenticated) could POST/GET directly to these endpoints with a valid
  token and act as admin. Every one of these 8 actions now calls `requireAdmin()` like
  every other admin action. Verified live: unauthenticated requests to all 8 now 302 to
  login instead of executing.
- **Product upload crash fix:** the old procedural image-upload code tolerated a missing
  `$_FILES['img_pro']` key (PHP warning, silent no-op) because it had no type hints. The
  ported `ProductController::moveUpload()` private helper initially had a strict
  `array $file` hint, turning that same input into an uncaught `TypeError` — found via a
  curl edge-case test (not reachable through a real browser, which always populates
  `$_FILES['img_pro']` as an array even with no file selected). Fixed by making the
  parameter nullable with an explicit no-op guard, matching the old graceful
  degradation instead of introducing a new crash.
- `Model\Product::allAdmin(int $idcate = 0)` added as the admin list's simpler
  category-only filter, reconciling the signature divergence from the client's fuller
  `search($kyw, $idcate, $min, $max)` — additive, `search()` untouched.
- `Model\User::checkAdmin()`/`allAdmin()`/`find()`/`updateAdmin()`/`deleteAdmin()` and
  `Model\Category`/`Model\Coupon`'s equivalent CRUD methods are all additive; none of the
  existing client-facing methods on these classes were modified.
- `admin/view/dashboard.php`'s own stats-gathering (`loadall_loai()`/`loadall_pro()`/
  `loadall_user()`) was the last remaining caller of 3 of the 4 superseded
  `admin/model/*.php` files — switched to `Category::all()`/`Product::allAdmin()`/
  `User::allAdmin()` to unblock deleting them. `loadall_cmt()`/`loadall_bill(0)` are
  untouched (Comment/Bill domains aren't ported until Phase 09).
- Deleted `admin/model/{category,product,user,coupon}.php` (zero remaining callers,
  verified via project-wide grep before each deletion) and their `include` lines in
  `public/admin/index.php`. `admin/model/{bill,comment,statistics,question,pdo}.php`
  remain — Phase 09's scope.
- Live-tested every domain end-to-end with disposable test admin accounts and
  throwaway rows (all created/exercised/deleted): full CRUD round-trips (including a
  real file upload via curl `-F`) for category/product/user/coupon; validation-failure
  paths (empty fields, invalid email, short password, FK-referenced delete); the
  authorization-guard fix; and a full sweep of every admin `act` (ported + the 4 still
  on the old switch) both authenticated and not, plus client routes. Zero new entries
  in `apache/logs/error.log` across the entire phase.
- Commits are per-domain (Category → Product → User → Coupon → file cleanup) for
  rollback granularity, matching this phase's own risk-assessment note, though only
  pushed once at the end as usual.

## Success criteria
- Login/logout, dashboard, and full category/product/user/coupon CRUD work via MVC; URLs + flashes unchanged.
- Product image upload works. Deletes of referenced rows show flash (no crash). No superseded model files remain
  for these domains.

## Risk assessment
- **High/Med — shared-model change breaks client reads.** Mitigation: additive methods; re-run client
  route checklist after each domain.
- **Med — upload handling regressions** (path, permissions, filename collisions). Mitigation: mirror the
  existing logic exactly; test a real upload + overwrite.
- **Med — `act` string drift** (esp. `delete_usser`). Mitigation: copy exact strings; grep views for the
  links that reference them.
- **Med — strangler half-state.** Mitigation: every merge leaves all `act` values working.

## Rollback
- Per-domain commits; revert a domain restores its old switch case + `admin/model/*` file. Others unaffected.

## Security considerations
- CSRF + role guard via base. Password hashing on user update. Output already escaped (Phase 03).

## Next steps
- Runs before/parallel-safe-after 09 (disjoint domains, same file `public/admin/index.php` → sequential with 09).
  Phase 10 regression covers both.
