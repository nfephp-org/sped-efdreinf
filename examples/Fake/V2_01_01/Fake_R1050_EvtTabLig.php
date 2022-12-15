<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita (homologação)
    'verProc' => '0_2_1_1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_01', //versão do layout do evento
    'serviceVersion' => '1_05_01', //versão do webservice
    'contribuinte' => [
        //'admPublica' => false, //campo Opcional, deve ser true apenas se natureza
        //jurídica do contribuinte declarante for de administração pública
        //direta federal ([101-5], [104-0], [107-4], [116-3]
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '12345678901234', //numero do documento com 11 ou 14 digitos
        'nmRazao' => 'Razao Social'
    ],
    'transmissor' => [  //refere ao proprietario do certificado digital usado
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999999999' //numero do documento
    ]
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->modo = 'EXC'; //Obrigatório INC-inclusao ALT-alteração EXC-exclusao
$std->tpentlig = 1; //Opcional
$std->cnpjlig = '12345678901234'; //Obrigatório
$std->inivalid = '2017-01'; //Obrigatório inicio da validade data de inicio da OBRIGATORIEDADE da declaração
$std->fimvalid = '2017-02'; //Opcional somente deve ser passado caso seja uma alteração de dados

//indicar somente quando for uma alteração com novo periodo de validade
$std->novavalidade = new \stdClass(); //Opcional
$std->novavalidade->inivalid = '2017-02'; //Obrigatório inicio da validade dessa nova informação
$std->novavalidade->fimvalid = null; //Opcional não deve ser declarado o fim da validade, isso é usado em casos RAROS onde se sabe a data que o evento será modificado


$json = json_encode($std, JSON_PRETTY_PRINT);


try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtTabLig(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Event::r1050($configJson, $std, $certificate)->toXML();
    //$json = Event::evtItablig($configJson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
