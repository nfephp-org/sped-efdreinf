<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 3, //tipo de ambiente 1 - Produção; 2 - Produção restrita - dados reais;3 - Produção restrita - dados fictícios.
    'verProc' => '0_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_02_00', //versão do layout do evento
    'serviceVersion' => '1_02_00',//versão do webservice
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

$std->indretif = 1;
$std->nrrecibo = 'ABCD-234-2020';
$std->dtapuracao = '2017-12-01';

$std->idecontri = new \stdClass();
$std->idecontri->tpinsc = 1;
$std->idecontri->nrinsc = '12345678901234';

$std->idecontri->ideestab[0] = new \stdClass();
$std->idecontri->ideestab[0]->tpinscestab = 1;
$std->idecontri->ideestab[0]->nrinscestab = '12345678901234';

$std->idecontri->ideestab[0]->boletim[0] = new \stdClass();
$std->idecontri->ideestab[0]->boletim[0]->nrboletim = 'abcd';
$std->idecontri->ideestab[0]->boletim[0]->tpcompeticao = 1;
$std->idecontri->ideestab[0]->boletim[0]->categevento = 1;
$std->idecontri->ideestab[0]->boletim[0]->moddesportiva = 'corrida de bigas';
$std->idecontri->ideestab[0]->boletim[0]->nomecompeticao = 'Torneio tornado';
$std->idecontri->ideestab[0]->boletim[0]->cnpjmandante = '12345678901234';
$std->idecontri->ideestab[0]->boletim[0]->cnpjvisitante = '12345678901234';
$std->idecontri->ideestab[0]->boletim[0]->nomevisitante = 'Quebra Toco FC';
$std->idecontri->ideestab[0]->boletim[0]->pracadesportiva = 'Estadio do outro';
$std->idecontri->ideestab[0]->boletim[0]->codmunic = '1234567';
$std->idecontri->ideestab[0]->boletim[0]->uf = 'PR';
$std->idecontri->ideestab[0]->boletim[0]->qtdepagantes = 3200;
$std->idecontri->ideestab[0]->boletim[0]->qtdenaopagantes = 200;

$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0] = new \stdClass();
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->tpingresso = 4;
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->descingr = 'entrada';
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->qtdeingrvenda = 34568;
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->qtdeingrvendidos = 24567;
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->qtdeingrdev = 3;
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->precoindiv = 23.76;
$std->idecontri->ideestab[0]->boletim[0]->receitaingressos[0]->vlrtotal = 290323.99;
        
$std->idecontri->ideestab[0]->boletim[0]->outrasreceitas[0] = new \stdClass();
$std->idecontri->ideestab[0]->boletim[0]->outrasreceitas[0]->tpreceita = 4;
$std->idecontri->ideestab[0]->boletim[0]->outrasreceitas[0]->vlrreceita = 392000029.56;
$std->idecontri->ideestab[0]->boletim[0]->outrasreceitas[0]->descreceita = 'money money dim dim';

$std->idecontri->ideestab[0]->receitatotal = new \stdClass();      
$std->idecontri->ideestab[0]->receitatotal->vlrreceitatotal = 3456720000.36;
$std->idecontri->ideestab[0]->receitatotal->vlrcp = 123450900.0;
$std->idecontri->ideestab[0]->receitatotal->vlrcpsusptotal = 3498282.84;
$std->idecontri->ideestab[0]->receitatotal->vlrreceitaclubes = 489388.43;
$std->idecontri->ideestab[0]->receitatotal->vlrretparc = 123.76;

$std->idecontri->ideestab[0]->receitatotal->infoproc[0] = new \stdClass(); 
$std->idecontri->ideestab[0]->receitatotal->infoproc[0]->vlrcpsusp = 2345678.93;
$std->idecontri->ideestab[0]->receitatotal->infoproc[0]->tpproc = 1;
$std->idecontri->ideestab[0]->receitatotal->infoproc[0]->nrproc = '829js,n,sn,n';
$std->idecontri->ideestab[0]->receitatotal->infoproc[0]->codsusp = '12345678901234';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtEspDesportivo(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r3010($json, $std, $certificate)->toXML();
    //$json = Event::evtEspDesportivo($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
