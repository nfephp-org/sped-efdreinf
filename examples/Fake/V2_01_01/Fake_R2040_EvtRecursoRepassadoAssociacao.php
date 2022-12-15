<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

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

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 1;
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';
$std->nrinscestab = '12345678901234';

$std->recursosrep[0] = new \stdClass();
$std->recursosrep[0]->cnpjassocdesp = '12345678901234';
$std->recursosrep[0]->vlrtotalrep = 1000.00;
$std->recursosrep[0]->vlrtotalret = 100.00;
$std->recursosrep[0]->vlrtotalnret = 10.00;

$std->recursosrep[0]->inforecurso[0] = new \stdClass();
$std->recursosrep[0]->inforecurso[0]->tprepasse = 3;
$std->recursosrep[0]->inforecurso[0]->descrecurso = 'sei la';
$std->recursosrep[0]->inforecurso[0]->vlrbruto = 5000.03;
$std->recursosrep[0]->inforecurso[0]->vlrretapur = 500.99;

$std->recursosrep[0]->infoproc[0] = new \stdClass();
$std->recursosrep[0]->infoproc[0]->tpproc = 1;
$std->recursosrep[0]->infoproc[0]->nrproc = 'ABC21';
$std->recursosrep[0]->infoproc[0]->codsusp = '12345678901234';
$std->recursosrep[0]->infoproc[0]->vlrnret = 1000.66;

try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtAssocDespRep(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Evento::r2040($json, $std, $certificate)->toXML();
    //$json = Event::evtAssocDespRep($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
