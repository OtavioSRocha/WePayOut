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
            $payment = $this->conn->select("SELECT * FROM tab_pagamento WHERE id = :id", ["id"=>$this->id_pagamento]);
            $paymentStatus = $this->bank->registerPayment();
            
            var_dump($payment, $paymentStatus);exit;
        }
    }

?>