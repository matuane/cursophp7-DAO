<?php 

    require_once("config.php");

    $sql = new SQL();

    $users = $sql->select("SELECT * FROM tb_user");

    echo json_encode($users);

?>