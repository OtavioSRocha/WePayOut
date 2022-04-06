## Introdução
Simulador de sistema de pagamento com fila.


Para rodar o projeto é necessário estar com o rewrite mode ativado no PHP
PHP 8.0.17
MariaDB 10.4.24

## Creating database

```sql
CREATE DATABASE wepayout_database;

CREATE TABLE tab_pagamento(
    id INT NOT NULL AUTO_INCREMENT,
    invoice VARCHAR(250) NOT NULL,
    nomeDoBeneficiario VARCHAR(250) NOT NULL,
    codigoDoBancoDoBeneficiario VARCHAR(3) NOT NULL,
    numeroDaAgenciaDoBeneficiario VARCHAR(4) NOT NULL,
    numeroDaContaDoBeneficiario VARCHAR(15) NOT NULL,
    valorDoPagamento DOUBLE NOT NULL,
    status VARCHAR(200) NOT NULL DEFAULT 'CRIADO',
    bancoProcessador VARCHAR(200) NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;

CREATE TABLE tab_fila_pagamento(
    id INT NOT NULL AUTO_INCREMENT,
    id_pagamento INT NOT NULL,
    invoice_pagamento VARCHAR(250) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;
```

As configurações da conexão ficam no arquivo `.env` na raiz do projeto;

# Routes


### List all payments
```
[GET] /index.php/payment/list
```

### Receive a specific payment
```
[GET] /index.php/payment/list/{id}
```

### Create a payment
```
[POST] /index.php/payment/create
```
Body
```json
{
	"invoice": "invoice",
	"nomeDoBeneficiario":"Nome do Beneficiário",
	"codigoDoBancoDoBeneficiario": "123",
	"numeroDaAgenciaDoBeneficiario": "1234",
	"numeroDaContaDoBeneficiario": "123456789012345",
	"valorDoPagamento": "99.99"
}
```

### Rota para enviar requisição para os bancos
```
[GET | POST] /index.php/schedule/executeQueue
```

### Rota para verificar a aprovação do pagamento
```
[GET | POST] /index.php/schedule/getPaymentApproval
```

# Schedule
### Windows
    Os arquivos para serem adicionados ao schedule do Windows estão na pasta `PowerShell`.

### Linux
    Os comandos para serem adicionados ao crontab do linux estão no arquivo `crontab.txt`.
