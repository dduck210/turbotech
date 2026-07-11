<?php
function loadall_bill(int $iduser, int $status = -1, string $keyword = "", string $from_date = "", string $to_date = "")
{
    $sql = "SELECT * FROM bill WHERE 1";
    $args = [];
    if ($iduser > 0) {
        $sql .= " AND id_user = ?";
        $args[] = $iduser;
    }
    if ($status > -1) {
        $sql .= " AND status = ?";
        $args[] = $status;
    }
    if ($keyword != "") {
        $sql .= " AND (full_name LIKE ? OR phone LIKE ?)";
        $args[] = "%" . $keyword . "%";
        $args[] = "%" . $keyword . "%";
    }
    if ($from_date != "") {
        $sql .= " AND DATE(order_date) >= ?";
        $args[] = $from_date;
    }
    if ($to_date != "") {
        $sql .= " AND DATE(order_date) <= ?";
        $args[] = $to_date;
    }
    $sql .= " ORDER BY id_bill DESC";
    $listbill = pdo_query($sql, ...$args);
    return $listbill;
}
function remove_bill(int $idbill)
{
    $sql = "DELETE FROM bill WHERE id_bill = ?";
    pdo_execute($sql, $idbill);
}
function loadone_bill(int $idbill)
{
    $sql = "SELECT * FROM bill WHERE id_bill = ?";
    $one_bill = pdo_query_one($sql, $idbill);
    return $one_bill;
}

function load_cart_all(int $idbill)
{
    $sql = "SELECT * FROM `cart` WHERE id_bill = ?";
    $ab = pdo_query($sql, $idbill);
    return $ab;
}
function update_bill(int $id_bill, string $status, string $status_pay)
{
    $sql = "UPDATE bill SET status = ?, status_pay = ? WHERE id_bill = ?";
    pdo_execute($sql, $status, $status_pay, $id_bill);
}
