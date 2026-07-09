<?php
function register($user_name, $full_name, $email_user, $password)
{
    $sql = "INSERT INTO user (user_name, full_name, email_user, password) VALUES (?, ?, ?, ?)";
    pdo_execute($sql, $user_name, $full_name, $email_user, $password);
}
function check_user($user_name, $password)
{
    $sql = "SELECT * FROM user WHERE ((user_name = ?) OR (email_user = ?)) AND password = ?";
    $user = pdo_query_one($sql, $user_name, $user_name, $password);
    return $user;
}

function check_pass($name, $email)
{
    $sql = "SELECT * FROM user WHERE user_name = ? AND email_user = ?";
    $pass = pdo_query_one($sql, $name, $email);
    return $pass;
}

function getUserEmail($email)
{
    $sql = "SELECT * FROM user where email_user = ?";
    $result = pdo_query_one($sql, $email);
    if ($result) {
        return $result;
    } else {
        echo "<h4 style='color: red; text-align: center; margin-top: 10px;'>Email không tồn tại!</h4>";
    }
}
function updatePass($user_name, $password)
{
    $sql = "UPDATE `user` SET password = ? WHERE user_name = ?";
    pdo_execute($sql, $password, $user_name);
}
function forgetPass($password, $email)
{
    $sql = "UPDATE user SET password = ? WHERE email_user = ?";
    pdo_execute($sql, $password, $email);
}
function update_user($id_user, $img_user, $full_name, $sex, $email_user, $address, $phone_user)
{
    if ($img_user != '') {
        $sql = "UPDATE user SET
    img_user = ?,
    full_name = ?,
    sex = ?,
    email_user = ?,
    address = ?,
    phone_user = ?
      WHERE id_user = ?";
        pdo_execute($sql, $img_user, $full_name, $sex, $email_user, $address, $phone_user, $id_user);
    } else {
        $sql = "UPDATE user SET
        full_name = ?,
        sex = ?,
        email_user = ?,
        address = ?,
        phone_user = ?
          WHERE id_user = ?";
        pdo_execute($sql, $full_name, $sex, $email_user, $address, $phone_user, $id_user);
    }
}
?>