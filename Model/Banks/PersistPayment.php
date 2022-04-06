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

        public function executePayment() {
            try {
                $payment = $this->conn->select("SELECT * FROM tab_pagamento WHERE id = :id", ["id"=>$this->id_pagamento]);
                $paymentStatus = $this->bank->registerPayment($payment);
                if($paymentStatus['status'] == true) {
                    $this->setPaymentAsProcessed($paymentStatus);
                } else {
                    echo "Falha ao registrar pagamento";
                }
            } catch(Error $e) {
                $this->conn->rollBack();
                throw New Exception($e->getMessage());
            }
        }

        public function getPaymentApproval() {
            try {
                $payment = $this->conn->select("SELECT * FROM tab_pagamento WHERE id = :id", ["id"=>$this->id_pagamento]);
                $paymentStatus = $this->bank->consultPayment($payment[0]['invoice'], $payment[0]['numeroDaContaDoBeneficiario']);
                $this->persistPaymentResult($paymentStatus['status']);
            } catch(Error $e) {
                echo "Erro ao verificar aprovação do pagamento";
            }
        }

        public function persistPaymentResult($result) {
            try {
                $this->conn->beginTransaction();
                $this->conn->exec("UPDATE tab_pagamento SET status = :status WHERE id = :id", ["status" => $result, "id"=> $this->id_pagamento]);
                $this->conn->exec("DELETE FROM tab_fila_pagamento WHERE id_pagamento = :id_pagamento", ["id_pagamento"=>$this->id_pagamento]);
                $this->conn->commit();
            } catch(Error $e) {
                $this->conn->rollBack();
                echo "Erro ao persistir o pagamento";
                exit;
            }
        }

        public function setPaymentAsProcessed($paymentStatus) {
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
                $this->conn->exec("
                    UPDATE tab_fila_pagamento SET 
                        status = :status 
                    WHERE 
                    id_pagamento = :id_pagamento",
                    [
                        "status" => "PROCESSADO",
                        "id_pagamento" => $this->id_pagamento
                    ]);
                $this->conn->commit();
            } catch(Error $e) {
                $this->conn->rollBack();
                echo "Erro ao processar o pagamento";
                exit;
            }
        }

    }

?>