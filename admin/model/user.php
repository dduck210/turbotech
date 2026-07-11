<?php
function loadall_user()
{
    $sql = "SELECT * FROM user ORDER BY id_user DESC";
    $listuser = pdo_query($sql);
    return $listuser;
}
function loadone_user(int $id_user)
{
    $sql = "SELECT * FROM user WHERE id_user = ?";
    $user = pdo_query_one($sql, $id_user);
    return $user;
}
function update_user(int $id_user, string $user_name, string $full_name, string $email_user, string $password, string $role)
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
function delete_user(int $id_user)
{
    $sql = "DELETE FROM user WHERE id_user = ?";
    pdo_execute($sql, $id_user);
}
function check_user_admin(string $user_name, string $password)
{
    $sql = "SELECT * FROM user WHERE ((user_name = ?) OR (email_user = ?)) AND password = ? AND role = '1'";
    $user = pdo_query_one($sql, $user_name, $user_name, $password);
    return $user;
}
