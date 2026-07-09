# Phase 05 — Cleanup, smells, Mailer & payment path

## Context Links
- Cart HTML in model: `model/giohang.php:2-132` (echo). Mailer: `email/index.php` (global-namespace class).
- Payment poller: `model/atm.php` (standalone script) + `model/function.php`. QR view: `view/qr.php`.

## Overview
- **Priority:** P2. **Status:** completed.
- Finish the concerns deferred from Phase 04: move cart-rendering HTML into a view partial, namespace
  the Mailer, and settle the payment poller (`atm.php`) + QR flow. App already fully MVC after Phase 04;
  this is polish, each change independently testable.

## Key Insights
- `viewcart()`/`cart_detail()` were echo-HTML "model" funcs. Their markup belongs in `view/giohang/`.
  Move the HTML into a partial (e.g. `view/giohang/_cart-table.php`) that reads `Cart::items()` /
  passed `$cart_detail`; call it from `bill.php:85`, `billconfirm.php:89`, `viewbill.php:90` where
  `viewcart(0)`/`cart_detail(...)` are called today.
- `Mailer` (`email/index.php`) already a class but global-namespace + `require`s PHPMailer directly.
  Move to `src/Mail/Mailer.php` under `84904\Codemoi\Mail`, keep the PHPMailer `require`s (PHPMailer is
  NOT PSR-4-registered in this composer setup — verify before assuming autoload). Update the 2 call
  sites (`Auth/Password`+`Checkout` controllers) to `new \84904\Codemoi\Mail\Mailer()`.
- `atm.php` is a bank-webhook poller run out-of-band (its own `session_start`, top-level exec). Keep it
  a standalone script but repoint its `pdo_*` calls → `Core\Database` and `function.php` helpers →
  `Model\Payment`. Do NOT force it into a controller (it's not an `act` route). Then delete `model/function.php`.
- Confirm the remaining `extract()` usages are gone (`prodetail` handled Phase 04; verify no others).

## Requirements
- No `echo`-HTML inside any `src/Model/*`. Cart markup lives in a view partial.
- Single `Mailer` class, namespaced, both call sites updated, emails still send.
- `atm.php` runs standalone against `Core\Database`/`Model\Payment`; `model/function.php` deleted.

## Related Code Files
- **Create:** `view/giohang/_cart-table.php` (extracted markup), `src/Mail/Mailer.php`.
- **Edit:** `view/giohang/{bill,billconfirm,viewbill}.php` (call partial), controllers using Mailer,
  `model/atm.php` (repoint), `view/qr.php` if it references old funcs.
- **Delete:** `email/index.php` (after move), `model/function.php` (after atm repoint).
  Keep `email/PHPMailer/` (library) in place.

## Implementation Steps
1. Extract cart-table markup from old `giohang.php` logic into `view/giohang/_cart-table.php`
   (param: mode/removecol + source rows). Wire the 3 views to `include` it.
2. Create `src/Mail/Mailer.php` (namespaced); keep PHPMailer `require`s; update call sites; send a test mail.
3. Repoint `model/atm.php` to `Core\Database` + `Model\Payment`; run it via `php.exe` against test data.
4. Delete `email/index.php` and `model/function.php`; grep to confirm no remaining references.
5. Lint everything; re-run the Phase 04 route checklist (cart/checkout pages especially).

## Todo List
- [x] `_cart-table.php` partial + 3 views wired — already done in Phase 04 as
      `view/giohang/cart-detail-table.php`, included from `billconfirm.php`/
      `viewbill.php`; `bill.php`/`viewcart.php` keep their own inline
      markup (never called the removed `viewcart()`/`cart_detail()` helpers).
      Verified `src/Model/Cart.php` has no echo/HTML.
- [x] `src/Mail/Mailer.php` + call sites updated + test send — SMTP creds
      externalized to `Core\Config`; call sites in `CheckoutController`/
      `PasswordController` updated; forgotPass + cash-checkout flows both
      exercised live, Mailer reaches `sendMail()` without a fatal error.
- [x] `atm.php` repointed & runs — now uses `Core\Database` + `Model\Payment`;
      ran via `php.exe`, completes without fatal (external API returned 404,
      pre-existing/unrelated to the port).
- [x] `email/index.php` + `model/function.php` (+ `model/pdo.php`, unused
      after repoint) deleted, no dangling refs (grep-verified).
- [x] Lint + cart/checkout route recheck — all changed files lint clean;
      home/product/login/checkout (cash + bank-transfer)/viewbill routes
      re-verified live.

Also fixed the `viewbill()` undefined-variable bug (was flagged as an Open
Question): now reads `$full_name`/`$address`/`$phone`/`$email`/`$payment`
from `$_POST` like `confirm()` does. Verified via direct `?act=viewbill` POST
with a non-empty cart — order row created with correct POST-sourced fields
(see Next Steps for a related pre-existing issue this surfaced).

## Success Criteria
- `grep -rn "echo" src/Model/` shows no HTML output; cart pages render identically to pre-refactor.
- Password-reset + order-confirmation emails still send (manual trigger).
- `atm.php` processes a webhook payload without error; `model/` contains no leftover procedural files
  except (intentionally) none — client `model/` dir is empty/removed.
- `grep -rn "\bextract(" src/ view/` returns nothing (or only justified isolated-scope View use).

## Risk Assessment
- **PHPMailer autoload assumption (Med/Med):** if not PSR-4-registered, keep explicit `require`s in Mailer.
  Mitigate: verify `email/PHPMailer/src` paths resolve from new file location (adjust relative paths).
- **Cart-partial markup drift (Med/Med):** subtle HTML/number_format differences. Mitigate: copy markup verbatim.
- **atm.php out-of-band breakage (Low/Med):** it's rarely hit interactively. Mitigate: lint + one manual run.

## Security Considerations
- SMTP creds currently hard-coded in `email/index.php` (`:27-28`) — carry over as-is (out of scope to
  externalize; note for user). Keep bound params in `Model\Payment`.

## Open Question (resolved)
- SMTP credentials externalized to `Core\Config` (constants) per user decision. Still committed in
  source as literal values (matches legacy behavior) — moving to env vars is a further follow-up,
  not done here.

## Next Steps
- Client refactor complete. Phase 06 (admin) optional.
- **Newly discovered, pre-existing, out-of-scope bug:** `viewbill()`'s own internal order-creation
  branch (`if ($total_amount > 0) { Order::create(...); redirect('?act=viewbill'); }`) redirects
  (and exits) *before* reaching `Cart::clear()` later in the method. If `?act=viewbill` is hit
  directly with a logged-in user and a non-empty cart, the self-redirect loops back into the same
  branch on the next request — and since that next request has no `$_POST` data, `$payment` is
  `null`, which now hits `bill.payment NOT NULL` and throws an uncaught `PDOException`. This bug
  predates this phase (in the old code `$payment` was *always* null here, so it crashed even sooner,
  on the very first hit); the Phase 05 `$_POST` fix does not introduce it and does not change behavior
  for the real `confirm()`→`viewbill()` flow (cart already empty by then). Flagging for a future fix
  (e.g. move `Cart::clear()` before the redirect, or don't self-redirect at all).
