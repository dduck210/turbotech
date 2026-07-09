<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Bank-transfer payment poller helpers. Ported from `model/function.php`.
 * `atm.php` (the standalone poller script) still requires the old procedural
 * files directly; it is addressed in Phase 05.
 */
class Payment
{
    /** Mirrors old `$MEMO_PREFIX = " ";` (`model/function.php:2`). */
    private const MEMO_PREFIX = " ";

    /**
     * Extract the order id embedded in a bank transfer memo/description.
     * Mirrors old `parse_order_id($des)`.
     *
     * @return int|null
     */
    public static function parseOrderId($des)
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
    public static function curlGet($url)
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
    public static function findByTran($tranId)
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
    public static function findBillByCode($id)
    {
        $sql = "SELECT * FROM bill WHERE bill_code = ?";
        return Database::queryOne($sql, $id);
    }
}
