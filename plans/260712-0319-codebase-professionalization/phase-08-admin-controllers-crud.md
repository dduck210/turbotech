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
- [ ] Auth + Dashboard controllers (login via `password_verify`, CSRF, token rotate)
- [ ] Category controller + `Model\Category` CRUD (+ FK guard)
- [ ] Product controller + `Model\Product` CRUD + upload (+ preserve `delete_product` FK catch)
- [ ] User controller + `Model\User` CRUD (hash on update; FK guard)
- [ ] Coupon controller + `Model\Coupon` CRUD (+ FK guard)
- [ ] Routes registered; ported cases removed from old switch; superseded models deleted
- [ ] `php -l`; per-screen browser walkthrough

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
