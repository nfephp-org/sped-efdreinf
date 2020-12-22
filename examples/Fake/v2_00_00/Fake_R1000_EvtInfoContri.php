<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_00_00', //versão do layout do evento
    'serviceVersion' => '2_00_00',//versão do webservice
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
$std->modo = 'INC';
$std->inivalid = '2017-01';
$std->fimvalid = '2017-12';

$std->infocadastro = new \stdClass();
$std->infocadastro->classtrib = '01';
$std->infocadastro->indescrituracao = 0;
$std->infocadastro->inddesoneracao = 0;
$std->infocadastro->indacordoisenmulta = 0;
$std->infocadastro->indsitpj = 0;

$std->infocadastro->contato = new \stdClass();
$std->infocadastro->contato->nmctt = 'Fulano de Tal';
$std->infocadastro->contato->cpfctt = '12345678901';
$std->infocadastro->contato->fonefixo = '0115555555';
$std->infocadastro->contato->fonecel = '1199999999';
$std->infocadastro->contato->email = 'fulano@email.com';

$std->infocadastro->softhouse[0] = new \stdClass();
$std->infocadastro->softhouse[0]->cnpjsofthouse = '12345678901234';
$std->infocadastro->softhouse[0]->nmrazao = 'Razao Social';
$std->infocadastro->softhouse[0]->nmcont = 'Fulano de Tal';
$std->infocadastro->softhouse[0]->telefone = '0115555555';
$std->infocadastro->softhouse[0]->email = 'fulano@email.com';

$std->infocadastro->infoefr = new \stdClass();
$std->infocadastro->infoefr->ideefr = 'N';
$std->infocadastro->infoefr->cnpjefr = '12345678901234';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtInfoContri(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::r1000($configJson, $std, $certificate)->toXML();
    //$json = Event::evtInfoContri($configJson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
