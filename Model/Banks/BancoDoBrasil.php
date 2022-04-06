<?php
    include_once "Model/Banks/PersistPaymentStrategyInterface.php";
    
    class BancoDoBrasil implements PersistPaymentStrategyInterface {
        public function registerPayment($payment) {
            return [
                "id_pagamento" => $payment[0]['id'],
                "banco"=> "BB",
                "status"=> true
            ];
        }

        public function consultPayment($invoice, $conta) {
            if(rand(0,1)) {
                return [
                    "invoice" => $invoice,
                    "conta" => $conta,
                    "status" => "APROVADO"
                ];
            } else {
                return [
                    "invoice" => $invoice,
                    "conta" => $conta,
                    "status" => "REJEITADO"
                ];
            }
        }
    }