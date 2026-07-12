# Phase 03 — XSS output-escaping audit + fix

**Context:** [plan.md](plan.md) · Workstream 1 (security) · **Priority P1** · must land BEFORE 07–09

## Overview
Only 11 of 42 view files escape output (verified: client 7/19 use `htmlspecialchars`, admin 4/23).
Most echo DB/user values raw (`<?= $pro['name_pro'] ?>` pattern). Worst: stored-XSS via user comments.
Systematically escape every DB- or user-sourced echo across all views.

## Key insights (verified)
- **Stored-XSS, highest priority:** `public/view/comment-form.php` — comment `content` comes from
  `$_POST['content_cmt']` `:37`, persisted via `Comment::create()` `:48` unescaped, and rendered author
  fields echo raw at `:58` (`<?= $full_name ?> (<?= $user_name ?>)`). Comments are free-text end-user
  input → direct stored-XSS. Escape at OUTPUT (render), and treat all comment fields as tainted.
- Escaping must be at output time (render), not sanitize-on-store, to avoid double-encoding and keep
  raw data intact (KISS + correctness).
- Admin views (`admin/view/*.php`) echo product/category/user/order fields raw throughout — same fix.
- Intentional exception: JSON-into-`<script>` uses `json_encode()` (e.g. flash toasts) — that is a
  correct escaping mechanism for JS context; do NOT wrap those in `htmlspecialchars`.

## Data flow
```
DB/user value ─> view echo ─> htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ─> browser (inert text)
JS context     ─> json_encode($v) (already safe — leave as-is)
```

## Requirements
- Functional: every `<?= $dbOrUserVar ?>` in HTML context wrapped in `htmlspecialchars(... ENT_QUOTES, 'UTF-8')`.
- Non-functional: introduce a short helper `e($v)` (in `src/Core/`, or a global function) to keep call
  sites terse and DRY; use it consistently.
- Do not alter values written to DB (output-time only); do not touch `json_encode` JS-context echoes.

## Related code files
- Create: `src/Core/helpers.php` (or add to an existing bootstrap) — `function e($v): string { return
  htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }`. Ensure it's loaded by both front controllers.
- Modify (audit each, escape tainted echoes):
  - Client `view/*.php` + `public/view/*.php` — 19 files, prioritize `public/view/comment-form.php`
    (and wherever stored comment `content` is listed).
  - Admin `admin/view/*.php` — 23 files (list_*, add_*, update_*, billdetail, dashboard).
- Read for context: `src/Model/Comment.php` (confirm `content`/author stored raw → must escape on output).

## Implementation steps
1. Add `e()` helper; wire its `require` into `public/index.php` + `public/admin/index.php` bootstrap
   (or autoload via a `files` entry — but no composer dump available, so prefer explicit `require_once`).
2. Grep every view for echoed variables: `<?=\s*\$` and `echo \$`. Build the per-file list.
3. Escape each HTML-context DB/user echo with `e(...)`. Leave `json_encode(...)` JS-context echoes alone.
4. Comment flow first: `comment-form.php` author + content, and the comment listing view.
5. `php -l` all touched views; browser-render key pages to confirm no broken markup / double-encoding.

## Todo
- [x] `e()` helper + load in both front controllers
- [x] Enumerate echoed vars per view (record counts: client 19, admin 23)
- [x] Escape client views (comment flow first)
- [x] Escape admin views
- [x] Verify no double-encoding, no touched `json_encode` echoes
- [x] `php -l` clean

**Status: DONE (2026-07-12).** All 41 tracked views (client `view/*`, `public/view/comment-form.php`,
admin `admin/view/*`) escaped; ~84 echoes wrapped in admin views alone. `dashboard.php`'s
JS-string-literal context (`name_cate` inside `'...'`) fixed with `json_encode()` instead of `e()`
since that's JS context, not HTML — caught during verification, not part of the original per-file
plan. Concatenated URL variables (`$prodetail`, `$removepro`, `$linkpro`, etc.) escaped at build time
so their later bare echo is already safe. Untracked legacy Vietnamese-named duplicate files
(`view/sanpham/`, `admin/view/comment.php`, etc.) deliberately left untouched — dead pre-refactor
cruft, not part of the served app.

## Success criteria
- Grep audit: no HTML-context `<?= $dbVar ?>` remains unwrapped (each is `e()`-wrapped or justified as
  JS-context `json_encode`).
- Injecting `<script>alert(1)</script>` as a comment renders as inert text, not executed.

## Risk assessment
- **Med — over-escaping intentional HTML** (any view that deliberately echoes stored HTML markup?).
  Mitigation: trace each field's source; product/comment/user fields are plain text → safe to escape.
  If any field is meant to hold HTML, document the exception rather than blanket-wrapping.
- **Low — double-encoding** if a value is already escaped upstream. Mitigation: escape only at final output.
- **Coordination — admin views also edited by Phase 12 (styling).** Sequential; 03 edits echoes, 12 edits
  markup/classes. 03 first.

## Security considerations
- `ENT_QUOTES` (escape both quote types) + explicit `UTF-8`. Output-time escaping is the authoritative XSS defense.

## Next steps
- Must complete before admin MVC phases (07–09) so views are escaped once. Independent of 01/02/04.
