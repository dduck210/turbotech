# Phase 06 — Admin app MVC (DEFERRED / OPTIONAL)

## Context Links
- Admin front controller: `admin/index.php` (switch on `act`). Render helper: `admin/controller/controller.php`
  (`render($path,$data)`). Admin models: `admin/model/{pdo,sanpham,loai,nguoidung,hoadon,binhluan,hoidap,thongke}.php`.
- Admin views: `admin/view/*.php` (AdminLTE/SB-Admin). Admin login requires `role='1'` (`check_user_admin`).

## Overview
- **Priority:** P3 (deferred). **Status:** pending / optional.
- **Recommendation: DEFER admin to this final phase (or a separate follow-up plan).** It roughly doubles
  scope and the client refactor must prove the pattern first. Do NOT bundle admin into the client switchover.
  Admin and client stay TWO separate apps sharing the common `src/` library.

## Key Insights
- Admin already has a render helper + controller.php scaffold — closer to MVC than the client was.
- **Divergent duplicate models** vs client: `admin/model/sanpham.php::loadall_pro($idcate=0)` has a DIFFERENT
  signature than client `loadall_pro($kyw,$idcate,$min,$max)`. Reconcile by reusing `Model\Product` and
  ADDING admin-only methods (CRUD: create/update/delete) rather than a second class. Same for User/Category.
- `admin/model/pdo.php` differs only by `host=127.0.0.1` vs client `localhost` — the shared `Core\Database`
  + `Config` must accommodate (both resolve to the same MySQL; pick one host, verify admin still connects).
- Admin has extra domains with no client equivalent: `thongke.php` (statistics), bill status updates,
  comment/question moderation → new admin-scoped model methods or `Model\Stats`.
- Admin auth is a separate session key (`$_SESSION['admin']`, role check) — keep isolated from client `Auth`.

## Requirements
- Reuse `src/Core/*` (Router, Controller, View, Database) and `src/Model/*` (extend, don't duplicate).
- New namespace segment `84904\Codemoi\Admin\Controller\*`; admin views stay in `admin/view/` (unchanged URLs).
- Admin `Core\View` base path override → `admin/view/` (parameterize View base dir, or an AdminController
  subclass that prefixes paths).
- Preserve `admin/index.php?act=X` URLs and the `role='1'` guard.

## Architecture
- Flow mirrors client: `admin/index.php (bootstrap) → Router → Admin\Controller\* → View(admin/view/...)`.
- Shared models get admin CRUD methods (e.g. `Product::create/update/delete`); read methods reused.

## Related Code Files (indicative — detail when phase is scheduled)
- **Create:** `src/Controller/Admin/{Dashboard,Product,Category,User,Bill,Comment,Question,Stats}Controller.php`
  (or `src/Admin/Controller/*`), extend `Model\*` with CRUD, `Model\Stats`.
- **Rewrite in place:** `admin/index.php` → thin front controller.
- **Delete:** `admin/model/*.php` (superseded), `admin/controller/controller.php` (replaced by `Core\View`).

## Implementation Steps (high level — expand at scheduling time)
1. Parameterize `Core\View` base dir (or `AdminController` path prefix) → `admin/view/`.
2. Add admin CRUD methods to shared `Model\*`; add `Model\Stats`.
3. Write admin controllers per `admin/index.php` cases.
4. Rewrite `admin/index.php` front controller; delete `admin/model/*` + `admin/controller/controller.php`.
5. Lint + full admin route walkthrough (login/role guard, product/category/user/bill/comment/question CRUD, stats).

## Todo List
- [ ] View base-dir parameterization
- [ ] Shared-model CRUD + Stats
- [ ] Admin controllers
- [ ] `admin/index.php` rewritten, old admin model/controller deleted
- [ ] Admin route walkthrough passes

## Success Criteria
- Admin login (role=1) + all CRUD screens function; URLs unchanged; no `admin/model/*.php` remain.
- Client app unaffected (shared models still serve client reads).

## Risk Assessment
- **Shared-model coupling (Med/High):** admin CRUD change breaks a client read. Mitigate: additive methods
  only; re-run client route checklist after admin changes.
- **Host mismatch (Low/Med):** `127.0.0.1` vs `localhost` in `Config`. Mitigate: verify admin connects post-merge.
- **Scope creep (High/Med):** stats/moderation are big. Mitigate: keep as separate follow-up if time-boxed.

## Rollback
- Separate commit(s); revert restores `admin/index.php` + `admin/model/*`. Client unaffected.

## Open Questions (flag to user — DECISION REQUIRED before starting)
1. Do admin now (Phase 06) or split into a separate follow-up plan after client ships? (Recommend: separate.)
2. Reconcile duplicate divergent models into shared `Model\*` with admin methods — confirm acceptable, or
   keep an admin-only model layer to avoid client coupling risk?
3. `Config` host: standardize on `localhost` for both apps? (admin uses `127.0.0.1` today.)

## Next Steps
- If deferred: close client plan at Phase 05; open a dedicated admin plan referencing this file.
