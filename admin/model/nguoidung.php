<?php
function loadall_user()
{
    $sql = "SELECT * FROM user ORDER BY id_user DESC";
    $listuser = pdo_query($sql);
    return $listuser;
}
function loadone_user($id_user)
{
    $sql = "SELECT * FROM user WHERE id_user = ?";
    $user = pdo_query_one($sql, $id_user);
    return $user;
}
function update_user($id_user, $user_name, $full_name, $email_user, $password, $role)
{
    $sql = "UPDATE user SET
    user_name = ?,
    full_name = ?,
    email_user = ?,
    password = ?,
    role = ?
    WHERE id_user = ?";
    pdo_execute($sql, $user_name, $full_name, $email_user, $password, $role, $id_user);
}
function delete_user($id_user) {
    $sql = "DELETE FROM user WHERE id_user = ?";
    pdo_execute($sql, $id_user);
}
function countusser()
{
    $sql = "SELECT count(*) FROM user";
    $a = pdo_query($sql);
    return $a;
}
function check_user_admin($user_name, $password)
{
    $sql = "SELECT * FROM user WHERE ((user_name = ?) OR (email_user = ?)) AND password = ? AND role = '1'";
    $user = pdo_query_one($sql, $user_name, $user_name, $password);
    return $user;
}