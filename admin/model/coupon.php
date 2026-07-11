<?php
function insert_coupon($code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status)
{
    $sql = "INSERT INTO coupons(code, discount_type, discount_value, max_discount, min_order_value, product_id, start_date, end_date, usage_limit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    pdo_execute($sql, $code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status);
}

function loadall_coupon()
{
    $sql = "SELECT * FROM coupons ORDER BY id_coupon DESC";
    return pdo_query($sql);
}

function delete_coupon($id_coupon)
{
    $sql = "DELETE FROM coupons WHERE id_coupon = ?";
    pdo_execute($sql, $id_coupon);
}

function loadone_coupon($id_coupon)
{
    $sql = "SELECT * FROM coupons WHERE id_coupon = ?";
    return pdo_query_one($sql, $id_coupon);
}

function update_coupon($id_coupon, $code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status)
{
    $sql = "UPDATE coupons SET code = ?, discount_type = ?, discount_value = ?, max_discount = ?, min_order_value = ?, product_id = ?, start_date = ?, end_date = ?, usage_limit = ?, status = ? WHERE id_coupon = ?";
    pdo_execute($sql, $code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status, $id_coupon);
}
?>
