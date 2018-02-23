<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';


use NFePHP\Common\Certificate;
use JsonSchema\Validator;
use NFePHP\EFDReinf\Event;
use NFePHP\EFDReinf\Tools;
use NFePHP\EFDReinf\Common\FakePretty;
use NFePHP\EFDReinf\Common\Soap\SoapFake;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita - dados reais;3 - Produção restrita - dados fictícios.
    'verProc' => '0_1_3', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_03_00', //versão do layout do evento
    'serviceVersion' => '1_03_00',//versão do webservice
    'empregador' => [
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999', //numero do documento
        'nmRazao' => 'Razao Social'
    ],    
    'transmissor' => [
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999999999' //numero do documento
    ]
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

try {
    //carrega a classe responsavel por lidar com os certificados
    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //usar a classe Fake para não tentar enviar apenas ver o resultado da chamada
    $soap = new SoapFake();
    //desativa a validação da validade do certificado 
    //estamos usando um certificado vencido nesse teste
    $soap->disableCertValidation(true);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadSoapClass($soap);
    
    //executa a consulta 
    //Número do Protocolo do Fechamento (recebido no retorno do evento R-2099).
    $response = $tools->consultar('1.2.201707.0000000000000007638');    
    
    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, 'fake_envConsulta');
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
