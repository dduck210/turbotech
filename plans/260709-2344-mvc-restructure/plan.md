---
title: "MVC Restructure — Turbotech client app (classes + namespaces + Router)"
description: "Convert procedural PHP client app to lightweight custom MVC on PSR-4, no behavior/URL/schema change."
status: pending
priority: P2
effort: 9h (client) + 3h (admin, deferred)
branch: refactor/mvc-restructure
tags: [refactor, mvc, php, psr-4, architecture]
created: 2026-07-09
---

# MVC Restructure — Turbotech

Convert plain-procedural client app (`index.php` switch + `model/*.php` functions + `view/*.php`)
into a lightweight custom MVC (classes + `84904\Codemoi\` namespace + Router) on the existing
composer PSR-4 autoloader. Behavior, `index.php?act=X` URLs, and DB schema UNCHANGED.

## Key Decisions (locked — see phase files for justification)

- **Class tree lives under `src/`** (existing PSR-4 root `84904\Codemoi\ => src/`). Static assets
  `src/css|js|image` STAY put. Rationale: no `composer` binary in this env (verified — cannot
  `dump-autoload`); PSR-4 resolves new files under an already-mapped root at runtime with zero
  regen; and moving assets would churn ~23 URL refs across 7 view files for no functional gain.
  Namespaces: `Core\`, `Model\`, `Controller\` under `src/`.
- **Views stay** as plain PHP templates in `view/` (unchanged paths), rendered by `Core\View`
  (isolated-scope `include`, no global `extract`). No template engine (YAGNI).
- **Cart** → `Model\Cart` wrapping `$_SESSION['mycart']`; **Auth** → `Model\Auth`/`User` wrapping
  `$_SESSION['user']`. Session shapes unchanged.
- **Admin app is DEFERRED** to Phase 6 (optional follow-up). Client refactored first, admin reuses
  shared models later. Two apps stay separate. See phase-06.
- **Strangler sequencing**: old procedural code stays live until Phase 4 flips the front controller;
  every phase leaves the app runnable + manually testable (browser / `Invoke-WebRequest`;
  lint via `C:\xampp\php\php.exe -l`). No automated test suite exists.

## Phases

| # | File | Focus | Runnable after? | Effort |
|---|------|-------|-----------------|--------|
| 1 | [phase-01-foundation.md](phase-01-foundation.md) | `src/` layout, `Core\Database`, `Config` | yes (old app untouched) | 1h |
| 2 | [phase-02-model-classes.md](phase-02-model-classes.md) | Product/Category/User/Cart/Order/Question/Comment/Payment models | yes (old app untouched) | 2h |
| 3 | [phase-03-core-mvc.md](phase-03-core-mvc.md) | `Core\Router`, base `Controller`, `Core\View` | yes | 1.5h |
| 4 | [phase-04-controllers-switchover.md](phase-04-controllers-switchover.md) | Client controllers + rewrite `index.php`, delete old `model/*.php` | yes (new MVC live) | 3h |
| 5 | [phase-05-cleanup-smells.md](phase-05-cleanup-smells.md) | Kill `extract()` smells, cart-HTML→view, Mailer namespace, payment path | yes | 1.5h |
| 6 | [phase-06-admin-deferred.md](phase-06-admin-deferred.md) | Admin app MVC reusing shared models (OPTIONAL) | yes | 3h |

## Dependencies

- 1 → 2 → 3 → 4 (strict; 4 is the switchover, needs 1-3 done).
- 5 after 4. 6 after 5 (reuses `Model\*` + `Core\*`).

## Verification per phase

Lint all touched files (`php.exe -l`), then hit affected routes at `http://localhost/codemoi1/index.php?act=...`
in browser. See each phase's Success Criteria for the exact route checklist.

## Open Questions

See end of phase-04 and phase-06.
