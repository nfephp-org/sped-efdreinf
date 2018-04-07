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
$std->perapur = '2017-11';
$std->cdretorno = 'asdfer';
$std->descretorno = 'Retorno descrição sei la alguma coisa';

$std->regocorrs[0] = new \stdClass();
$std->regocorrs[0]->tpocorr = 2;
$std->regocorrs[0]->localerroaviso = 'este é o local onde aconteceu';
$std->regocorrs[0]->codresp = 'ksksk';
$std->regocorrs[0]->dscresp = 'Este campo lorem ipsum';

$std->dhprocess = '2017-11-22T12:34:00';
$std->tpev = '123456';
$std->idev = 'lslslslslslslslsl';
$std->hash = 'akhld wy slksndsjhslslkjsk';
$std->nrrecarqbase = 'ksksksksksksks';
$std->indexistinfo = 2;
$std->indescrituracao = 0;
$std->inddesoneracao = 1;
$std->indacordoisenmulta = 1;

$std->rtom[0] = new \stdClass();
$std->rtom[0]->cnpjprestador = '12345678901234';
$std->rtom[0]->vlrtotalbaseret = 11111.11;
$std->rtom[0]->vlrtotalretprinc = 2222.22;
$std->rtom[0]->vlrtotalretadic = 3333.33;
$std->rtom[0]->vlrtotalnretprinc = 4444.44;
$std->rtom[0]->vlrtotalnretadic = 5555.55;

$std->rprest[0] = new \stdClass();
$std->rprest[0]->tpinsctomador = 4;
$std->rprest[0]->nrinsctomador = '12345678901234';
$std->rprest[0]->vlrtotalbaseret = 2903030.92;
$std->rprest[0]->vlrtotalretprinc = 9292929;
$std->rprest[0]->vlrtotalretadic = 1111111;
$std->rprest[0]->vlrtotalnretprinc = 29982818.92;
$std->rprest[0]->vlrtotalnretadic = 1772717.88;

$std->rrecrepad[0] = new \stdClass();
$std->rrecrepad[0]->cnpjassocdesp = '12345678901234';
$std->rrecrepad[0]->vlrtotalrep = 20020.37;
$std->rrecrepad[0]->vlrtotalret = 292929.22;
$std->rrecrepad[0]->vlrtotalnret = 8748383.00;

$std->rcoml = new \stdClass();
$std->rcoml->vlrcpapur = 12345.98;
$std->rcoml->vlrratapur = 12345.98;
$std->rcoml->vlrsenarapur = 12345.98;
$std->rcoml->vlrcpsusp = 12345.98;
$std->rcoml->vlrratsusp = 12345.98;
$std->rcoml->vlrsenarsusp = 12345.98;

$std->rcprb[0] = new \stdClass();
$std->rcprb[0]->codrec = '123456';
$std->rcprb[0]->vlrcpapurtotal = 2345675.93;
$std->rcprb[0]->vlrcprbsusp = 2311.87;

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtTotal(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r5001($json, $std, $certificate)->toXML();
    //$json = Event::evtTotal($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
