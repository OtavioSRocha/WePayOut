<?php
class Queue {
    private $conn;

    public function __construct() {
        $this->conn = new Database();
    }

    public function addQueue($params, $hora = 0) {
        if($hora > 0 ){
            $params['hora'] = $hora;
            $this->conn->exec("INSERT INTO tab_fila_pagamento (id_pagamento, invoice_pagamento, hora) VALUES (:id_pagamento, :invoice_pagamento, :hora)", $params);
        } else {
            $this->conn->exec("INSERT INTO tab_fila_pagamento (id_pagamento, invoice_pagamento) VALUES (:id_pagamento, :invoice_pagamento)", $params);
        }
    }

    public function getFIUnprocessed() {
        return $this->conn->select("SELECT * FROM tab_fila_pagamento WHERE status != 'PROCESSADO' ORDER BY id ASC LIMIT 1");
    }

    public function getFIProcessed() {
        return $this->conn->select("SELECT * FROM tab_fila_pagamento WHERE status = 'PROCESSADO' AND hora <= current_timestamp ORDER BY id ASC LIMIT 1");
    }

}