<?php 

    //Classe SQL que extende os atributos e métodos da classe PDO
    class SQL extends PDO {

        private $conn;
        
        private $database;

        //Função construtora que conecta no banco de dados selecinado
        public function __construct($database = 'mysql'){
            if($database === 'sqlsrv'){
                $this->conn = new PDO("$database:Database=db_php7; server=PRY-N-I10055\SQLEXPRESS", "sa", "M@teus2432");
            } else if ($database === 'mysql'){
                $this->conn = new PDO("$database:host=localhost; dbname=db_php7", "root", "");
            }
            $this->database = $database;
        }

        //função privada que acessa os valores presentes no array de parametros para repassar esse valor para a função setParam
        private function setParams($statment, $parameters = array()){
            foreach($parameters as $key => $value){
                $this->setParam($statment, $key, $value);
            }
        }

        //Função para realizar um bind em cada statment recebido pela função setParams
        private function setParam($statment, $key, $value){
            $statment->bindParam($key, $value);
        }

        //Função que conversa com o banco de dados, montando a query, chamando a função set params e executando a query
        public function querys($rawQuery, $params = array()){
            $stmt = $this->conn->prepare($rawQuery);

            $this->setParams($stmt, $params);

            $stmt->execute();

            return $stmt;
        }

        //função que recebe um rawQuery e trata o retorno do banco
        public function select($rawQuery, $params = array()){

            $stmt = $this->querys($rawQuery, $params);

            return  $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        //função para executar a procedure dependendo do naco selecionado
        public function insertProcedure($procedure, $login, $password){

            switch($this->database){
                case $this->database == 'sqlsrv':
                    return $this->select("EXECUTE $procedure':LOGIN, :PASSWORD'", array(':LOGIN' => $login, ':PASSWORD' => $password));
                    break;
                case $this->database == 'mysql':
                    return $this->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(':LOGIN' => $login, ':PASSWORD' => $password));
                    break;
            }

        }

    }

?>