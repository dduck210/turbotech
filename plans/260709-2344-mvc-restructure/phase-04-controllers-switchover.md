# Phase 04 — Client controllers + front-controller switchover

## Context Links
- Whole old dispatch: `index.php:1-454`. Shared home data: `index.php:19-28`. Header uses `$listcate`
  + `countcart()` (`view/header.php:3`). Content uses `$prohome/$list_topsp/$list_bestsp` (`view/content.php`).

## Overview
- **Priority:** P1 (the switchover). **Status:** pending.
- Write client controllers, rewrite `index.php` into a thin front controller using `Core\Router`,
  then DELETE the old procedural `model/*.php` (client) and the switch. After this phase the app runs
  entirely on the new MVC. This is the one high-blast-radius phase — do it as a single focused commit
  with a full route walkthrough before merging.

## Key Insights — route → controller map
| act | Controller::method (src/Controller/) | Template | Notes |
|---|---|---|---|
| (none)/default | `HomeController::index` | content.php | loads featured/best/home lists |
| product | `ProductController::index` | sanpham/sanpham.php | keeps kyw/idcate/min/max parse+swap (`index.php:40-48`) |
| prodetail | `ProductController::detail` | sanpham/sanphamct.php | view-count once-per-session (`index.php:59-68`); NO `extract` — pass `$one_pro` as array/fields |
| register | `AuthController::register` | nguoidung/register.php | |
| login | `AuthController::login` | nguoidung/login.php | sets `$_SESSION['user']` via `Auth::login` |
| logout | `AuthController::logout` | — | `session_unset` → redirect login |
| mk | `PasswordController::methods` | nguoidung/cachthuclaymk.php | |
| usermk | `PasswordController::byNameEmail` | nguoidung/laymk2.php | |
| forgotPass | `PasswordController::forgot` | nguoidung/forgotpass.php | sends code via Mailer |
| verification | `PasswordController::verification` | nguoidung/verification.php | |
| changePass | `PasswordController::change` | nguoidung/changePass.php | |
| myaccount | `AccountController::index` | nguoidung/myaccount.php | auth-guard → redirect login |
| viewcart | `CartController::view` | giohang/viewcart.php | |
| edit | `CartController::edit` | (no view; POST) | `Cart::update` |
| addtocart | `CartController::add` | giohang/viewcart.php | `Cart::add` |
| removecart | `CartController::remove` | — | redirect viewcart |
| bill | `CheckoutController::bill` | giohang/bill.php | |
| pay | `CheckoutController::pay` | qr.php | |
| billconfirm | `CheckoutController::confirm` | giohang/billconfirm.php | order create + mail + payment branch |
| viewbill | `CheckoutController::viewbill` | giohang/billconfirm.php | |
| question | `QuestionController::index` | hoidap/question.php | |
| introduce | `PageController::introduce` | gioithieu.php | |
| contact | `PageController::contact` | lienhe.php | |

- **Preserve exact behaviors:** the min/max swap (`index.php:44-46`); once-per-session view increment
  (`index.php:59-68`); login-required guards for myaccount/billconfirm (`index.php:210,292,346`);
  `alert()`+`header()` redirect patterns. Port them into controller methods verbatim in intent.
- **`$data` variable names MUST match templates** (e.g. `sanpham.php` reads `$listpro`,`$namecate`,`$listcate`;
  `sanphamct.php` reads product columns + `$similar_pro`). Verify each template's `$` reads before finalizing.
- **Front-controller shared state:** header needs `$listcate` (`Category::all`) + cart count; keep
  `head.php`/`header.php` include before dispatch and `footer.php` after, passing `$listcate` into header scope.

## Requirements
- Thin `index.php`: `session_start`; `ob_start`; `require vendor/autoload.php`; init cart session array;
  build header data; include chrome; build `Router` route table (map above); `dispatch($_GET['act'] ?? '')`;
  include footer; `ob_end_flush`.
- Each controller <200 lines; split if needed (Checkout is largest — keep confirm/viewbill tight).

## Related Code Files
- **Create:** `src/Controller/{Home,Product,Auth,Password,Account,Cart,Checkout,Question,Page}Controller.php`.
- **Rewrite in place:** `index.php` (do NOT create `index-new.php` — replace directly per dev-rules).
- **Delete:** `model/sanpham.php`, `model/loai.php`, `model/nguoidung.php`, `model/giohang.php`,
  `model/hoadon.php`, `model/question.php`, `model/binhluan.php` (client copies now superseded by `src/Model/*`).
- **Keep for now:** `model/function.php`, `model/atm.php`, `email/index.php` → handled in Phase 05.

## Implementation Steps
1. Write all 9 controllers, each method porting the matching `case` body onto `Model\*`/`Core\View`.
2. For `prodetail`: replace `extract($one_pro)` — pass `$one_pro` (array) + fields the template needs.
3. Rewrite `index.php` as front controller with the route table.
4. Delete the 7 superseded `model/*.php` files.
5. Lint every new/changed file. Grep for any lingering `pdo_query`/old-func calls in `view/` — fix
   references that break (e.g. `view/header.php` `countcart()` → `Cart::count()`; `myaccount.php`
   `loadall_countcart` → `Order::itemCount`; `binhluan/formbinhluan.php` comment funcs → `Model\Comment`).
6. Full manual route walkthrough (see Success Criteria).

## Todo List
- [ ] 9 controllers written & lint-clean
- [ ] `index.php` rewritten as front controller
- [ ] 7 old client `model/*.php` deleted
- [ ] view/*.php global-func references repointed to `Model\*`
- [ ] Full route walkthrough passes

## Success Criteria (manual route checklist — all render without PHP error)
- Home `/`; `?act=product` (+ `&kyw=`,`&idcate=`,`&min_price=&max_price=`); `?act=prodetail&idpro=<id>`
  (view count +1 once/session); register; login (sets session)/logout; mk/usermk/forgotPass/verification/changePass;
  myaccount (logged-in) + redirect when logged-out; viewcart/addtocart/edit/removecart; bill/pay/billconfirm/viewbill
  (order row inserted, cart cleared); question; introduce; contact.
- No `model/sanpham.php` etc. remain; `grep -rn "pdo_query\|loadall_pro(" view/ index.php` returns nothing.

## Risk Assessment
- **Behavior inversion in Checkout (High/High):** confirm/viewbill have payment branches + session flags
  (`index.php:322-372,386-425`). Mitigate: trace each branch; test cash (payment=1) AND qr (2/3) paths.
- **Template var mismatch (High/High):** page renders blank/undefined. Mitigate: per-route grep of template `$` reads.
- **Deleting models too early (Med/High):** a view still calls an old func. Mitigate: step 5 grep BEFORE deleting; delete last.

## Rollback
- Single phase = single commit on `refactor/mvc-restructure`. Revert commit → old `index.php` + `model/*.php`
  restored from git; `main` remains the untouched safety net.

## Security Considerations
- Preserve login guards; keep bound params (now in `Model\*`). No new user-controlled includes (fixed route table).

## Open Questions (flag to user)
1. `billconfirm` currently references a stray external URL `http://localhost/duan1-turbotech/...` in the
   confirmation email (`index.php:336`) and `viewbill` reads undefined `$full_name/$address/$phone/$payment`
   (`index.php:392-396` commented, but used at `:402,415`). Preserve the latent bug as-is, or fix opportunistically?
2. Keep both `billconfirm` and `viewbill` (near-duplicate order-insert logic), or collapse to one? (URL impact.)

## Next Steps
- Phase 05 cleans smells + payment/Mailer.
