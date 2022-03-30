<?php 

    require_once("config.php");

    // retorna um usuário
    // $root = new Usuario();

    // $root->loadbyId(4);

    // if(!is_null($root)){
    //     echo $root;
    // };

    //retorna uma lista
    // $lista = Usuario::getList();

    // echo json_encode($lista);

    //faz uma busca por usuários
    // $users = Usuario::search('a  ');

    // echo json_encode($users);

    //busca um usuário
    $user = new Usuario();

    $user->login('leticia', 'joao');

    echo $user;

?>