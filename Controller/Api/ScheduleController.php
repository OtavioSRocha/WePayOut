<?php
    include_once "Model/Payment.php";
    include_once "Model/Banks/PersistPayment.php";

    class ScheduleController extends BaseController {

        public function executeQueue() {
            try {
                $queue = new Queue();
                $item = $queue->getFI();

                $bank = new PersistPayment($item[0]['id_pagamento']);
                $bank->executePayment();

                
            } catch(Error $e) {
                var_dump($e);
                return false;
            }
        }

    }