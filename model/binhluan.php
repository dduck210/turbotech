<?php 
function insert_comment($content, $id_user, $user_name, $full_name, $idpro, $comment_date)
{
    $sql = "INSERT INTO comment (content, id_user, user_name, full_name, id_pro, comment_date) VALUES (?, ?, ?, ?, ?, ?)";
    pdo_execute($sql, $content, $id_user, $user_name, $full_name, $idpro, $comment_date);
}
function loadall_comment($idpro)
{
    $sql = "SELECT * FROM comment WHERE id_pro = ? ORDER BY id_cmt desc limit 0,8";
    $listcmt = pdo_query($sql, $idpro);
    return $listcmt;
}
?>