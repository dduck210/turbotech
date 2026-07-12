---
title: "Storefront Product Search & Filter Improvements"
description: "Secure + unify product search (name, brand/category, price) into one combined GET filter."
status: done
priority: P1
effort: 3h
branch: feat/search-filter-improvements
tags: [search, filter, security, sql-injection, storefront]
created: 2026-07-09
---

# Storefront Product Search & Filter Improvements

Small, contained enhancement to the Turbotech (laptops/PCs) storefront listing route
(`act=product`). Fix SQL injection + unify keyword / brand-category / price into ONE
combined, shareable GET filter. Brand == category (single existing `category` table —
NO schema migration). Pagination is out of scope.

## Confirmed Decisions
- **GET, not POST** for the listing filter. Sidebar category links are already GET `<a>`
  tags; GET makes keyword+category+price combine cleanly and be bookmarkable/shareable.
- **Header quick-search** stays name-only but switches POST→GET (one-word change) so it
  flows through the same pipeline.
- Reuse existing `category` table as-is for brand/category (one concept).
- No admin-side changes (no new product fields).

## Phases
| # | Phase | Status | File |
|---|-------|--------|------|
| 1 | Secure + unify search/filter (model + controller + views) | pending | [phase-01-secure-unify-search-filter.md](phase-01-secure-unify-search-filter.md) |

## Key Dependencies
- `pdo_query($sql, ...$args)` already supports bound `?` params (pdo.php:43-58) — no helper change.
- All edits are within one route; single phase, sequential, no parallelism needed.

## Success Criteria (measurable)
- No string concatenation of user input in `loadall_pro()`; all filters bound as `?` params.
- Keyword + category + min price + max price applied together return correctly filtered rows.
- Filter state survives in the URL (shareable) and repopulates the form.
- Existing grid/list toggle, empty state, sidebar, add-to-cart, breadcrumbs unchanged.
