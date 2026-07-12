<?php

namespace Codemoi\Core;

/**
 * Central holder for connection settings.
 *
 * All settings (DB, SMTP, bank) are read from a local `.env` file
 * (gitignored — see `.env.example` for the required keys) so real
 * credentials never enter git history. `env()` falls back to an OS
 * environment variable, then to the given default — the DB_* constants
 * below are those defaults, matching the local XAMPP root/no-password
 * dev DB unchanged when no `.env` is present.
 */
class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'codemoi2';
    const DB_USER = 'root';
    const DB_PASS = '';
    const CHARSET = 'utf8';

    private static ?array $env = null;

    public static function dbHost(): string
    {
        return self::env('DB_HOST', self::DB_HOST);
    }

    public static function dbPort(): int
    {
        return (int) self::env('DB_PORT', '3306');
    }

    public static function dbName(): string
    {
        return self::env('DB_NAME', self::DB_NAME);
    }

    public static function dbUser(): string
    {
        return self::env('DB_USER', self::DB_USER);
    }

    public static function dbPass(): string
    {
        return self::env('DB_PASS', self::DB_PASS);
    }

    public static function charset(): string
    {
        return self::env('DB_CHARSET', self::CHARSET);
    }

    public static function smtpHost(): string
    {
        return self::env('SMTP_HOST', 'smtp.gmail.com');
    }

    public static function smtpUsername(): string
    {
        return self::env('SMTP_USERNAME', '');
    }

    public static function smtpPassword(): string
    {
        return self::env('SMTP_PASSWORD', '');
    }

    public static function smtpPort(): int
    {
        return (int) self::env('SMTP_PORT', '465');
    }

    public static function smtpFromEmail(): string
    {
        return self::env('SMTP_FROM_EMAIL', self::smtpUsername());
    }

    public static function smtpFromName(): string
    {
        return self::env('SMTP_FROM_NAME', 'Turbotech');
    }

    public static function bankCode(): string
    {
        return self::env('BANK_CODE', '');
    }

    public static function bankAccountNo(): string
    {
        return self::env('BANK_ACCOUNT_NO', '');
    }

    public static function bankAccountName(): string
    {
        return self::env('BANK_ACCOUNT_NAME', '');
    }

    private static function env(string $key, string $default): string
    {
        if (self::$env === null) {
            self::$env = self::loadEnvFile();
        }

        return self::$env[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Minimal `.env` parser (KEY=VALUE per line, `#` comments, no quoting
     * rules beyond trimming) — no Composer package available in this env
     * to pull in a real dotenv library, and the format needed here is tiny.
     */
    private static function loadEnvFile(): array
    {
        $path = dirname(__DIR__, 2) . '/.env';
        if (!is_file($path)) {
            return [];
        }

        $values = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $values[trim($key)] = trim($value);
        }

        return $values;
    }
}
