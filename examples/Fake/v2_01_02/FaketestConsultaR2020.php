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
use stdClass;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_02', //versão do layout do evento
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
    $soap = new SoapFake();
    //desativa a validação da validade do certificado
    //estamos usando um certificado vencido nesse teste
    $soap->disableCertValidation(true);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadSoapClass($soap);

    //CONSULTAS
    //Consolidada
    //$std = new stdClass();
    //$std->numeroprotocolofechamento = '12345678901234';
    //$std->tipoinscricaocontribuinte = 2;
    //$std->numeroinscricaocontribuinte = '12345678901';
    //$response = $tools->consultar($tools::CONSULTA_CONSOLIDADA, $std);

    //CONSULTAS
    //R1000
    //$response = $tools->consultar($tools::CONSULTA_R1000, null);

    //CONSULTAS
    //R1070
    //$response = $tools->consultar($tools::CONSULTA_R1070, null);

    //CONSULTAS
    //R2010
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$std->cnpjprestador = '12345678901234';
    //$std->tpinscestab = 2;
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R2010, $std);

    //CONSULTAS
    //R2020
    $std = new stdClass();
    $std->perapur = '2018-12';
    $std->nrinscestabprest = '12345678901234';
    $std->tpinsctomador = 4; //1 CNPJ ou 4 CNO
    $std->nrinsctomador = '12345678901';
    $response = $tools->consultar($tools::CONSULTA_R2020, $std);

    //CONSULTAS
    //R2030
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R2030, $std);

    //CONSULTAS
    //R2040
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R2040, $std);

    //CONSULTAS
    //R2050
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R2050, $std);

    //CONSULTAS
    //R2060
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$std->nrinscestabprest = '12345678901234';
    //$std->tpinscestab = 1;
    //$std->nrinscestab = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R2060, $std);

    //CONSULTAS
    //R2098
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$response = $tools->consultar($tools::CONSULTA_R2098, $std);

    //CONSULTAS
    //R2099
    //$std = new stdClass();
    //$std->perapur = '2018-12';
    //$response = $tools->consultar($tools::CONSULTA_R2099, $std);

    //CONSULTAS
    //R3010
    //$std = new stdClass();
    //$std->dtapur = '2018-12-11';
    //$std->nrinscestabelecimento = '12345678901234';
    //$response = $tools->consultar($tools::CONSULTA_R3010, $std);

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
