<?php

namespace App\Services;

/**
 * Ported from `Codemoi\Model\Payment` (legacy `src/Model/Payment.php`).
 * VietQR.io is NAPAS's official free bank-QR generator — no third-party
 * account needed. No transaction-matching/webhook exists (confirmed
 * absent in the legacy app too — the old standalone atm.php poller was
 * dead code, never wired up): payment confirmation stays manual, either
 * the customer's self-declared "Đã chuyển khoản" button or an admin
 * marking the bill paid.
 */
class VietQrService
{
    private const MEMO_PREFIX = ' ';

    public function transferMemo(string $billCode): string
    {
        return 'Thanh toan don'.self::MEMO_PREFIX.$billCode;
    }

    public function qrUrl(int $amount, string $billCode): string
    {
        $bankCode = rawurlencode((string) config('services.vietqr.bank_code'));
        $accountNo = rawurlencode((string) config('services.vietqr.account_no'));
        $accountName = rawurlencode((string) config('services.vietqr.account_name'));
        $memo = rawurlencode($this->transferMemo($billCode));

        return "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png"
            ."?amount={$amount}&addInfo={$memo}&accountName={$accountName}";
    }
}
