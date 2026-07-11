# Phase 01 — Provision external MySQL + migrate schema/data

**Context:** [plan.md](plan.md) · source dump `Turbotech.sql` (repo root) · local DB `codemoi2`

## Overview
- **Priority:** P1 (blocks everything — no phase works without a reachable cloud DB)
- **Status:** pending
- **Description:** Stand up a managed MySQL/MariaDB reachable from Vercel and load the current
  schema+data into it.

## Key insights
- Two independent code paths connect to MySQL today: `src/Core/Database.php:22` (DSN from
  `Config` constants) and `admin/model/pdo.php:8` (hardcoded `127.0.0.1;dbname=codemoi2`). Both
  target the same local DB `codemoi2`. The new cloud DB must serve both.
- `Turbotech.sql` is the schema/data source of truth in-repo; local running DB is `codemoi2`.
- `Database.php` opens **one PDO connection per call** (per-request lifetime, verified
  `Database.php:20-28`). Under serverless this means many short connections — connection limit
  matters when picking a host.

## Requirements
- Functional: cloud DB contains all tables + rows from `codemoi2`; reachable over public TLS from
  Vercel functions; MySQL-protocol compatible (mysqli/PDO_mysql).
- Non-functional: connection limit high enough for serverless burst; free/cheap tier acceptable
  for this project size.

## Host options (recommend one default)
| Option | Pros | Cons | Verdict |
|--------|------|------|---------|
| **Railway MySQL** | Real MySQL, simple, generous conns, public URL | paid after trial | **DEFAULT** — closest to XAMPP MySQL, no SQL rewrites |
| PlanetScale | Serverless scaling, pooling | MySQL-compatible (Vitess), no FK enforcement, may need schema tweaks | fallback if scale needed |
| Aiven MySQL | Real MySQL, free tier | smaller free conn limit | fallback |
| TiDB Cloud | MySQL-compatible, serverless free tier | subtle compat differences | fallback |

Recommend **Railway MySQL**: real MySQL 8, least risk of SQL incompatibility with legacy queries.

## Data flow
`Turbotech.sql` (mysqldump) ──`mysql` client / import──> cloud MySQL `codemoi2` schema+rows.

## Related code files
- Read only: `Turbotech.sql`, `admin/model/pdo.php`, `src/Core/Database.php`
- No code edits in this phase.

## Implementation steps
1. **[USER ACTION]** User creates managed MySQL (Railway recommended), returns: host, port,
   db name, user, password, and whether TLS/CA is required.
2. Agent verifies `Turbotech.sql` is current vs. local `codemoi2` (diff table list via
   `mysqldump --no-data` locally vs. the .sql). If drift, re-dump local DB.
3. Agent imports dump into cloud DB: `mysql -h <host> -P <port> -u <user> -p<pass> <db> < Turbotech.sql`.
4. Verify row counts per key table (`product`, `category`, `user`, `bill`, `coupon`, `comment`)
   match local.
5. Record the connection params for Phase 02/05 env vars (do NOT commit them).

## Todo
- [ ] User provisions cloud MySQL, returns creds
- [ ] Confirm `Turbotech.sql` current (re-dump if drift)
- [ ] Import dump to cloud DB
- [ ] Verify row counts match local
- [ ] Store creds for env config (uncommitted)

## Success criteria
- `mysql -h <cloud-host> ... -e "SELECT COUNT(*) FROM product"` returns same count as local.

## Risk assessment
- **MED/HIGH — SQL incompatibility** if a non-true-MySQL host chosen (PlanetScale FK/Vitess).
  Mitigation: default to Railway real MySQL.
- **MED — dump drift** (local DB ahead of `Turbotech.sql`). Mitigation: step 2 diff + re-dump.
- **LOW — TLS/CA requirement** breaks plain PDO DSN. Mitigation: capture CA need now; Phase 02
  DSN adds `sslmode`/`PDO::MYSQL_ATTR_SSL_CA` only if host requires it.

## Security considerations
- Cloud creds are real secrets (unlike current localhost). Never commit; go into `.env` (gitignored)
  locally and Vercel dashboard env in Phase 05.

## Next steps
- Blocks Phase 02 (needs creds to wire env DSN).
