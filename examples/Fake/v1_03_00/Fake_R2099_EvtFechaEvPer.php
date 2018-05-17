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
$std->iderespinf= new \stdClass();
$std->iderespinf->nmresp = 'Ciclano de Tal';
$std->iderespinf->cpfresp = '12345678901';
$std->iderespinf->telefone = '5555-5555';
$std->iderespinf->email = 'ciclano@mail.com';

$std->evtservtm = 'S';
$std->evtservpr = 'S';
$std->evtassdesprec = 'S';
$std->evtassdesprep = 'S';
$std->evtcomprod = 'S';
$std->evtcprb = 'S';
$std->evtpgtos = 'S';
$std->compsemmovto = '2017-12';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtFechaEvPer(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r2099($json, $std, $certificate)->toXML();
    //$json = Event::evtFechaEvPer($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
