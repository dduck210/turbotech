# Phase 05 — Cleanup, smells, Mailer & payment path

## Context Links
- Cart HTML in model: `model/giohang.php:2-132` (echo). Mailer: `email/index.php` (global-namespace class).
- Payment poller: `model/atm.php` (standalone script) + `model/function.php`. QR view: `view/qr.php`.

## Overview
- **Priority:** P2. **Status:** pending.
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
- [ ] `_cart-table.php` partial + 3 views wired
- [ ] `src/Mail/Mailer.php` + call sites updated + test send
- [ ] `atm.php` repointed & runs
- [ ] `email/index.php` + `model/function.php` deleted, no dangling refs
- [ ] Lint + cart/checkout route recheck

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

## Open Question (flag to user)
- SMTP credentials are committed in source. Externalize to `Core\Config`/env now, or leave (out of scope)?

## Next Steps
- Client refactor complete. Phase 06 (admin) optional.
