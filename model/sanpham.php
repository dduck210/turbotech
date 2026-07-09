<?php 
// show toàn bộ sản phẩm trang home
function loadall_pro_home()
{
    $sql = "SELECT * FROM product WHERE 1 ORDER BY id_pro desc limit 0,20";
    $listpro= pdo_query($sql);
    return $listpro;
}
function loadone_pro($id_pro)
{
    $sql = "SELECT * FROM product WHERE id_pro = ?";
    $onepro = pdo_query_one($sql, $id_pro);
    return $onepro;
}


// show toàn bộ sản phẩm theo keyword được tìm kiếm, theo danh mục/thương hiệu và khoảng giá
function loadall_pro($kyw = "", $idcate = 0, $min = 0, $max = 0)
{
    $sql = "SELECT * FROM product WHERE 1";
    $args = [];
    $kyw = trim($kyw);
    if ($kyw !== '') {
        $esc = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $kyw);
        $sql .= " AND name_pro LIKE ?";
        $args[] = '%' . $esc . '%';
    }
    if ($idcate > 0) {
        $sql .= " AND idcate = ?";
        $args[] = $idcate;
    }
    if ($min > 0) {
        $sql .= " AND price >= ?";
        $args[] = $min;
    }
    if ($max > 0) {
        $sql .= " AND price <= ?";
        $args[] = $max;
    }
    $sql .= " ORDER BY id_pro desc";
    $listpro = pdo_query($sql, ...$args);
    return $listpro;
}
// show sản phẩm bán chạy
function loadall_pro_best()
{
    $sql = "SELECT 
                a.id_pro,
                a.name_pro,
                a.price,
                a.img_pro,
                COUNT(*) as total_sale
            FROM product a
            INNER JOIN cart b ON a.id_pro = b.id_pro
            INNER JOIN bill c ON b.id_bill = c.id_bill
            WHERE c.status_pay = 1
            GROUP BY 
                a.id_pro,
                a.name_pro,
                a.price,
                a.img_pro
            ORDER BY total_sale DESC";

    $listpro = pdo_query($sql);
    return $listpro;
}
// show 8 sản phẩm nổi bật được xem nhiều nhất
function loadall_pro_noibat()
{
    $sql = "SELECT * FROM product WHERE 1 ORDER BY view desc limit 0,8";
    $listpro= pdo_query($sql);
    return $listpro;
}
function similar_pro($id_pro, $idcate) {
$sql = "SELECT * FROM product WHERE idcate = ? AND id_pro <> ?";
$listpro = pdo_query($sql, $idcate, $id_pro);
return $listpro;
}


function updateview($a)
{
    $sql = "UPDATE product SET view = view+1 WHERE id_pro = ?";
    $listpro = pdo_execute($sql, $a);
    return $listpro;
}
