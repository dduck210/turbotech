-- Drops 6 legacy Laravel-shaped tables confirmed to have zero references
-- anywhere in the application code (verified via project-wide grep,
-- Phase 06 of plans/260712-0319-codebase-professionalization/plan.md).
--
-- Row counts at time of writing: users=0, migrations=4, failed_jobs=0,
-- password_resets=0, personal_access_tokens=0, history_bank=9.
--
-- REQUIRED before running this: take a full backup of these 6 tables.
--   "C:\xampp\mysql\bin\mysqldump.exe" -u root codemoi2 ^
--     users migrations failed_jobs password_resets personal_access_tokens history_bank ^
--     > scripts\dead-tables_backup_YYYYMMDD.sql
-- (the /scripts/*_backup_*.sql pattern is already gitignored — do not commit it)
--
-- Only run this file after explicit owner sign-off.

DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `password_resets`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `history_bank`;
