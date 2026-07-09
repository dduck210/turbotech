# Phase 01 — Secure + Unify Product Search / Filter

## Context Links
- Plan overview: [plan.md](plan.md)
- Route entry: `index.php:39-53` (case `'product'`)
- Model: `model/sanpham.php:18-30` (`loadall_pro`)
- PDO helper: `model/pdo.php:43-58` (`pdo_query`, variadic bound args)
- Views: `view/sanpham/sanpham.php:131-136` (listing form), `view/header.php:245-251` (quick search)
- Category source: `model/loai.php:2-6` (`loadall_cate` → `$listcate`, available globally via index.php:22)

## Overview
- **Priority:** P1 (fixes an active SQL injection).
- **Status:** pending.
- Fix SQLi in `loadall_pro`, extend it with a bound price-range filter, and unify the
  listing UI into one GET form carrying keyword + category + min/max price.

## Key Insights (verified against code)
- `loadall_pro` concatenates `$kyw`/`$idcate` directly → **SQL injection** (sanpham.php:20-26).
- `pdo_query` does `array_slice(func_get_args(),1)` then `$stmt->execute($sql_args)`
  (pdo.php:44-48) → passing `pdo_query($sql, ...$args)` binds `?` placeholders. No helper edit.
- **Latent bug:** index.php:40-44 sets `$kyw = " "` (a single space) when no keyword, so
  category-only browsing runs a spurious `name_pro LIKE '% %'`. `trim()` on input removes it.
- Method mismatch is the root cause the user hit: search forms are POST (kyw only), sidebar
  category filters are GET `<a>` (idcate only) → they never combine. Moving to GET unifies them.
- `$listcate` is already loaded in index.php:22 before the view include → usable to build a
  category `<select>` with no extra query (DRY).

## Requirements
**Functional:** search by name; filter by brand/category (existing `category`); filter by
price range (min/max); all combine in one request; state reflected in URL + repopulated form;
existing empty-state / grid-list / add-to-cart preserved.
**Non-functional:** all user input parameterized (no concatenation); KISS — 4 files, no new files.

## Architecture / Data Flow
```
[header quick-search GET kyw]  ┐
[listing form GET kyw,idcate,  ├─▶ index.php case 'product'
   min_price,max_price]        │      sanitize → loadall_pro($kyw,$idcate,$min,$max)
[sidebar <a> GET idcate]       ┘      → pdo_query($sql, ...$args)  → $listpro
                                      → view/sanpham/sanpham.php (renders + repopulates form)
```

## Related Code Files
**Modify (4, no new/deleted files):**
1. `model/sanpham.php` — rewrite `loadall_pro` (lines 18-30): add `$min=0,$max=0` params,
   build WHERE with `?` placeholders, escape LIKE wildcards, `return pdo_query($sql, ...$args)`.
2. `index.php` — case `'product'` (lines 40-50): read all filters from `$_GET`, sanitize
   (trim keyword; int-cast + `is_numeric`/`>0` guard prices; swap if min>max), call extended
   `loadall_pro`. Change keyword source from `$_POST` to `$_GET`.
3. `view/sanpham/sanpham.php` — replace the POST form (lines 131-136) with one GET form:
   keyword input + category `<select name="idcate">` (from `$listcate`) + `min_price`/`max_price`
   number inputs + submit; repopulate each field from `$_GET`. `action="index.php?act=product"`.
4. `view/header.php` — line 245: change `method="post"` → `method="get"`; add hidden
   `<input type="hidden" name="act" value="product">` (GET form drops the query string), keep
   `name="kyw"` only.

