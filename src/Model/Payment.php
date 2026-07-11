<?php

namespace Codemoi\Model;

use Codemoi\Core\Config;
use Codemoi\Core\Database;

/**
 * Bank-transfer payment poller helpers. Ported from `model/function.php`.
 * The old standalone `atm.php` poller (called an unverified third-party
 * API to auto-match transactions) was removed as dead code — it was never
 * wired up to run (no cron/scheduler referencing it). Payment confirmation
 * is currently either the customer's self-declared "Đã Chuyển Khoản"
 * button or an admin manually marking a bill paid.
 */
class Payment
{
    /** Mirrors old `$MEMO_PREFIX = " ";` (`model/function.php:2`). */
    private const MEMO_PREFIX = " ";

    /**
     * Transfer memo shown to the customer and encoded into the QR's
     * `addInfo`, kept in one place so the two can never drift apart.
     * Keeps the "{MEMO_PREFIX}{billCode}" shape parseOrderId() already
     * expects, so a real transaction-forwarding webhook could still
     * auto-match payments against this same memo format later.
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

    /**
     * Extract the order id embedded in a bank transfer memo/description.
     * Mirrors old `parse_order_id($des)`.
     *
     * @return int|null
     */
    public static function parseOrderId(string $des)
    {
        $re = '/' . self::MEMO_PREFIX . '\d+/im';
        preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
        if (count($matches) == 0) {
            return null;
        }
        $orderCode = $matches[0][0];
        $prefixLength = strlen(self::MEMO_PREFIX);

        return intval(substr($orderCode, $prefixLength));
    }

    /**
     * Fetch the response body of a GET request.
     * Mirrors old `curl_get($url)`.
     *
     * @return string|false
     */
    public static function curlGet(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Look up a recorded bank transaction by its transaction id.
     * Mirrors old `checkcode($tranId)`.
     *
     * @return array|false
     */
    public static function findByTran(string $tranId)
    {
        $sql = "SELECT * FROM history_bank WHERE tranid = ?";
        return Database::queryOne($sql, $tranId);
    }

    /**
     * Look up a bill by its bill code.
     * Mirrors old `checkbill($id)`.
     *
     * @return array|false
     */
    public static function findBillByCode(string $id)
    {
        $sql = "SELECT * FROM bill WHERE bill_code = ?";
        return Database::queryOne($sql, $id);
    }
}
