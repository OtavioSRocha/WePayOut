### Creating database

```sql
CREATE DATABASE wepayout_database;
````
```sql
CREATE TABLE wepayout_database.tab_pagamento(
    id INT NOT NULL AUTO_INCREMENT,
    invoice VARCHAR(250) NOT NULL,
    nomeDoBeneficiario VARCHAR(250) NOT NULL,
    codigoDoBancoDoBeneficiario VARCHAR(3) NOT NULL,
    numeroDaAgenciaDoBeneficiario VARCHAR(4) NOT NULL,
    numeroDaContaDoBeneficiario VARCHAR(15) NOT NULL,
    valorDoPagamento DOUBLE NOT NULL,
    status VARCHAR(200) NOT NULL,
    bancoProcessador VARCHAR(200) NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;
```

As configurações da conexão ficam no arquivo `Model/Database.php`