<?php

class Usuario
{

    private $dtcadastro;
    private $idusuario;
    private $deslogin;
    private $dessenha;

    // public function __construct($idusuario = '0', $deslogin = " ", $dessenha = " ", $dtcadastro = new DateTime("now"))
    // {
    //     $this->deslogin = $deslogin;
    //     $this->dessenha = $dessenha;
    //     $this->idusuario = $idusuario;
    //     $this->dtcadastro = $dtcadastro;
    // }

    public function setIdusuario(int $idusuario)
    {
        $this->$idusuario = $idusuario;
    }

    public function setDeslogin(string $deslogin)
    {
        $this->$deslogin = $deslogin;
    }

    public function setDessenha(string $dessenha)
    {
        $this->$dessenha = $dessenha;
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
        $sql = new SQL();

        $result = $sql->select("SELECT * FROM tb_users WHERE idusuario = :ID", array(":ID" => $idusuario));

        if (count($result) > 0) {
            $row  = $result[0];
            var_dump($row);

            $this->setDeslogin($row['deslogin']);
            $this->setDessenha($row['dessenha']);
            $this->setIdusuario($row['idusuario']);
            $this->setDtcadastro(new DateTime($row['dtcadastro']));
        }
    }

    public static function search($login){

        $sql = new SQL();

        return $sql->select("SELECT * FROM tb_users WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(':SEARCH' => "%" . $login . "%"));

    }


    public function login($login, $password){

        $sql = new SQL();

        $result = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN  AND dessenha = :PASSWORD", array(":LOGIN" => $login, ":PASSWORD" => $password));

        if (count($result) > 0) {
            $row  = $result[0];
            var_dump($row);

            $this->setDeslogin($row['deslogin']);
            $this->setDessenha($row['dessenha']);
            $this->setIdusuario($row['idusuario']);
            $this->setDtcadastro(new DateTime($row['dtcadastro']));
        } else {
            throw new Exception("Login e/ou senha inválidos");
        }

    }


    public static function getList(){

        $sql = new SQL();

        return $sql->select("SELECT * FROM tb_users ORDER BY deslogin");
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
