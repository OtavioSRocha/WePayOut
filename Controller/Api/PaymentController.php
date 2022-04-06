<?php
    include_once "Model/Payment.php";

    class PaymentController extends BaseController {

        public function validaDados($dados) {
            $erros = "";
            if($dados->valorDoPagamento > 100000 || $dados->valorDoPagamento < 0.01) {
                $erros = $erros . " | Valor do pagamento inválido";
            }
            if(strlen($dados->codigoDoBancoDoBeneficiario) > 3 || strlen($dados->codigoDoBancoDoBeneficiario) < 1) {
                $erros = $erros . " | Código do banco inválido";
            }
            if(strlen($dados->numeroDaAgenciaDoBeneficiario) > 4 || strlen($dados->numeroDaAgenciaDoBeneficiario) < 1) {
                $erros = $erros . " | Agência inválida";
            }
            if(strlen($dados->numeroDaContaDoBeneficiario) > 15 || strlen($dados->numeroDaContaDoBeneficiario) < 1) {
                $erros = $erros . " | Conta inválida";
            }

            return $erros;
        }

        public function create() {

            $data =  file_get_contents('php://input');;
            $params = json_decode($data);
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'POST') {
                $valida = $this->validaDados($params);
                if(strlen($valida) == 0) {
                    $payment = new Payment;
                    $payment->create($params);
                } else {
                    $strErrorDesc = $valida; 
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            if (!$strErrorDesc) {
                $this->sendOutput(
                    "Payment successfully added",
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                    array('Content-Type: application/html', $strErrorHeader)
                );
            }
        }

        public function list($id = null) {

            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    
                    $payment = new Payment;
     
                    $arrPayment = $payment->list($id);
                    $responseData = json_encode($arrPayment);
                    
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
     
            if (!$strErrorDesc) {
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }

    }