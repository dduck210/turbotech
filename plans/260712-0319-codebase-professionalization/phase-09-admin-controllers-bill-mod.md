# Phase 09 — Admin controllers: bill/order, moderation, statistics

**Context:** [plan.md](plan.md) · Workstream 3 (admin MVC) · **Priority P2** · depends on Phase 07

## Overview
Port the remaining, more complex admin domains onto the MVC scaffold: bill/order management (list with
filters, detail, status transitions), comment moderation, question moderation, and statistics. These have
no direct client-side equivalent, so they get admin-scoped model methods (or a `Model\Stats`).

## Key insights (verified — `public/admin/index.php` case map)
- Bill/order: `list_bill:330` (with filters), `edit_bill:376`, `update_bill:397`, `approve_bill:410`,
  `ship_bill:432`, `cancel_bill:443`, `billdetail:443/466` (order detail). Status transitions
  approve→ship→cancel each set a flash (`:416/:427/:438`). Note commented-out `// case 'removebill':368`.
  Missing-order guard exists (`:383`, `:450` `flash_error 'Không tìm thấy đơn hàng này.'`).
- **Domain naming caveat:** the `cart` table is ORDER LINE ITEMS, not an active cart (the live cart is
  `$_SESSION`). Bill detail joins `bill` + `cart` line items. Preserve this — don't "fix" the naming.
- Comment moderation: `list_cmt:466`, `delete_cmt:479`.
- Question moderation: `list_ques:512`, `delete_ques:524` (and answering questions — confirm whether an
  `answer`/update path exists in this range; enumerate before porting).
- Statistics: `list_thongke:489` — backed by `admin/model/statistics.php`. No client equivalent → `Model\Stats`.

## Data flow
```
act ─> Router ─> Admin\Controller\{Bill,Comment,Question,Stats}
Bill:    list/detail read bill+cart(line items); status actions ─> Model\Order status update ─> flash
Moderate: list ─> Model\{Comment,Question}; delete ─> Model delete (+ FK guard if exposed)
Stats:   Model\Stats aggregate queries ─> list_statistic view
```

## Requirements
- Preserve all `act` strings, filter params on `list_bill`, status-transition semantics, and the
  missing-order guards + flashes.
- Reuse `Model\Order` for bill reads/status; add admin methods. New `Model\Stats` for statistics.
- Carry forward CSRF verify (base) + FK guards where deletes are exposed.

## Related code files
- Create: `src/Controller/Admin/{BillController,CommentController,QuestionController,StatsController}.php`
  (extend `AdminController`; keep <200 lines — split Bill if needed).
- Extend: `src/Model/Order.php` — admin methods (listWithFilters, findDetail, updateStatus/approve/ship/cancel).
  `src/Model/Comment.php`, `src/Model/Question.php` — admin list/delete/answer methods.
- Create: `src/Model/Stats.php` — aggregate queries (port `admin/model/statistics.php`).
- Modify: `public/admin/index.php` — register these routes; remove ported cases from old switch.
- Delete (progressively): `admin/model/{bill,comment,question,statistics}.php` once superseded.
- Reuse: `admin/view/{list_bill,update_bill,billdetail,list_comment,list_question,list_statistic}.php`
  (unchanged; escaped in Phase 03).

## Implementation steps
1. Enumerate exact bill status-transition logic + `list_bill` filter params (grep the case bodies) before porting.
2. BillController: list (filters), billdetail (bill+cart line items), edit/update, approve/ship/cancel
   (preserve flashes + missing-order guards). Extend `Model\Order` additively.
3. CommentController (list/delete + FK guard if exposed), QuestionController (list/delete/answer — confirm
   answer path). Extend `Model\Comment`/`Model\Question`.
4. StatsController + `Model\Stats` (port aggregate queries from `statistics.php`).
5. Register routes; strip ported cases; delete superseded `admin/model/*`.
6. `php -l`; walk every bill status transition, filter combo, moderation delete, and the stats page.

## Todo
- [ ] Enumerate bill filters + status-transition semantics
- [ ] BillController + `Model\Order` admin methods (list/detail/status; preserve guards+flashes)
- [ ] Comment + Question controllers + model methods (confirm question-answer path)
- [ ] StatsController + `Model\Stats`
- [ ] Routes registered; ported cases removed; superseded models deleted
- [ ] `php -l`; walk all transitions/filters/moderation/stats

## Success criteria
- Bill list/filters/detail and approve→ship→cancel transitions work via MVC with unchanged flashes/URLs.
- Comment + question moderation and statistics render correctly. No superseded `admin/model/*` remain for these domains.

## Risk assessment
- **High/Med — status-transition logic subtlety** (order state machine). Mitigation: enumerate exact
  current behavior first; port verbatim; test each transition + the missing-order guard path.
- **Med — `cart`-as-line-items confusion** leading to a wrong join in bill detail. Mitigation: preserve
  the existing query; do not rename or "correct" the schema.
- **Med — stats query drift** (aggregate correctness). Mitigation: compare new vs old stats numbers on
  the same data before deleting `statistics.php`.
- **Med — strangler half-state / same-file with 08.** Mitigation: sequential with 08; all `act` working per merge.

## Rollback
- Per-domain commits; revert restores old switch case + `admin/model/*`. Other domains unaffected.

## Security considerations
- CSRF + role guard via base. FK guards on exposed deletes. Output escaped (Phase 03).

## Next steps
- With 08, fully replaces the old switch. Phase 10 does the final full regression sweep before merge.
