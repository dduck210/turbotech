# Turbotech

A Laravel e-commerce application for laptops/electronics: product catalog,
cart, checkout with coupons and VietQR bank-transfer payment, order
management, and an admin dashboard.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

Fill in `.env`: `DB_*` (MySQL), `MAIL_*` (SMTP, used for OTP password
reset and admin question replies), `BANK_*` (VietQR checkout). `DB_COLLATION`
should stay `utf8mb4_general_ci` to match how the existing tables compare/sort
Vietnamese text — Laravel's own default collation sorts slightly differently.

`--seed` creates one default admin account: `admin` / `password`
(`email_user: admin@example.com`). **Change this password immediately** on
any real deployment — it's a fixed, publicly-known value meant only to get a
fresh install into a usable state.

This project has no dedicated Apache vhost — it's served from a
subdirectory of a shared `htdocs/`. The project-root `.htaccess` forwards
requests into `public/`, and `public/index.php` corrects `SCRIPT_NAME`
before Laravel's `Request::capture()` runs (see the comment in that file
for why: an internal rewrite into a subdirectory otherwise breaks
Symfony's request base-path detection). If you deploy this behind a real
vhost with `DocumentRoot` pointing straight at `public/`, that correction
is a no-op and can be left in place.

Uploaded product/avatar images live under `storage/app/public/` (reached
publicly via the `storage/` symlink) — they aren't version-controlled, so
a fresh clone starts with none until products are re-added or images are
copied over manually.
