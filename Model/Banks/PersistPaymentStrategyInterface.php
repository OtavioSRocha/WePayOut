<?php
    interface PersistPaymentStrategyInterface {
        public function registerPayment($payment);
        public function consultPayment($invoice, $conta);
    }
?>