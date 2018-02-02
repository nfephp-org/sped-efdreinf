<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 3, //tipo de ambiente 1 - Produção; 2 - Produção restrita - dados reais;3 - Produção restrita - dados fictícios.
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

$std = new \stdClass();
$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';
$std->tpinscestab = 1;
$std->nrinscestab = "12345678901";
$std->vlrrecbrutatotal = '10000,00'; 
$std->vlrcpapurtotal = '1020,00';
$std->vlrcprbsusptotal = '200,00';

$std->tipocod[0] = new \stdClass();
$std->tipocod[0]->codativecon = '12345678';
$std->tipocod[0]->vlrrecbrutaativ = '4444,44';
$std->tipocod[0]->vlrexcrecbruta = '3333,33';
$std->tipocod[0]->vlradicrecbruta = '2222,22';
$std->tipocod[0]->vlrbccprb = '1111,11';
$std->tipocod[0]->vlrcprbapur = '2000,00';

$std->tipocod[0]->tipoajuste[0] = new \stdClass();
$std->tipocod[0]->tipoajuste[0]->tpajuste = 0;
$std->tipocod[0]->tipoajuste[0]->codajuste = 11;
$std->tipocod[0]->tipoajuste[0]->vlrajuste = '200,00';
$std->tipocod[0]->tipoajuste[0]->descajuste = 'sei la';
$std->tipocod[0]->tipoajuste[0]->dtajuste = '2017-10';

$std->infoproc[0] = new \stdClass();
$std->infoproc[0]->vlrcprbsusp = '200,00';
$std->infoproc[0]->tpproc = 1;
$std->infoproc[0]->nrproc = 'ABC21';
$std->infoproc[0]->codsusp = '12345678901234';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtCPRB(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r2060($json, $std, $certificate)->toXML();
    //$json = Event::evtCPRB($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
