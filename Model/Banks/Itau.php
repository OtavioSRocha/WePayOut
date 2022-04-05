<?php
    include_once "Model/Banks/PersistPaymentStrategyInterface.php";
    
    class Itau implements PersistPaymentStrategyInterface {
        public function registerPayment($payment) {
            if(rand(0,1)) {
                return [
                    "id_pagamento" => $payment[0]['id'],
                    "banco"=> "ITAU",
                    "status"=> true
                ];
            } else {
                return [
                    "banco"=> null,
                    "status"=> false
                ];
            }
        }

        public function consultPayment() {
            return rand(0,1);
        }
    }