<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';


use NFePHP\Common\Certificate;
use NFePHP\EFDReinf\Tools;
use NFePHP\EFDReinf\Common\FakePretty;
use NFePHP\EFDReinf\Common\Restful\RestFake;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_1_1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_01', //versão do layout do evento
    'serviceVersion' => '1_05_01',//versão do webservice
    'contribuinte' => [
         //'admPublica' => false, //campo Opcional, deve ser true apenas se natureza
        //jurídica do contribuinte declarante for de administração pública
        //direta federal ([101-5], [104-0], [107-4], [116-3]
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '12345678901234', //numero do documento com 11 ou 14 digitos
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
    $rest = new RestFake();
    //desativa a validação da validade do certificado
    //estamos usando um certificado vencido nesse teste
    $rest->disableCertValidation(true);
    $rest->loadCertificate($certificate);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadRestClass($rest);

    //CONSULTAS
    //R1000
    //$std = new stdClass();
    //$std->evento = 'R1000';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R1050
    //$std = new stdClass();
    //$std->evento = 'R1050';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R1070
    //$std = new stdClass();
    //$std->evento = 'R1070';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2010
    $std = new stdClass();
    $std->evento = 'R2010';
    $std->perapur = '2018-12';
    $std->tpinscestab = 1;
    $std->nrinscestab = '12345678901234';
    $std->cnpjprestador = '12345678901234';
    $response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2020
    //$std = new stdClass();
    //$std->evento = 'R2020';
    //$std->perapur = '2018-12';
    //$std->nrinscestabprest = '12345678901234';
    //$std->tpinsctomador = '1';
    //$std->nrinsctomador = '123456789012';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2030
    //$std = new stdClass();
    //$std->evento = 'R2030';
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2040
    //$std = new stdClass();
    //$std->evento = 'R2040';
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2050
    //$std = new stdClass();
    //$std->evento = 'R2050';
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2055
    //$std = new stdClass();
    //$std->evento = 'R2055';
    //$std->perapur = '2018-12';
    //$std->tpinscadq = '1';
    //$std->nrinscadq = '12345678901234';
    //$std->tpinscprod = '2';
    //$std->nrinscprod = '12345678901';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2060
    //$std = new stdClass();
    //$std->evento = 'R2060';
    //$std->perapur = '2018-12';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2098
    //$std = new stdClass();
    //$std->evento = 'R2098';
    //$std->perapur = '2018-12';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R2099
    //$std = new stdClass();
    //$std->evento = 'R2099';
    //$std->perapur = '2018-12';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R3010
    //$std = new stdClass();
    //$std->evento = 'R3010';
    //$std->dtapur = '2018-12-11';
    //$std->nrinscestabelecimento = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R4010
    //$std = new stdClass();
    //$std->evento = 'R4010';
    //$std->perapur = '2018-12';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$std->cpfBenef = '12345678901';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R4020
    //$std = new stdClass();
    //$std->evento = 'R4020';
    //$std->perapur = '2018-12';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$std->cpfBenef = '12345678901';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R4040
    //$std = new stdClass();
    //$std->evento = 'R4040';
    //$std->perapur = '2018-12';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R4080
    //$std = new stdClass();
    //$std->evento = 'R4080';
    //$std->perapur = '2018-12';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$std->cnpjfonte = '12345678901234';
    //$response = $tools->consultarEventoAssincono($std);

    //CONSULTAS
    //R4099
    //$std = new stdClass();
    //$std->evento = 'R4099';
    //$std->perapur = '2018-12';
    //$response = $tools->consultarEventoAssincono($std);


    //echo "<pre>";
    //echo str_replace(['<', '>'],['&lt;','&gt;'], $response);
    //echo "</pre>";

    //header('Content-Type: application/xml; charset=utf-8');
    //echo $response;

    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, '');

} catch (\Exception $e) {
    echo $e->getMessage();
}
