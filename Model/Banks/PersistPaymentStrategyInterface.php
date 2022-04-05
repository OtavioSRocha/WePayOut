<?php
    interface PersistPaymentStrategyInterface {
        public function registerPayment();
        public function consultPayment();
    }
?>