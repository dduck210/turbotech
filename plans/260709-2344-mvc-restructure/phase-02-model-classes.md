# Phase 02 — Model classes

## Context Links
- Old models: `model/sanpham.php`, `model/loai.php`, `model/nguoidung.php`, `model/giohang.php`,
  `model/hoadon.php`, `model/question.php`, `model/binhluan.php`, `model/function.php`, `model/atm.php`.

## Overview
- **Priority:** P1. **Status:** pending.
- Wrap each domain's SQL functions in a `Model\*` class using `Core\Database`. Old procedural files
  stay live (old `index.php` still uses them) — new classes are additive until Phase 04.

## Key Insights
- **`model/giohang.php` mixes concerns:** `viewcart()` (`:2-88`) and `cart_detail()` (`:89-132`) ECHO
  HTML — those are VIEW helpers, not model. `Model\Cart` gets DATA methods only
  (`add/update/remove/items/count/total/clear` over `$_SESSION['mycart']`); the HTML moves to a view
  partial in Phase 05. `countcart():134`, `total_amount():142`, `insert_cart():152`, `loadall_cart():157`,
  `loadall_countcart():163` map to Cart/Order methods.
- **Cart tuple shape is positional** `[id,name,img,price,qty,total]` (`index.php:260`). Preserve indices
  exactly inside `Cart` (edit uses `[4]`=qty, `[5]`=total: `index.php:230-231`). Do NOT switch to assoc
  keys now (would break `view/giohang/*` reads) — flag as future cleanup.
- `load_namecate()` does `extract($cate); return $name_cate` (`loai.php:11-13`) — code smell; new
  `Category::name($id)` returns the single column directly, no `extract`.
- Comment funcs are used ONLY inside `view/binhluan/formbinhluan.php:7,37` (not included by `index.php`).
  Provide `Model\Comment` so that view can migrate; low priority.
- `function.php` (`parse_order_id`, `curl_get`, `checkcode`, `checkbill`) + `atm.php` are the payment
  poller — group as `Model\Payment` (+ keep `atm.php` as a standalone script, addressed Phase 05).

## Requirements
- One class per domain, static methods, method names readable (not verbatim Vietnamese func names),
  each file <200 lines. Bound params preserved verbatim from source SQL.

## Architecture — mapping table
| New class (src/Model/) | Methods (← old function) |
|---|---|
| `Product.php` | `allHome`←loadall_pro_home; `one`←loadone_pro; `search`←loadall_pro($kyw,$idcate,$min,$max); `bestSellers`←loadall_pro_best; `featured`←loadall_pro_noibat; `similar`←similar_pro; `incrementView`←updateview |
| `Category.php` | `all`←loadall_cate; `name`←load_namecate (no extract) |
| `User.php` | `register`, `check`←check_user, `checkPass`←check_pass, `byEmail`←getUserEmail (return-only, move the echo to controller), `updatePassword`←updatePass, `resetPassword`←forgetPass, `update`←update_user |
| `Auth.php` | `user()`/`check()`/`login($row)`/`logout()` over `$_SESSION['user']` |
| `Cart.php` | `items/add/update/remove/clear/count/total` over `$_SESSION['mycart']` (data only) |
| `Order.php` | `create`←insert_bill; `one`←loadone_bill; `allByUser`←loadall_bill; `items`←loadall_cart/load_cart_all; `itemCount`←loadall_countcart; `addItem`←insert_cart; `statusLabel`←get_stt |
| `Question.php` | `create`←question |
| `Comment.php` | `create`←insert_comment; `forProduct`←loadall_comment |
| `Payment.php` | `parseOrderId`, `curlGet`, `findByTran`←checkcode, `findBillByCode`←checkbill |

## Related Code Files
- **Create:** `src/Model/{Product,Category,User,Auth,Cart,Order,Question,Comment,Payment}.php`.
- **Read for parity:** all `model/*.php`.
- **Delete:** none this phase (Phase 04 deletes old `model/*.php`).

## Implementation Steps
1. Create each class, port SQL verbatim, swap `pdo_*` calls → `Core\Database::*`.
2. For `User::byEmail` and `Category::name`, DROP the inline `echo`/`extract` — return data only.
3. `Cart`/`Auth` operate on `$_SESSION`; guard `session_start()` assumed done by front controller.
4. Lint all. Scratch-test 2-3 read methods (`Product::search`, `Category::all`) via `php.exe`.

## Todo List
- [ ] 9 model classes created & lint-clean
- [ ] echo/extract removed from `User::byEmail`, `Category::name`
- [ ] Scratch read-method test passes
- [ ] Old app still renders (untouched)

## Success Criteria
- All model files lint clean, each <200 lines.
- `Product::search('',0,0,0)` returns same rows as `loadall_pro('',0,0,0)` (spot compare in scratch).
- Existing site unaffected.

## Risk Assessment
- **Cart index drift (Med/High):** off-by-one on tuple indices breaks edit/total. Mitigate: unit-check
  add→total→update→remove in scratch against known values.
- **Signature mismatch (Med/Med):** `search` must keep 4 optional args in same order as `loadall_pro`.

## Security Considerations
- Keep bound params. `Product::search` keeps the existing LIKE-escaping (`sanpham.php:24`).

## Next Steps
- Phase 03 adds Router/View to invoke these.
