<?php 

    class SQL extends PDO {

        private $conn;
        
        private $database;

        public function __construct($database = 'mysql'){
            if($database === 'sqlsrv'){
                $this->conn = new PDO("$database:Database=db_php7; server=PRY-N-I10055\SQLEXPRESS", "sa", "M@teus2432");
            } else if ($database === 'mysql'){
                $this->conn = new PDO("$database:host=localhost; dbname=db_php7", "root", "");
            }
            $this->database = $database;
        }

        private function setParams($statment, $parameters = array()){
            foreach($parameters as $key => $value){
                $this->setParam($statment, $key, $value);
            }
        }

        private function setParam($statment, $key, $value){
            $statment->bindParam($key, $value);
        }

        public function querys($rawQuery, $params = array()){
            $stmt = $this->conn->prepare($rawQuery);

            $this->setParams($stmt, $params);

            $stmt->execute();

            return $stmt;
        }

        public function select($rawQuery, $params = array()){

            $stmt = $this->querys($rawQuery, $params);

            return  $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

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