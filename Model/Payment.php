<?php
    include_once "Database.php";

    class Payment {
        private $conn;

        public function __construct() {
            $this->conn = new Database();
        }

        public function list() {
            return $this->conn->select("SELECT * FROM tab_pagamento");
        }

        public function create($params) {
            $this->conn->create("INSERT INTO tab_pagamento (
                    invoice, 
                    nomeDoBeneficiario, 
                    codigoDoBancoDoBeneficiario, 
                    numeroDaAgenciaDoBeneficiario, 
                    numeroDaContaDoBeneficiario, 
                    valorDoPagamento, 
                    status
                ) VALUES (
                    :invoice, 
                    :nomeDoBeneficiario, 
                    :codigoDoBancoDoBeneficiario, 
                    :numeroDaAgenciaDoBeneficiario, 
                    :numeroDaContaDoBeneficiario, 
                    :valorDoPagamento, 
                    :status
                )", $params);
        }
    }