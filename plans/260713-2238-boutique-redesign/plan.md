# Boutique Redesign — Overview

Full visual + layout overhaul of both client and admin, pivoting away from
the "Dark Luxury Tech" theme to a warm boutique aesthetic (cream/beige,
serif headings, bronze/gold accent, thin borders, minimal shadow, small
radius). User explicitly wants genuine layout restructuring, not a token
recolor — see [[design-system.md]] for the full spec every phase must follow.

## Phases

| # | Phase | Owner | Status |
|---|-------|-------|--------|
| 1 | [Design tokens + logo](phase-01-design-tokens.md) | me (direct) | done |
| 2 | [Client redesign](phase-02-client-redesign.md) | agent A (`view/`, `public/view/`) | done |
| 3 | [Admin redesign](phase-03-admin-redesign.md) | agent B (`admin/view/`) | done |
| 4 | Final rebuild, lint, route sweep, commit/push | me (direct) | done |

## Key constraints

- No browser/screenshot tool in this environment — all verification is
  `php -l` + curl route sweeps + reasoning. User must confirm visually.
- File ownership is split by directory so agents 2 and 3 never touch the
  same file: agent A owns `view/*.php` + `public/view/comment-form.php`;
  agent B owns `admin/view/*.php`. Neither touches the token CSS files
  (`src/css/*.css`) — those are already finalized in phase 1.
- Both agents rebuild nothing themselves; the final `tailwindcss.exe`
  compile + verification happens once, in phase 4, after both finish.
