<?php 

    //função responsável por importar as classes presentes na pasta class
    spl_autoload_register(function($class_name){

        $filename = "class" . DIRECTORY_SEPARATOR .  $class_name . '.php';

        if(file_exists(($filename))){
            require_once($filename);
        }
    })

?>