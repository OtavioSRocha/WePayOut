<?php
    include_once "Model/Payment.php";
    include_once "Model/Banks/PersistPayment.php";

    class ScheduleController extends BaseController {

        public function executeQueue() {
            try {
                $queue = new Queue();
                $item = $queue->getFIUnprocessed();
                if(!empty($item)) {
                    $bank = new PersistPayment($item[0]['id_pagamento']);
                    $bank->executePayment();
                } else {
                    echo "Sem pagamentos na fila";
                }
                
            } catch(Error $e) {
                var_dump($e);
                return false;
            }
        }

        public function getPaymentApproval() {
            try {
                $queue = new Queue();
                $item = $queue->getFIProcessed();
                if(!empty($item)) {
                    $bank = new PersistPayment($item[0]['id_pagamento']);
                    $bank->getPaymentApproval();
                } else {
                    echo "Sem pagamentos na fila";
                }
                
            } catch(Error $e) {

            }
        }

    }