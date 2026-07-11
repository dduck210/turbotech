<?php
function add_pro(string $name_pro, string $price, string $discount, string $img_pro, string $short_des, string $detail_des, int $idcate, int $stock = 0, ?string $stock_message = null)
{
    $sql = "INSERT INTO product (name_pro, price, discount, img_pro, short_des, detail_des, idcate, stock, stock_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    pdo_execute($sql, $name_pro, $price, $discount, $img_pro, $short_des, $detail_des, $idcate, $stock, $stock_message);
}
function loadall_pro($idcate = 0)
{
    $sql = "SELECT * FROM product WHERE 1";
    $args = [];
    if ($idcate > 0) {
        $sql .= " AND idcate = ?";
        $args[] = $idcate;
    }
    $sql .= " ORDER BY id_pro DESC";
    $listpro = pdo_query($sql, ...$args);
    return $listpro;
}
function loadone_pro(int $id_pro)
{
    $sql = "SELECT * FROM product WHERE id_pro = ?";
    $pro = pdo_query_one($sql, $id_pro);
    return $pro;
}
function update_pro(int $id_pro, string $name_pro, string $price, string $discount, string $short_des, string $detail_des, string $img_pro, int $idcate, int $stock = 0, ?string $stock_message = null)
{
    if ($img_pro != '') {
        $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        img_pro = ?,
        idcate = ?,
        stock = ?,
        stock_message = ?
        WHERE id_pro = ?";
        pdo_execute($sql, $name_pro, $price, $discount, $short_des, $detail_des, $img_pro, $idcate, $stock, $stock_message, $id_pro);
    } else {
        $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        idcate = ?,
        stock = ?,
        stock_message = ?
        WHERE id_pro = ?";
        pdo_execute($sql, $name_pro, $price, $discount, $short_des, $detail_des, $idcate, $stock, $stock_message, $id_pro);
    }
}
function remove_pro(int $id_pro)
{
    $sql = "DELETE FROM product WHERE id_pro = ?";
    pdo_execute($sql, $id_pro);
}
