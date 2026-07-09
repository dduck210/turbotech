<?php 
function loadall_bill($iduser)
{
    $sql = "SELECT * FROM bill WHERE 1";
    $args = [];
    if ($iduser > 0) {
        $sql .= " AND id_user = ?";
        $args[] = $iduser;
    }
    $sql .= " ORDER BY id_bill DESC";
    $listbill = pdo_query($sql, ...$args);
    return $listbill;
}
function remove_bill($idbill)
{
    $sql = "DELETE FROM bill WHERE id_bill = ?";
    pdo_execute($sql, $idbill);
}
function loadone_bill($idbill) {
    $sql = "SELECT * FROM bill WHERE id_bill = ?";
    $one_bill = pdo_query_one($sql, $idbill);
    return $one_bill;
}

function load_cart_all($idbill) {
    $sql = "SELECT * FROM `cart` WHERE id_bill = ?";
    $ab = pdo_query($sql, $idbill);
    return $ab;
}
function update_bill($id_bill, $status, $status_pay){
    $sql = "UPDATE bill SET status = ?, status_pay = ? WHERE id_bill = ?";
    pdo_execute($sql, $status, $status_pay, $id_bill);
}
?>