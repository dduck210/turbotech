# Phase 02 — Client Redesign

Follow [[design-system.md]] exactly. Genuine layout restructuring, not a
class-rename pass — the user explicitly rejected a color-only redesign
before.

## Files you own (edit freely, nothing else)

Layout partials (included by `public/index.php` around every page):
- `view/head.php` — `<head>`: swap Google Fonts link to load
  "Cormorant Garamond" (weights 500/600/700) alongside Inter; keep the
  CSRF meta tag and tailwind.css link as-is.
- `view/header.php` — top nav bar: logo, nav links, search, cart
  dropdown, account dropdown, mobile menu. Restyle as a slim boutique
  storefront bar (thin bottom border, no blur/glass), pick whichever
  logo SVG variant fits the new header background
  (`public/assets/images/menu/logo/logo-wordmark-light.svg` if the
  header ends up light/cream).
- `view/footer.php` — site footer.

Pages (rendered inside head/header/.../footer):
- `view/content.php` — homepage. Rework the hero + sections into an
  editorial/boutique composition (see design-system.md's "Layout
  expectations").
- `view/product/product-list.php`, `view/product/product-detail.php`
- `view/cart/viewcart.php`, `view/cart/cart-detail-table.php`,
  `view/cart/bill.php`, `view/cart/billconfirm.php`
- `view/user/login.php`, `view/user/register.php`,
  `view/user/myaccount.php`, `view/user/forgot-password.php`,
  `view/user/verification.php`, `view/user/changePass.php`
- `view/qa/question.php`, `view/introduce.php`, `view/contact.php`
- `public/view/comment-form.php` (standalone page, own `<head>` — same
  font-link change as `view/head.php`, plus swap the favicon reference
  to the recolored SVG if not already done)

## Do NOT touch

- `view/binhluan/`, `view/giohang/`, `view/hoidap/`, `view/nguoidung/`,
  `view/sanpham/` — confirmed dead legacy duplicates from before the MVC
  refactor, not referenced by any controller (`grep -rn "this->view("
  src/Controller/*.php` only ever resolves to `content`, `product/*`,
  `cart/*`, `user/*`, `qa/question`, `introduce`, `contact`). Redesigning
  them would be wasted effort.
- `src/css/*.css` (phase 1, already done), anything under `admin/`.

## Verification before reporting done

1. `"/c/xampp/php/php.exe" -l <file>` on every file you touched.
2. Grep your owned files for `glass|btn-glow|text-gradient|card-glow|
   animate-float|animate-glow-pulse|animate-gradient-shift` — none
   should remain (see phase-01's note on why).
3. Do not run the Tailwind CLI build yourself — phase 4 does one final
   build after phase 3 also finishes, to avoid clobbering the shared
   compiled CSS output mid-edit.
