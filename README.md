## Introdução
Simulador de sistema de pagamento com fila.


Para rodar o projeto é necessário estar com o rewrite mode ativado no PHP.

PHP 8.0.17.

MariaDB 10.4.24.

## Composer
Execute o composer para instalar as dependencias do projeto.
```bash
composer install
```

## Creating database

```sql
CREATE DATABASE wepayout_database;

CREATE TABLE wepayout_database.tab_pagamento(
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

CREATE TABLE wepayout_database.tab_fila_pagamento(
    id INT NOT NULL AUTO_INCREMENT,
    id_pagamento INT NOT NULL,
    invoice_pagamento VARCHAR(250) NOT NULL,
    hora datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'CRIADO',
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


# Rodar utilizando o Docker (docker-compose)
O Docker e o Docker Compose já devem estar instalados na máquina

## Docker Network
Execute o comando abaixo para criar a network

```bash
docker network create wepayout
```

## Traefik
#### Criando o container do Traefik
Crie o arquivo docker-compose.yml em uma pasta vazia com o conteúdo abaixo:
```yml
version: '3'
services:
  reverse-proxy:
    image: traefik:1.7.14
    command: --api --docker
    ports:
      - "80:80"
      - "443:443"
      - "8080:8080"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    networks:
       - wepayout
    restart: always
    container_name: traefik
networks:
  wepayout:
    external: true
```

Na mesma pasta onde o arquivo está execute o comando
```bash
docker-compose up --build -d
```

## Rodando o projeto

Na raiz do projeto execute o comando

```bash
docker-compose up --build -d
```