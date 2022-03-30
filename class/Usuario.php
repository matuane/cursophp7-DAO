<?php

//Calsse Usuário
class Usuario
{
    //Atributos
    private $dtcadastro;
    private $idusuario;
    private $deslogin;
    private $dessenha;


    //Construtor
    public function __construct($deslogin = "", $dessenha = "")
    {
        $this->deslogin = $deslogin;
        $this->dessenha = $dessenha;
    }

    //Atualiza o id do usuário
    public function setIdusuario(int $idusuario)
    {
        $this->idusuario = $idusuario;
    }

    //Atualiza o login do usuário
    public function setDeslogin(string $deslogin)
    {
        $this->deslogin = $deslogin;
    }

    //Atualiza a senha do usuário
    public function setDessenha(string $dessenha)
    {
        $this->dessenha = $dessenha;
    }

    //Atualiza a data de usuário do usuário
    public function setDtcadastro($dtcadastro)
    {
        $this->dtcadastro = $dtcadastro;
    }

    //Recupera o valor do id do usuário
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    //Recupera o valor do login do usuário
    public function getDeslogin()
    {
        return $this->deslogin;
    }

    //Recupera o valor da senha do usuário
    public function getDessenha()
    {
        return $this->dessenha;
    }

    //Recupera o valor da data de cadastro do usuário
    public function getDtcadastro()
    {
        return $this->dtcadastro;
    }

    //Carrega o usuario pelo ID
    public function loadById(int $idusuario)
    {
        $sql = new SQL('sqlsrv');

        $result = $sql->select("SELECT * FROM tb_users WHERE idusuario = :ID", array(":ID" => $idusuario));

        if (count($result) > 0) {
            $this->setData($result[0]);
        }
    }

    //Realiza um busca pelo login passado
    public static function search($login)
    {

        $sql = new SQL('sqlsrv');

        return $sql->select("SELECT * FROM tb_users WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(':SEARCH' => "%" . $login . "%"));
    }

    //Verifica se o usuário existe para realizar o login
    public function login($login, $password)
    {

        $sql = new SQL('sqlsrv');

        $result = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN  AND dessenha = :PASSWORD", array(":LOGIN" => $login, ":PASSWORD" => $password));

        if (count($result) > 0) {
            $this->setData($result[0]);
        } else {
            throw new Exception("Login e/ou senha inválidos");
        }
    }

    //Recupera um lista de usuários
    public static function getList()
    {

        $sql = new SQL('sqlsrv');

        return $sql->select("SELECT * FROM tb_users ORDER BY deslogin");
    }

    //Atualiza o valor dos atriubutos do objeto
    public function setData($data)
    {

        $this->setDeslogin($data['deslogin']);
        $this->setDessenha($data['dessenha']);
        $this->setIdusuario($data['idusuario']);
        $this->setDtcadastro(new DateTime($data['dtcadastro']));
    }

    //insere um novo usuáio no banco de dados
    public function insert()
    {
        $sql = new SQL('mysql');

        if($this->getDeslogin() && $this->getDessenha()){
            $results = $sql->insertProcedure('sp_usuarios_insert', $this->getDeslogin(), $this->getDessenha());
        } else {
            throw new Exception("Não possui usuario");
        }

        echo json_encode($results);
        if($results !== null && count($results) > 0){
            $this->setData($results[0]);
        }
    }

    //Deleta um usuário do banco de dados
    public function delete(){

        $sql = new SQL();

        $sql->querys("DELETE FROM tb_users WHERE idusuario = :ID", array(':ID'=>$this->getIdusuario()));

        $this->clearObject();

    }

    //Limpa os valores dos objetos do banco de dados
    public function clearObject(){

        $this->setDeslogin('');
        $this->setDessenha('');
        $this->setIdusuario('0');
        $this->setDtcadastro(new DateTime());

    }

    //Atualiza os valores do usuário selecionado
    public function update($login, $password, $id){

        $this->setDeslogin($login);
        $this->setDessenha($password);

        $sql = new SQL('mysql');

        $sql->querys("UPDATE tb_users SET deslogin = '$login', dessenha = '$password' WHERE idusuario = $id");
    }

    //Retorna o objeto em string
    public function __toString()
    {
        if ($this->getDtcadastro() !== null) {
            return json_encode(array(
                "idusuario" => $this->getIdusuario(),
                "deslogin" => $this->getDeslogin(),
                "dessenha" => $this->getDessenha(),
                "dtcadastro" => $this->getDtcadastro()->format("d-m-Y H:i:s")
            ));
        } else {
            throw new Exception("values null");
        }
    }
}
