<?php 
    class Database {

        private $host = "127.0.0.1";
        private $database_name = "wepayout_database";
        private $username = "wepayout";
        private $password = "12321";
        public $conn;

        public function __construct(){
            $this->conn = $this->getConnection();
        }

        public function getConnection(){

            $this->conn = null;

            try {

                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");

            } catch(PDOException $exception) {

                echo "Database could not be connected: " . $exception->getMessage();

            }

            return $this->conn;
        }

        public function select($query = "" , $params = []) {
            try {
                $stmt = $this->executeStatment($query, $params);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);               
                
                return $result;
            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            } 
        }

        public function create($query = "" , $params = []) {
            try {

                $stmt = $this->executeStatment($query, $params);        
                return $stmt;

            } catch(Exception $e) {
                throw New Exception($e->getMessage());
            } 
        }

        public function executeStatment($query = "" , $params = []) {
            try {
                $stmt = $this->conn->prepare($query);
     
                if($stmt === false) {
                    throw New Exception("Unable to do prepared statement: " . $query);
                }
     
                if($params) {
                    foreach($params as $key=>&$param) {
                        $stmt->bindParam(":".$key, $param);
                    }
                } 

                $stmt->execute();

                return $stmt;

            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            }   
        }
    }
?>