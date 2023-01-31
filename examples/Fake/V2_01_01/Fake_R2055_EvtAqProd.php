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
    'serviceVersion' => '1_00_00', //versão do webservice
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
$std->nrrecibo = '1-23-4567-8901-2345';
$std->perapur = '2017-11';

$std->tpinscadq = "1"; //1 cnpj ou 3 CAEPF
$std->nrinscadq = "12345678901234"; //cnpj ou caepf
$std->tpinscprod = 1; //1-CNPJ 2-CPF
$std->nrinscprod = '12345678901234'; //cnpj ou cpf
$std->indopccp = "S"; //null ou "S"

$std->detaquis[0] = new \stdClass();
$std->detaquis[0]->indaquis = 1; //de 1 até 7
$std->detaquis[0]->vlrbruto = 10000.00;
$std->detaquis[0]->vlrcpdescpr = 5000.56;
$std->detaquis[0]->vlrratdescpr = 100.77;
$std->detaquis[0]->vlrsenardesc = 50.88;
$std->detaquis[0]->infoprocjud[0] = new \stdClass();
$std->detaquis[0]->infoprocjud[0]->nrprocjud = '123456';
$std->detaquis[0]->infoprocjud[0]->codsusp = '9292929';
$std->detaquis[0]->infoprocjud[0]->vlrcpnret = 1000.55;
$std->detaquis[0]->infoprocjud[0]->vlrratnret = 101.02;
$std->detaquis[0]->infoprocjud[0]->vlrsenarnret = 852.31;

try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtAqProd(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Evento::r2050($json, $std, $certificate)->toXML();
    //$json = Event::evtaqprod($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