## Implementation Steps
1. **model/sanpham.php** — replace `loadall_pro`:
   ```php
   function loadall_pro($kyw = "", $idcate = 0, $min = 0, $max = 0) {
       $sql = "SELECT * FROM product WHERE 1";
       $args = [];
       $kyw = trim($kyw);
       if ($kyw !== '') {
           $esc = str_replace(['%', '_'], ['\%', '\_'], $kyw); // literal wildcards
           $sql .= " AND name_pro LIKE ?";
           $args[] = '%' . $esc . '%';
       }
       if ($idcate > 0)  { $sql .= " AND idcate = ?"; $args[] = $idcate; }
       if ($min > 0)     { $sql .= " AND price >= ?"; $args[] = $min; }
       if ($max > 0)     { $sql .= " AND price <= ?"; $args[] = $max; }
       $sql .= " ORDER BY id_pro desc";
       return pdo_query($sql, ...$args); // empty $args → pdo_query($sql), still valid
   }
   ```
   (Default MySQL LIKE escape char is `\`, so `\%`/`\_` match literally — no ESCAPE clause needed.)
2. **index.php** case `'product'` — replace lines 40-50 body:
   ```php
   $kyw    = isset($_GET['kyw']) ? trim($_GET['kyw']) : "";
   $idcate = (isset($_GET['idcate']) && $_GET['idcate'] > 0) ? (int)$_GET['idcate'] : 0;
   $min    = (isset($_GET['min_price']) && is_numeric($_GET['min_price']) && $_GET['min_price'] > 0) ? (int)$_GET['min_price'] : 0;
   $max    = (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] > 0) ? (int)$_GET['max_price'] : 0;
   if ($min > 0 && $max > 0 && $min > $max) { [$min, $max] = [$max, $min]; }
   $listpro  = loadall_pro($kyw, $idcate, $min, $max);
   $namecate = load_namecate($idcate);
   ```
3. **view/sanpham/sanpham.php** — replace form (131-136) with GET form; escape output with
   `htmlspecialchars` on repopulated keyword; mark selected `<option>` when
   `$_GET['idcate'] == $id_cate`; prefill price inputs from `$_GET['min_price']/['max_price']`.
4. **view/header.php** — swap method to `get` + add hidden `act=product` (step in Files #4).
5. **Compile check:** run `php -l` on each modified PHP file (see Success Criteria).

## Todo List
- [ ] Rewrite `loadall_pro` with bound params + LIKE escaping + trim
- [ ] Update index.php `product` case to read/sanitize GET filters + min/max swap
- [ ] Rebuild listing form in sanpham.php as combined GET form (keyword/category/price) with repopulation
- [ ] Switch header quick-search to GET + hidden `act=product`
- [ ] `php -l` all four files; manual smoke test of combined filters
- [ ] Update `docs/project-changelog.md` (SQLi fix + combined filter) if docs dir exists

## Success Criteria
- `grep` shows no `.` string concatenation of `$kyw`/`$idcate`/price into SQL in `loadall_pro`.
- `php -l model/sanpham.php index.php view/sanpham/sanpham.php view/header.php` all "No syntax errors".
- Manual: `index.php?act=product&kyw=dell&idcate=2&min_price=10000000&max_price=30000000`
  returns only matching rows; form fields repopulate; removing params widens results.
- Category-only browse (sidebar link) no longer applies the phantom `LIKE '% %'`.
- Empty result still renders existing no-found block; grid/list toggle + add-to-cart intact.

## Risk Assessment
| Risk | Likelihood | Impact | Mitigation |
|------|-----------|--------|------------|
| POST→GET breaks any code reading `$_POST['kyw']` | Low | Med | Only readers are index.php:40 (this edit) + the two forms (this edit). Grep-confirmed no other `kyw` consumers. |
| Editing the WRONG `loadall_pro` (admin has its own) | Med | Med | `admin/model/sanpham.php:6` defines a SEPARATE `loadall_pro($idcate=0)` used only by `admin/index.php:172` + `admin/view/dashboard.php:5`. Admin includes its own model — do NOT touch it. Client edit is confined to `model/sanpham.php` only. |
| LIKE wildcard escaping wrong (no ESCAPE clause) | Low | Low | Default escape char `\` applies; wildcard search still works, just literal `%`/`_`. |
| Reflected XSS via repopulated `kyw` in form value | Med | Med | `htmlspecialchars()` on echoed keyword (step 3). |
| Non-numeric/negative price | Med | Low | `is_numeric` + `>0` guard + int cast (step 2). |
| Header GET form loses `act` in query string | Med | Low | Hidden `act=product` input (step 4). |

## Backwards Compatibility / Migration
- No DB schema change; no data migration. Old bookmarked POST search simply stops carrying
  keyword — user re-searches via the (now GET) form. Old `?act=product&idcate=X` links keep working.

## Rollback
- Pure code change across 4 files on a feature branch; revert the branch/commit to restore.
  No stateful/irreversible operations.

## Security Considerations
- Primary goal: eliminate SQL injection via bound `?` parameters (fixes the actual vuln).
- Add output encoding (`htmlspecialchars`) on the reflected keyword to prevent reflected XSS.
- Price/idcate int-cast defends against type juggling.

## Next Steps / Out of Scope
- Pagination (commented-out UI at sanpham.php:230-265 / 341-374) — not implemented.
- Sidebar category `<a>` links still reset keyword/price (fresh GET). Optional future
  enhancement: append current query string. Left out per YAGNI.

## Unresolved Questions
- Price input UX: free-number inputs (assumed) vs preset ranges (`<select>`)? Plan assumes
  free min/max number inputs — confirm if preset brackets are preferred.
