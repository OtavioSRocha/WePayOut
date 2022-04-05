<?php
    include_once PROJECT_ROOT_PATH . "/Model/Banks/BancoDoBrasil.php";
    include_once PROJECT_ROOT_PATH . "/Model/Banks/Itau.php";
    
    class PersistPayment {
        private $id_pagamento;
        private $conn;
        private $bank;

        public function __construct($id_pagamento) {
            $this->conn = new Database();
            $this->id_pagamento = $id_pagamento;
            $this->bank = $id_pagamento % 2 == 0 ? new Itau() : new BancoDoBrasil();
            
        }

        public function changePaymentStatus() {
            try {
                $this->conn->beginTransaction();
                $this->conn->exec("UPDATE tab_pagamento SET status = :status WHERE id = :id", ["status"=>"PROCESSANDO","id"=>$this->id_pagamento]);
                $this->conn->commit();
            } catch(Error $e) {
                $this->conn->rollBack();
                throw New Exception($e->getMessage());
            }
        }

        public function setPaymentAsComplete($paymentStatus) {
            try {
                $this->conn->beginTransaction();
                $this->conn->exec("
                    UPDATE tab_pagamento SET 
                        status = :status, 
                        bancoProcessador = :bancoProcessador 
                    WHERE id = :id", 
                    [
                        "status" => "PROCESSADO",
                        "id" => $this->id_pagamento,
                        "bancoProcessador" => $paymentStatus['banco']
                    ]
                );
                $this->conn->exec("DELETE FROM tab_fila_pagamento WHERE id_pagamento = :id_pagamento", ["id_pagamento"=>$this->id_pagamento]);
                $this->conn->commit();
            } catch(Error $e) {
                $this->conn->rollBack();
                throw New Exception($e->getMessage());
            }
        }

        public function executePayment() {
            try {
                $this->changePaymentStatus();
                $payment = $this->conn->select("SELECT * FROM tab_pagamento WHERE id = :id", ["id"=>$this->id_pagamento]);
                $paymentStatus = $this->bank->registerPayment($payment);
                if($paymentStatus['status'] == true) {
                    $this->setPaymentAsComplete($paymentStatus);
                } else {
                    var_dump("falha");
                }
            } catch(Error $e) {
                $this->conn->rollBack();
                throw New Exception($e->getMessage());
            }

        }
    }

?>