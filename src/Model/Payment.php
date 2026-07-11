<?php

namespace Codemoi\Model;

use Codemoi\Core\Config;

/**
 * VietQR bank-transfer QR code generation for the checkout payment page.
 * Payment confirmation is currently either the customer's self-declared
 * "Đã Chuyển Khoản" button or an admin manually marking a bill paid — no
 * automated transaction matching (the old standalone `atm.php` poller,
 * which called an unverified third-party API, was removed as dead code;
 * it was never wired up to run).
 */
class Payment
{
    /** Mirrors old `$MEMO_PREFIX = " ";` (`model/function.php:2`). */
    private const MEMO_PREFIX = " ";

    /**
     * Transfer memo shown to the customer and encoded into the QR's
     * `addInfo`, kept in one place so the two can never drift apart.
     */
    public static function transferMemo(string $billCode): string
    {
        return 'Thanh toan don' . self::MEMO_PREFIX . $billCode;
    }

    /**
     * Build a VietQR.io image URL (NAPAS's official, free bank-QR
     * generator — no third-party account needed) that pre-fills the
     * transfer amount and memo, so the customer's banking app fills
     * in the amount/content automatically instead of them having to
     * type it. Bank account comes from Config (.env), never hardcoded.
     */
    public static function vietQrUrl(int $amount, string $billCode): string
    {
        $bankCode = rawurlencode(Config::bankCode());
        $accountNo = rawurlencode(Config::bankAccountNo());
        $accountName = rawurlencode(Config::bankAccountName());
        $memo = rawurlencode(self::transferMemo($billCode));

        return "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png"
            . "?amount={$amount}&addInfo={$memo}&accountName={$accountName}";
    }
}
