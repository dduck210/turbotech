<?php
    function question($name, $email, $phone, $contennt){
        $sql = "INSERT INTO `question`( `name`, `email`, `phone`, `contennt`) VALUES (?, ?, ?, ?)";
        pdo_execute($sql, $name, $email, $phone, $contennt);
    }
?>