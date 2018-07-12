<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_1_3', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_03_02', //versão do layout do evento
    'serviceVersion' => '1_03_02',//versão do webservice
    'contribuinte' => [
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
$std->perapur = '2017-11';
$std->cdretorno = 'asdfer';
$std->descretorno = 'Retorno descrição sei la alguma coisa';

$std->regocorrs[0] = new \stdClass();
$std->regocorrs[0]->tpocorr = 2;
$std->regocorrs[0]->localerroaviso = 'este é o local onde aconteceu';
$std->regocorrs[0]->codresp = 'ksksk';
$std->regocorrs[0]->dscresp = 'Este campo lorem ipsum';

$std->nrprotentr = '1101010101010101010';
$std->dhprocess = '2017-11-22T12:34:00';
$std->tpev = '123456';
$std->idev = 'lslslslslslslslsl';
$std->hash = 'akhld wy slksndsjhslslkjsk';

$std->infototalcontrib[0] = new \stdClass();
$std->infototalcontrib[0]->nrrecarqbase = 'ksksksksk';
$std->infototalcontrib[0]->indexistinfo = 1;

$std->infototalcontrib[0]->rtom[0] = new \stdClass();
$std->infototalcontrib[0]->rtom[0]->cnpjprestador = '12345678901234';
$std->infototalcontrib[0]->rtom[0]->vlrtotalbaseret = 11111.11;

$std->infototalcontrib[0]->rtom[0]->infocrtom[0] = new \stdClass();
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->crtom = '114106'; //1162-01'
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->vlrcrtom = 20.00;
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->vlrcrtomsusp = 12.00;

$std->infototalcontrib[0]->rprest[0] = new \stdClass();
$std->infototalcontrib[0]->rprest[0]->tpinsctomador = 1; //4
$std->infototalcontrib[0]->rprest[0]->nrinsctomador = '12345678901';
$std->infototalcontrib[0]->rprest[0]->vlrtotalbaseret = 2000.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalretprinc = 10000.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalretadic = 100.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalnretprinc = 200.22;
$std->infototalcontrib[0]->rprest[0]->vlrtotalnretadic = 33.03;

$std->infototalcontrib[0]->rrecrepad[0] = new \stdClass();
$std->infototalcontrib[0]->rrecrepad[0]->cnpjassocdesp = '12345678901234';
$std->infototalcontrib[0]->rrecrepad[0]->vlrtotalrep = 200.22;
$std->infototalcontrib[0]->rrecrepad[0]->crrecrepad = '123456';
$std->infototalcontrib[0]->rrecrepad[0]->vlrcrrecrepad = 1000.11;
$std->infototalcontrib[0]->rrecrepad[0]->vlrcrrecrepadsusp = 511.55;

$std->infototalcontrib[0]->rcoml[0] = new \stdClass();
$std->infototalcontrib[0]->rcoml[0]->crcoml = '165701';
$std->infototalcontrib[0]->rcoml[0]->vlrcrcoml = 222.22;
$std->infototalcontrib[0]->rcoml[0]->vlrcrcomlsusp = 33.33;

$std->infototalcontrib[0]->rcprb[0] = new \stdClass();
$std->infototalcontrib[0]->rcprb[0]->crcprb = '299101';
$std->infototalcontrib[0]->rcprb[0]->vlrcrcprb = 1111.01;
$std->infototalcontrib[0]->rcprb[0]->vlrcrcprbsusp = 44.04;


try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtTotalContrib(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r5011($json, $std, $certificate)->toXML();
    //$json = Event::evtTotalContrib($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
