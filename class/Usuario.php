<?php

class Usuario
{

    private $dtcadastro;
    private $idusuario;
    private $deslogin;
    private $dessenha;

    public function __construct($deslogin = "", $dessenha = "")
    {
        $this->deslogin = $deslogin;
        $this->dessenha = $dessenha;
    }

    public function setIdusuario(int $idusuario)
    {
        $this->idusuario = $idusuario;
    }

    public function setDeslogin(string $deslogin)
    {
        $this->deslogin = $deslogin;
    }

    public function setDessenha(string $dessenha)
    {
        $this->dessenha = $dessenha;
    }

    public function setDtcadastro($dtcadastro)
    {
        $this->dtcadastro = $dtcadastro;
    }

    public function getIdusuario()
    {
        return $this->idusuario;
    }

    public function getDeslogin()
    {
        return $this->deslogin;
    }

    public function getDessenha()
    {
        return $this->dessenha;
    }

    public function getDtcadastro()
    {
        return $this->dtcadastro;
    }

    public function loadById(int $idusuario)
    {
        $sql = new SQL('sqlsrv');

        $result = $sql->select("SELECT * FROM tb_users WHERE idusuario = :ID", array(":ID" => $idusuario));

        if (count($result) > 0) {
            $this->setData($result[0]);
        }
    }

    public static function search($login)
    {

        $sql = new SQL('sqlsrv');

        return $sql->select("SELECT * FROM tb_users WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(':SEARCH' => "%" . $login . "%"));
    }


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


    public static function getList()
    {

        $sql = new SQL('sqlsrv');

        return $sql->select("SELECT * FROM tb_users ORDER BY deslogin");
    }

    public function setData($data)
    {

        $this->setDeslogin($data['deslogin']);
        $this->setDessenha($data['dessenha']);
        $this->setIdusuario($data['idusuario']);
        $this->setDtcadastro(new DateTime($data['dtcadastro']));
    }

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

    public function delete(){

        $sql = new SQL();

        $sql->querys("DELETE FROM tb_users WHERE idusuario = :ID", array(':ID'=>$this->getIdusuario()));

        $this->clearObject();

    }

    public function clearObject(){

        $this->setDeslogin('');
        $this->setDessenha('');
        $this->setIdusuario('0');
        $this->setDtcadastro(new DateTime());

    }

    public function update($login, $password, $id){

        $this->setDeslogin($login);
        $this->setDessenha($password);

        $sql = new SQL('mysql');

        $sql->querys("UPDATE tb_users SET deslogin = '$login', dessenha = '$password' WHERE idusuario = $id");
    }

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
