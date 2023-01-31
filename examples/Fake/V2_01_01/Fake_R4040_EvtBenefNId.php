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
//$std->nrrecibo = '1234567890123456789-23-4567-8901-1234567891234567899'; //Opcional indicar quando indretif = 2
$std->perapur = '2017-11';

//$std->tpinscestab = "1"; //Opcional FIXO tipo de inscrição do estabelecimento contratante dos serviços: 1 - CNPJ;
$std->nrinscestab = '12345678901234'; //Obrigatório numero de inscrição do estabelecimento contratante dos serviços

$std->idenat[0] = new stdClass(); //Obrigatório
$std->idenat[0]->natrend = '19001'; //Obrigatório apenas 19001 e 19009 são permitidos

$std->idenat[0]->infopgto[0] = new stdClass(); //Obrigatório
$std->idenat[0]->infopgto[0]->dtFG = '2022-07-30'; //Obrigatório
$std->idenat[0]->infopgto[0]->vlrLiq = 1000; //Obrigatório
$std->idenat[0]->infopgto[0]->vlrBaseIR = 2000; //Obrigatório
$std->idenat[0]->infopgto[0]->vlrIR = 500; //Opcional
$std->idenat[0]->infopgto[0]->descr = 'bla bla bla'; //Obrigatório

$std->idenat[0]->infopgto[0]->infoProcRet[0] = new stdClass(); //Opcional
$std->idenat[0]->infopgto[0]->infoProcRet[0]->tpProcRet = '1'; //Obrigatório
$std->idenat[0]->infopgto[0]->infoProcRet[0]->nrProcRet = '123344'; //Obrigatório
$std->idenat[0]->infopgto[0]->infoProcRet[0]->codSusp = '12345'; //Opcional
$std->idenat[0]->infopgto[0]->infoProcRet[0]->vlrBaseSuspIR = 1000; //Opcional
$std->idenat[0]->infopgto[0]->infoProcRet[0]->vlrNIR = 234.55; //Opcional
$std->idenat[0]->infopgto[0]->infoProcRet[0]->vlrDepIR = 654.33; //Opcional

try {

   //carrega a classe responsavel por lidar com os certificados
    $content  = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtBenefNId(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();

    //$xml = Evento::r4040($json, $std, $certificate)->toXML();
    //$json = Event::evtBenefNId($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
