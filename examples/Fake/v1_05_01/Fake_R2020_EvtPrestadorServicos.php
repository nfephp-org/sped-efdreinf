<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_1_5_1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_05_01', //versão do layout do evento
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
$std->nrinscestabprest = '12345678901234';
$std->tpinsctomador = "1";
$std->nrinsctomador = '12345678901234';
$std->indobra = 1;
$std->vlrtotalbruto = 1;
$std->vlrtotalbaseret = 1;
$std->vlrtotalretprinc = 1;
$std->vlrtotalretadic = 1;
$std->vlrtotalnretprinc = 1;
$std->vlrtotalnretadic = 1;

$std->nfs[0] = new \stdClass();
$std->nfs[0]->serie = '001';
$std->nfs[0]->numdocto = '265465';
$std->nfs[0]->dtemissaonf = '2017-01-22';
$std->nfs[0]->vlrbruto = 200.00;
$std->nfs[0]->obs = 'observacao pode ser nula';

$std->nfs[0]->infotpserv[0] = new \stdClass();
$std->nfs[0]->infotpserv[0]->tpservico = '123456789';
$std->nfs[0]->infotpserv[0]->vlrbaseret = 234.90;
$std->nfs[0]->infotpserv[0]->vlrretencao = 12.75;
$std->nfs[0]->infotpserv[0]->vlrretsub = 34.55;
$std->nfs[0]->infotpserv[0]->vlrnretprinc = 2345.75;
$std->nfs[0]->infotpserv[0]->vlrservicos15 = 22;
$std->nfs[0]->infotpserv[0]->vlrservicos20 = 33;
$std->nfs[0]->infotpserv[0]->vlrservicos25 = 44;
$std->nfs[0]->infotpserv[0]->vlradicional = 5;
$std->nfs[0]->infotpserv[0]->vlrnretadic = 1.55;

$std->infoprocretpr[0] = new \stdClass();
$std->infoprocretpr[0]->tpprocretprinc = 1;
$std->infoprocretpr[0]->nrprocretprinc = 'ZYX987';
$std->infoprocretpr[0]->codsuspprinc = '12345678901234';
$std->infoprocretpr[0]->valorprinc = 200.98;

$std->infoprocretad[0] = new \stdClass();
$std->infoprocretad[0]->tpprocretadic = 1;
$std->infoprocretad[0]->nrprocretadic = 'ACB21';
$std->infoprocretad[0]->codsuspadic = '12345678901234';
$std->infoprocretad[0]->valoradic = 1000.23;

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtServPrest(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r2020($json, $std, $certificate)->toXML();
    //$json = Event::evtServPrest($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
