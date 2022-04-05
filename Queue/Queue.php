<?php
class Queue {
    private $conn;

    public function __construct() {
        $this->conn = new Database();
    }

    public function addQueue($params) {
        $this->conn->exec("INSERT INTO tab_fila_pagamento (id_pagamento, invoice_pagamento) VALUES (:id_pagamento, :invoice_pagamento)", $params);
    }

    public function getFI() {
        return $this->conn->select("SELECT * FROM tab_fila_pagamento ORDER BY id ASC LIMIT 1");
    }

}