<?php 
function add_pro($name_pro, $price, $discount, $img_pro, $short_des,$detail_des, $idcate) {
    $sql = "INSERT INTO product (name_pro, price, discount, img_pro, short_des, detail_des, idcate ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    pdo_execute($sql, $name_pro, $price, $discount, $img_pro, $short_des, $detail_des, $idcate);
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
function loadone_pro($id_pro) {
    $sql = "SELECT * FROM product WHERE id_pro = ?";
    $pro = pdo_query_one($sql, $id_pro);
    return $pro;
}
function update_pro($id_pro, $name_pro, $price, $discount, $short_des, $detail_des, $img_pro, $idcate) {
        if($img_pro != '') {
        $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        img_pro = ?,
        idcate = ?
        WHERE id_pro = ?";
        pdo_execute($sql, $name_pro, $price, $discount, $short_des, $detail_des, $img_pro, $idcate, $id_pro);
        }
    else {
        $sql = "UPDATE product SET
        name_pro = ?,
        price = ?,
        discount = ?,
        short_des = ?,
        detail_des = ?,
        idcate = ?
        WHERE id_pro = ?";
        pdo_execute($sql, $name_pro, $price, $discount, $short_des, $detail_des, $idcate, $id_pro);
    }
}
function remove_pro($id_pro) {
    $sql = "DELETE FROM product WHERE id_pro = ?";
    pdo_execute($sql, $id_pro);
}
function filter_by_cate($id_pro, $idcate)
{
    $sql = "SELECT * FROM product WHERE idcate = ? AND id_pro <> ?";
    $listpro = pdo_query($sql, $idcate, $id_pro);
    return $listpro;
}
// function loadall_pro() { 
//     $sql = "SELECT * FROM product ORDER BY id_pro DESC";
//     $listpro = pdo_query($sql);
//     return $listpro;
// }
?>