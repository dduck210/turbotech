# Phase 03 — Admin Redesign

Follow [[design-system.md]] exactly. Genuine layout restructuring, not a
class-rename pass — the user explicitly rejected a color-only redesign
before.

## Files you own (edit freely, nothing else)

Layout partials (included around every admin page):
- `admin/view/header.php` — `<head>` + font links (swap to Cormorant
  Garamond like the client side), favicon already points at
  `../assets/images/menu/logo/favicon.svg`, leave that.
- `admin/view/nav.php` — sidebar. Keep the same nav items/links/PHP
  logic (`nav_link_class()` etc.) but reskin as a cream sidebar, thin
  right border, bronze left-accent bar for the active item — no
  gradient wash, no glow.
- `admin/view/topbar.php`, `admin/view/footer.php`
- `admin/view/login.php` — pick whichever logo SVG variant fits the
  final panel background (`public/assets/images/menu/logo/
  logo-wordmark-dark.svg` if you keep a dark accent panel,
  `logo-wordmark-light.svg` if it's light/cream like the rest).

Pages:
- `admin/view/dashboard.php`
- `admin/view/list_product.php`, `add_product.php`, `update_product.php`
- `admin/view/list_category.php`, `add_category.php`, `update_category.php`
- `admin/view/list_user.php`, `update_user.php`
- `admin/view/list_bill.php`, `update_bill.php`, `billdetail.php`
- `admin/view/list_coupon.php`, `add_coupon.php`, `update_coupon.php`
- `admin/view/list_comment.php`, `comment.php`
- `admin/view/list_question.php`
- `admin/view/list_statistic.php`

## Do NOT touch

`src/css/*.css` (phase 1, already done), anything under `view/` or
`public/view/` (client side).

## Verification before reporting done

1. `"/c/xampp/php/php.exe" -l <file>` on every file you touched.
2. Grep your owned files for `glass|btn-glow|text-gradient|card-glow|
   animate-float|animate-glow-pulse|animate-gradient-shift|bg-shape` —
   none should remain (bg-shape was the blurred decorative blob class on
   the login page, also removed from the admin token CSS).
3. Do not run the Tailwind CLI build yourself — phase 4 does one final
   build after this phase also finishes, to avoid clobbering the shared
   compiled CSS output mid-edit.
