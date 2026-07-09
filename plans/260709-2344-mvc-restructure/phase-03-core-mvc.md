# Phase 03 — Core MVC (Router, base Controller, View)

## Context Links
- Old dispatch: `index.php:36-451` (`switch($_GET['act'])`, ~19 cases).
- Old render precedent: `admin/controller/controller.php` (`render($path,$data)` uses `extract`+`include`).

## Overview
- **Priority:** P1. **Status:** pending.
- Build the three plumbing classes that replace the switch and the raw `include`s. Additive — not wired
  into `index.php` yet (that's Phase 04). App still runs on the old switch this phase.

## Key Insights
- Routing key stays `$_GET['act']` (URLs must not change). Router maps `act` string → `[Controller::class,'method']`.
- Missing/empty `act` and unknown `act` → home/default (`index.php:445-451`).
- `Core\View::render()` MUST use an **isolated scope** (extract inside a method, not global) to fix the
  current `extract($one_pro)`/`extract($cate)` leakage smell without changing view templates.
- Views read vars by their existing names (`$listpro`, `$listcate`, `$one_pro` fields, `$_SESSION[...]`).
  View::render passes a `$data` array the controller builds — keep the SAME variable names the templates expect.

## Requirements
- `Core\Router`: register routes, `dispatch(string $act): void`; unknown/empty → default route.
- `Core\Controller` (base): holds `view(string $template, array $data=[])` delegating to `Core\View`,
  plus `redirect(string $url)` helper (wraps `header('Location: ...')`).
- `Core\View`: `render(string $template, array $data=[])` → `extract($data); include "view/$template.php";`
  executed in a method scope so no caller-scope pollution.

## Architecture
- Flow: `index.php (bootstrap) → Router::dispatch($act) → Controller::method() → $this->view('sanpham/sanpham',$data) → Core\View`.
- View template paths are relative to project root `view/` (unchanged), e.g. `view/sanpham/sanpham.php`.
- Header/footer chrome: currently `index.php` includes `view/head.php`+`view/header.php` BEFORE switch and
  `view/footer.php` AFTER. Keep that in the front controller (Phase 04) — View::render renders only the
  inner content template. Preserves current page assembly.

## Related Code Files
- **Create:** `src/Core/Router.php`, `src/Core/Controller.php`, `src/Core/View.php`.
- **Read for parity:** `index.php`, `admin/controller/controller.php`.
- **Delete:** none this phase.

## Implementation Steps
1. `Core\View::render` — isolated include+extract; throw if template file missing (try/catch friendly).
2. `Core\Controller` — `protected function view(...)`, `protected function redirect(...)`.
3. `Core\Router` — `add($act,$handler)`, `setDefault($handler)`, `dispatch($act)` (instantiate controller,
   call method; catch missing route → default).
4. Lint. Scratch-test: register a dummy route → dispatch → assert echoes expected.

## Todo List
- [ ] `src/Core/View.php` (isolated scope)
- [ ] `src/Core/Controller.php`
- [ ] `src/Core/Router.php`
- [ ] Lint + scratch dispatch test
- [ ] Old app still runs

## Success Criteria
- Lint clean; scratch dispatch of a dummy controller works.
- `Core\View::render` does NOT leak `$data` keys into caller scope (verify in scratch).
- Existing site unaffected (index.php untouched).

## Risk Assessment
- **Variable-name mismatch (High/High):** if controller `$data` keys differ from what templates read,
  pages break. Mitigate: Phase 04 maps each route's `$data` keys to the exact names the template uses;
  cross-check against each `view/*.php`.
- **Header/footer double-render (Med/Med):** ensure View renders inner content only; chrome stays in front controller.

## Security Considerations
- View::render includes only known template paths built from a fixed route table — no user-controlled include path.

## Next Steps
- Phase 04 writes controllers + flips `index.php`.
