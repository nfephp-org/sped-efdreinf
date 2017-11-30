<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 3, //tipo de ambiente 1 - Produção; 2 - Produção restrita - dados reais;3 - Produção restrita - dados fictícios.
    'verProc' => '0_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_02_00', //versão do layout do evento
    'serviceVersion' => '1_02_00',//versão do webservice
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
$std->tpproc = 1;
$std->nrproc = 'apc123455678';
$std->inivalid = '2016-11';
$std->fimvalid = '2017-11';
$std->indautoria = 1;
$std->modo = 'ALT';

$std->infosusp[0] = new \stdClass();
$std->infosusp[0]->codsusp = '234567890123';
$std->infosusp[0]->indsusp = '01';
$std->infosusp[0]->dtdecisao = '2017-10-31';
$std->infosusp[0]->inddeposito = 'S';

$std->dadosprocjud = new \stdClass();
$std->dadosprocjud->ufvara = 'SP';
$std->dadosprocjud->codmunic = '3548714';
$std->dadosprocjud->idvara = 'TR';

$std->novavalidade = new \stdClass();
$std->novavalidade->inivalid = '2017-12';
$std->novavalidade->fimvalid = '2018-12';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtTabProcesso(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::r1070($configJson, $std, $certificate)->toXML();
    //$json = Event::evtTabProcesso($configJson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
