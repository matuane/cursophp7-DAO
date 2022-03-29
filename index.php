<?php 

    require_once("config.php");

    $root = new Usuario();

    $root->loadbyId(4);

    if(!is_null($root)){
        echo $root;
    };

?>