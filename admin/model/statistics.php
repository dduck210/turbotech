<?php
function thonngke()
{
    $sql = "SELECT *,COUNT(product.name_pro) AS sluong FROM product INNER JOIN category ON product.idcate = category.id_cate GROUP BY category.name_cate;";
    $a = pdo_query($sql);
    return $a;
}

function ngay()
{
    $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 DAY ";
    $liststatis = pdo_query_value($sql);
    return $liststatis;
}
function tuan()
{
    $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 WEEK ";
    $liststatis = pdo_query_value($sql);
    return $liststatis;
}
function thang()
{
    $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 MONTH ";
    $liststatis = pdo_query_value($sql);
    return $liststatis;
}
function tungthang(int $a)
{
    $sql = "SELECT SUM(`total_amount`) as soluong FROM `bill` WHERE `status_pay` = '1' AND Month(order_date) = ? ";
    $liststatis = pdo_query_value($sql, $a);
    return $liststatis;
}

function get_revenue_by_date(string $from_date, string $to_date)
{
    $sql = "SELECT DATE(order_date) as order_day, SUM(total_amount) as total_revenue, COUNT(id_bill) as total_orders FROM bill WHERE status_pay = '1'";
    $args = [];
    if ($from_date !== "") {
        $sql .= " AND DATE(order_date) >= ?";
        $args[] = $from_date;
    }
    if ($to_date !== "") {
        $sql .= " AND DATE(order_date) <= ?";
        $args[] = $to_date;
    }
    $sql .= " GROUP BY DATE(order_date) ORDER BY DATE(order_date) DESC";
    return pdo_query($sql, ...$args);
}

function get_products_sold_by_date(string $from_date, string $to_date, string $sort_order = 'DESC')
{
    $sql = "SELECT p.id_pro, p.name_pro, p.img_pro, SUM(c.quantity) as total_sold, SUM(c.total_amount) as total_revenue FROM cart c JOIN bill b ON c.id_bill = b.id_bill JOIN product p ON c.id_pro = p.id_pro WHERE b.status_pay = '1'";
    $args = [];
    if ($from_date !== "") {
        $sql .= " AND DATE(b.order_date) >= ?";
        $args[] = $from_date;
    }
    if ($to_date !== "") {
        $sql .= " AND DATE(b.order_date) <= ?";
        $args[] = $to_date;
    }

    $sort_sql = ($sort_order === 'ASC') ? 'ASC' : 'DESC';
    $sql .= " GROUP BY p.id_pro, p.name_pro, p.img_pro ORDER BY total_sold $sort_sql";
    return pdo_query($sql, ...$args);
}

function get_inventory()
{
    $sql = "SELECT id_pro, name_pro, img_pro, stock, price FROM product ORDER BY stock ASC";
    return pdo_query($sql);
}
