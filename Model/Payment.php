<?php
    include_once "Database.php";

    class Payment {
        private $conn;

        public function __construct() {
            $this->conn = new Database();
        }

        public function list($id=null) {
            if($id === null) {
                return $this->conn->select("SELECT * FROM tab_pagamento");
            } else {
                $param = [
                    'id'=> $id
                ];
                return $this->conn->select("SELECT * FROM tab_pagamento WHERE id = :id", $param);
            }
        }

        public function create($params) {
            $paymentId = $this->conn->create("INSERT INTO tab_pagamento (
                    invoice, 
                    nomeDoBeneficiario, 
                    codigoDoBancoDoBeneficiario, 
                    numeroDaAgenciaDoBeneficiario, 
                    numeroDaContaDoBeneficiario, 
                    valorDoPagamento
                ) VALUES (
                    :invoice, 
                    :nomeDoBeneficiario, 
                    :codigoDoBancoDoBeneficiario, 
                    :numeroDaAgenciaDoBeneficiario, 
                    :numeroDaContaDoBeneficiario, 
                    :valorDoPagamento
                )", $params);

            $fila = new Queue();
            $fila->addQueue([
                "id_pagamento"=> $paymentId,
                "invoice_pagamento" => $params->invoice
            ]);
        }


    }