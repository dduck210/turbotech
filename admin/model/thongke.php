<?php
function loadall_statis()
{
    $sql = "SELECT c.id_cate as idcate, c.name_cate as namecate, count(p.id_pro) as pro_quantity, min(p.price) as min_price, max(p.price) as max_price, avg(p.price) as avg_price";
    $sql .= " FROM product p LEFT JOIN category c ON c.id_cate = p.idcate";
    $sql .= " GROUP BY c.id_cate ORDER BY c.id_cate DESC";
    $liststatis = pdo_query($sql);
    return $liststatis;
}

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
function nam()
{
    $sql = "SELECT SUM(`total_amount`) FROM `bill` WHERE `status_pay` = '1' AND `order_date` >= NOW() - INTERVAL 1 YEAR ";
    $liststatis = pdo_query_value($sql);
    return $liststatis;
}

function tungthang($a)
{
    $sql = "SELECT SUM(`total_amount`) as soluong FROM `bill` WHERE `status_pay` = '1' AND Month(order_date) = ? ";
    $liststatis = pdo_query_value($sql, $a);
    return $liststatis;
}
