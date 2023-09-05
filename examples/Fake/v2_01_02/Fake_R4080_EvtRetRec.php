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
$std->indretif = 2;
$std->nrrecibo = '1-12-1234-1234-123456576';
$std->perapur = '2017-11';

$std->tpinscestab = "1"; //Opcional FIXO tipo de inscrição do estabelecimento contratante dos serviços: 1 - CNPJ;
$std->nrinscestab = '12345678901234'; //Obrigatório numero de inscrição do estabelecimento contratante dos serviços

$std->cnpjfont = '12345678901234';
$std->ideRend[0] = new \stdClass();
$std->ideRend[0]->natrend = '10001';
$std->ideRend[0]->observ = 'blça bla bla';

$std->ideRend[0]->infoRec[0] = new \stdClass();
$std->ideRend[0]->infoRec[0]->dtFG = '2022-08-12';
$std->ideRend[0]->infoRec[0]->vlrBruto = 120000;
$std->ideRend[0]->infoRec[0]->vlrBaseIR = 10000;
$std->ideRend[0]->infoRec[0]->vlrIR = 2900;

$std->ideRend[0]->infoRec[0]->infoProcRet[0] = new \stdClass();
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->tpProcRet = '1';
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->nrProcRet = '123455';
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->codSusp = '123';
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->vlrbasesuspir = 20000;
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->vlrnir = 100;
$std->ideRend[0]->infoRec[0]->infoProcRet[0]->vlrdepir = 10000;

try {

   //carrega a classe responsavel por lidar com os certificados
    $content  = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtRetRec(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();

    //$xml = Evento::r4080($json, $std, $certificate)->toXML();
    //$json = Event::evtRetRec($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
