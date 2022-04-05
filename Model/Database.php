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
                $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
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

        public function exec($query = "" , $params = []) {
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
                $id = $this->conn->lastInsertId();

                if($id == 0) {
                    return $stmt;
                } else {
                    return $id;
                }

            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            }   
        }

        public function beginTransaction() {
            return $this->conn->beginTransaction();
        }
        
        public function commit() {
            return $this->conn->commit();
        }

        public function rollBack() {
            return $this->conn->rollBack();
        }

    }
?>