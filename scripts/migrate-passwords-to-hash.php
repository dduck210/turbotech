<?php

/**
 * One-time migration: hash every plaintext password currently stored in
 * the `user` table with password_hash() (PASSWORD_DEFAULT / bcrypt).
 *
 * Idempotent — a row already holding a real hash (password_get_info()
 * resolves its algo) is left untouched, so this can be re-run safely.
 *
 * Run from the command line: php scripts/migrate-passwords-to-hash.php
 * Take a backup first: mysqldump codemoi2 user > user_backup.sql
 */

require __DIR__.'/../vendor/autoload.php';

use Codemoi\Core\Config;

$dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset='.Config::CHARSET;
$pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $pdo->query('SELECT id_user, password FROM user')->fetchAll(PDO::FETCH_ASSOC);

$update = $pdo->prepare('UPDATE user SET password = ? WHERE id_user = ?');

$migrated = 0;
$skipped = 0;

foreach ($rows as $row) {
    $info = password_get_info($row['password']);

    if ($info['algo'] !== null && $info['algo'] !== 0) {
        // Already a real hash (e.g. a re-run) — leave it alone.
        $skipped++;

        continue;
    }

    $hash = password_hash($row['password'], PASSWORD_DEFAULT);
    $update->execute([$hash, $row['id_user']]);
    $migrated++;
}

echo "Migrated: {$migrated}\n";
echo "Already hashed (skipped): {$skipped}\n";
echo 'Total rows: '.count($rows)."\n";
