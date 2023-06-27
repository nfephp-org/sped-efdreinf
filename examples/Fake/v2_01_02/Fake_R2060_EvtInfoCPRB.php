<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_02', //versão do layout do evento
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
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';
$std->tpinscestab = "1";
$std->nrinscestab = "123456789012";
$std->vlrrecbrutatotal = 10000.00;
$std->vlrcpapurtotal = 1020.00;
$std->vlrcprbsusptotal = 200.00;

$std->tipocod[0] = new \stdClass();
$std->tipocod[0]->codativecon = '12345678';
$std->tipocod[0]->vlrrecbrutaativ = 4444.44;
$std->tipocod[0]->vlrexcrecbruta = 3333.33;
$std->tipocod[0]->vlradicrecbruta = 2222.22;
$std->tipocod[0]->vlrbccprb = 1111.11;
$std->tipocod[0]->vlrcprbapur = 2000.00;

$std->tipocod[0]->tipoajuste[0] = new \stdClass();
$std->tipocod[0]->tipoajuste[0]->tpajuste = 0;
$std->tipocod[0]->tipoajuste[0]->codajuste = 11;
$std->tipocod[0]->tipoajuste[0]->vlrajuste = 200.00;
$std->tipocod[0]->tipoajuste[0]->descajuste = 'sei la';
$std->tipocod[0]->tipoajuste[0]->dtajuste = '2017-10';

$std->tipocod[0]->infoproc[0] = new \stdClass();
$std->tipocod[0]->infoproc[0]->tpproc = 1;
$std->tipocod[0]->infoproc[0]->nrproc = 'ABC21';
$std->tipocod[0]->infoproc[0]->codsusp = '12345678901234';
$std->tipocod[0]->infoproc[0]->vlrcprbsusp = 200.00;

try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtCPRB(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Evento::r2060($json, $std, $certificate)->toXML();
    //$json = Event::evtCPRB($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
