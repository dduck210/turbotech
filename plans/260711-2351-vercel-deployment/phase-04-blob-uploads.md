# Phase 04 — Migrate file uploads to Vercel Blob

**Context:** [plan.md](plan.md) · depends on [phase-02](phase-02-env-driven-db-config.md)

## Overview
- **Priority:** P1
- **Status:** pending
- **Description:** Move disk-writing uploads to Vercel Blob (durable object storage); store returned
  URL instead of a local filename. One-time migration re-uploads existing images and rewrites DB rows.

## Key insights
- Serverless: only `/tmp` is writable and it's wiped between invocations; `move_uploaded_file` to
  `./uploads/` fails/loses files. Two writers found:
  - **Admin product images:** `public/admin/index.php:143-150` (add) and `:222-226` (update) —
    `$_FILES['img_pro']`, `move_uploaded_file(...,"./uploads/"+name)` → resolves to
    `public/admin/uploads/` (22 existing files verified). Stores bare filename in `img_pro` via
    `add_pro()` / `update_pro()`.
  - **Account avatar:** `src/Controller/AccountController.php:49-55` — `$_FILES['img_user']`,
    `move_uploaded_file(...,'uploads/'+name)` → resolves to `public/uploads/` (1 existing file
    verified). Stores filename via `User::update()`.
- NOTE the task brief also mentioned category/coupon/comment image uploads; grep of
  `public/admin/index.php` shows only `img_pro` uses `move_uploaded_file`. **Category/coupon/comment
  upload writers NOT found in that file** — verify during implementation whether those entities have
  image-upload paths elsewhere; if none, migration scope for existing files is only product images
  (`public/admin/uploads/`) + avatar (`public/uploads/`). [Scope to confirm at impl start.]
- Where filenames are READ/rendered: templates build `<img src>` from the stored value. If we store a
  full Blob URL, render sites must emit the value as-is (absolute URL) rather than prefixing a local
  path. Must enumerate render sites for `img_pro`/`img_user` before switching.

## Data flow
Upload: browser file ──> PHP `$_FILES` (tmp in `/tmp`) ──> Blob PUT (token) ──> returns public URL
──> URL stored in `img_pro`/`img_user`. Render: template emits stored URL directly.

## Requirements
- Functional: admin can upload a product image on Vercel and it persists + renders; avatar likewise.
  Existing images keep rendering (migrated).
- Non-functional: keep local XAMPP path working — when `BLOB_READ_WRITE_TOKEN` unset, fall back to
  the current `move_uploaded_file` local behavior (dev unchanged).

## Related code files
- Create: `src/Core/Blob.php` — `Blob::put(string $tmpPath, string $filename): string` returns public
  URL; uses Vercel Blob REST (`PUT https://blob.vercel-storage.com/...` with
  `Authorization: Bearer $BLOB_READ_WRITE_TOKEN`) via cURL. If token unset → return null (signal
  caller to use local fallback).
- Modify: `public/admin/index.php:143-150` and `:222-226` — replace `move_uploaded_file` block with
  `Blob::put()`; store returned URL (fallback to local move + filename when token unset). Keep the
  `add_pro`/`update_pro` calls; only the value passed as `img_pro` changes (URL vs filename).
- Modify: `src/Controller/AccountController.php:49-55` — same pattern for `img_user`.
- Create: `scripts/migrate-uploads-to-blob.php` — enumerate `public/admin/uploads/*` and
  `public/uploads/*`, upload each to Blob, map old-filename → new-URL, then `UPDATE product SET
  img_pro=? WHERE img_pro=?` and `UPDATE user SET img_user=? WHERE img_user=?`.
- Verify (read): render sites for `img_pro` (`view/product/product-list.php`,
  `view/product/product-detail.php`, admin `list_product.php`, `update_product.php`,
  `view/content.php`) and `img_user` (`view/user/myaccount.php`) — confirm they emit the stored
  value in a way compatible with an absolute URL.

## Implementation steps
1. Confirm upload-writer scope (grep `move_uploaded_file` + `$_FILES` across `admin/` and `src/` — is
   there any category/coupon/comment writer? If yes add to scope; if no, note product+avatar only).
2. Enumerate render sites for `img_pro`/`img_user`; note any that prefix a local dir (e.g.
   `"uploads/".$img`) — these must switch to emit the raw stored value when it's an absolute URL
   (detect `str_starts_with($v,'http')`).
3. Create `Blob.php` (cURL PUT + Bearer token, returns URL or null).
4. Rewire the 3 upload sites: `$url = Blob::put($tmp,$name); $img = $url ?? localFallback();`.
5. Write `scripts/migrate-uploads-to-blob.php`; dry-run (log mapping) then execute UPDATEs.
6. Update render sites to handle absolute-URL values.

## Todo
- [ ] Confirm full upload-writer scope
- [ ] Enumerate + note `img_pro`/`img_user` render sites
- [ ] Create `src/Core/Blob.php`
- [ ] Rewire admin product upload (2 blocks) + avatar upload
- [ ] Render sites handle absolute URLs
- [ ] `scripts/migrate-uploads-to-blob.php` (dry-run → execute)
- [ ] User creates Blob store + supplies token

## Success criteria
- New admin product upload on Vercel: image PUTs to Blob, URL stored, renders on storefront.
- Existing migrated images render (no broken `<img>`).
- Local (token unset): upload still writes to disk + renders (unchanged).

## Risk assessment
- **HIGH — broken images if render sites prefix local path** to a now-absolute URL. Mitigation:
  step 2 enumeration + `str_starts_with('http')` guard at each render site.
- **MED — migration partial failure** leaves mixed local/URL values. Mitigation: idempotent script
  (skip rows already URL), dry-run mapping log, run inside a transaction per table.
- **MED — scope gap** (category/coupon/comment uploads exist but unmigrated). Mitigation: step 1
  explicit scope confirmation before coding.
- **LOW — Blob token exposure.** Mitigation: token only in env, never rendered/committed.

## Security considerations
- Validate upload extension/mime before Blob PUT (current code does minimal checking — preserve at
  least existing `pathinfo` extension check at `public/admin/index.php:146`).
- Blob objects are public-read by design — acceptable for product/avatar images (already public).

## Rollback
- Unset `BLOB_READ_WRITE_TOKEN` → code falls back to local writes (dev). DB rows already migrated to
  URLs stay valid as long as Blob store exists; keep local originals until migration verified.

## Next steps
- Feeds Phase 05 (env `BLOB_READ_WRITE_TOKEN`), Phase 06/07 (upload smoke test).
