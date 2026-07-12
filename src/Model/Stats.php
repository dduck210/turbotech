<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Admin dashboard/statistics aggregate queries. Ported from
 * `admin/model/statistics.php`. Admin-only — no client-facing equivalent.
 */
class Stats
{
    /** Product count per category. Mirrors old `thonngke()`. */
    public static function byCategory(): array
    {
        $sql = "SELECT *,COUNT(product.name_pro) AS sluong FROM product INNER JOIN category ON product.idcate = category.id_cate GROUP BY category.name_cate;";
        return Database::query($sql);
    }

    /** Paid revenue in the last 24h. Mirrors old `ngay()`. */
    public static function today()
    {
        $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 DAY ";
        return Database::queryValue($sql);
    }

    /** Paid revenue in the last 7 days. Mirrors old `tuan()`. */
    public static function thisWeek()
    {
        $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 WEEK ";
        return Database::queryValue($sql);
    }

    /** Paid revenue in the last 30 days. Mirrors old `thang()`. */
    public static function thisMonth()
    {
        $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 MONTH ";
        return Database::queryValue($sql);
    }

    /** Paid revenue for calendar month `$month` (1-12), any year. Mirrors old `tungthang($a)`. */
    public static function forMonth(int $month)
    {
        $sql = "SELECT SUM(`total_amount`) as soluong FROM `bill` WHERE `status_pay` = '1' AND Month(order_date) = ? ";
        return Database::queryValue($sql, $month);
    }

    /** Mirrors old `get_revenue_by_date($from_date, $to_date)`. */
    public static function revenueByDate(string $fromDate, string $toDate): array
    {
        $sql = "SELECT DATE(order_date) as order_day, SUM(total_amount) as total_revenue, COUNT(id_bill) as total_orders FROM bill WHERE status_pay = '1'";
        $args = [];
        if ($fromDate !== "") {
            $sql .= " AND DATE(order_date) >= ?";
            $args[] = $fromDate;
        }
        if ($toDate !== "") {
            $sql .= " AND DATE(order_date) <= ?";
            $args[] = $toDate;
        }
        $sql .= " GROUP BY DATE(order_date) ORDER BY DATE(order_date) DESC";

        return Database::query($sql, ...$args);
    }

    /** Mirrors old `get_products_sold_by_date($from_date, $to_date, $sort_order)`. */
    public static function productsSoldByDate(string $fromDate, string $toDate, string $sortOrder = 'DESC'): array
    {
        $sql = "SELECT p.id_pro, p.name_pro, p.img_pro, SUM(c.quantity) as total_sold, SUM(c.total_amount) as total_revenue FROM cart c JOIN bill b ON c.id_bill = b.id_bill JOIN product p ON c.id_pro = p.id_pro WHERE b.status_pay = '1'";
        $args = [];
        if ($fromDate !== "") {
            $sql .= " AND DATE(b.order_date) >= ?";
            $args[] = $fromDate;
        }
        if ($toDate !== "") {
            $sql .= " AND DATE(b.order_date) <= ?";
            $args[] = $toDate;
        }

        $sortSql = ($sortOrder === 'ASC') ? 'ASC' : 'DESC';
        $sql .= " GROUP BY p.id_pro, p.name_pro, p.img_pro ORDER BY total_sold $sortSql";

        return Database::query($sql, ...$args);
    }

    /** Mirrors old `get_inventory()`. */
    public static function inventory(): array
    {
        $sql = "SELECT id_pro, name_pro, img_pro, stock, price FROM product ORDER BY stock ASC";
        return Database::query($sql);
    }
}
