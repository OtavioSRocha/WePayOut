<?php
    include_once "Model/Banks/PersistPaymentStrategyInterface.php";
    
    class BancoDoBrasil implements PersistPaymentStrategyInterface {
        public function registerPayment() {
            return rand(0,1);
        }

        public function consultPayment() {
            return rand(0,1);
        }
    }