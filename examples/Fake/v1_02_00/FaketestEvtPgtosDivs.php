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
$std->indretif = 1;
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';

$std->codpgto = '0916';
$std->tpinscbenef = 2;
$std->nrinscbenef = '12345678901';
$std->nmrazaobenef = 'Fulano de Tal';

$std->inforesidext = new \stdClass();
$std->inforesidext->paisresid = '123';
$std->inforesidext->dsclograd = 'Av. 5';
$std->inforesidext->nrlograd = '342L';
$std->inforesidext->complem = 'Apto 32';
$std->inforesidext->bairro = 'Soho';
$std->inforesidext->cidade = 'New Jersey';
$std->inforesidext->codpostal = '1234567890';

$std->inforesidext->indnif = 1;
$std->inforesidext->nifbenef = '123456789';
$std->inforesidext->relfontepagad = '500';

$std->infomolestia = new \stdClass();
$std->infomolestia->dtlaudo = '2016-05-22';

$std->ideestab[0] = new \stdClass();
$std->ideestab[0]->tpinsc = 1;
$std->ideestab[0]->nrinsc = '12345678901234';

$std->ideestab[0]->pgtopf[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->dtpgto = '2017-10-10';
$std->ideestab[0]->pgtopf[0]->indsuspexig = 'N';
$std->ideestab[0]->pgtopf[0]->inddecterceiro = 'N';
$std->ideestab[0]->pgtopf[0]->vlrrendtributavel = 2000;
$std->ideestab[0]->pgtopf[0]->vlrirrf = 380;

$std->ideestab[0]->pgtopf[0]->detdeduca[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->detdeduca[0]->indtpdeducao = 4;
$std->ideestab[0]->pgtopf[0]->detdeduca[0]->vlrdeducao = 100;

$std->ideestab[0]->pgtopf[0]->rendisento[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->rendisento[0]->tpisencao = 1;
$std->ideestab[0]->pgtopf[0]->rendisento[0]->vlrisento = 30000;
$std->ideestab[0]->pgtopf[0]->rendisento[0]->descrendimento = 'chega de impostos';

$std->ideestab[0]->pgtopf[0]->detcompet[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->detcompet[0]->indperreferencia = 1;
$std->ideestab[0]->pgtopf[0]->detcompet[0]->perrefpagto = '2017-10';
$std->ideestab[0]->pgtopf[0]->detcompet[0]->vlrrendtributavel = 20;

$std->ideestab[0]->pgtopf[0]->inforra[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->inforra[0]->tpprocrra = 1;
$std->ideestab[0]->pgtopf[0]->inforra[0]->nrprocrra = 'abcdefg';
$std->ideestab[0]->pgtopf[0]->inforra[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopf[0]->inforra[0]->natrra = 'sei la';
$std->ideestab[0]->pgtopf[0]->inforra[0]->qtdmesesrra = 49;

$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud = new \stdClass();  
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->vlrdespcustas = 10;
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->vlrdespadvogados = 1.45;

$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->nrprocjud = 'sei la';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->indorigemrecursos = 1;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->vlrdespcustas = 200;
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->vlrdespadvogados = 2.90;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->origemrecursos = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->origemrecursos->cnpjorigemrecursos = '12345678901234';

$std->ideestab[0]->pgtopf[0]->depjudicial = new \stdClass();
$std->ideestab[0]->pgtopf[0]->depjudicial->vlrdepjudicial = 23.97;

$std->ideestab[0]->pgtopj[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->dtpagto = '2017-01-10';
$std->ideestab[0]->pgtopj[0]->vlrrendtributavel = 2000;
$std->ideestab[0]->pgtopj[0]->vlrret = 30.67;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->nrprocjud = 'sei la';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->indorigemrecursos = 1;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->vlrdespcustas = 200;
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->vlrdespadvogados = 2.90;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->origemrecursos = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->origemrecursos->cnpjorigemrecursos = '12345678901234';

$std->ideestab[0]->pgtoresidext = new \stdClass();
$std->ideestab[0]->pgtoresidext->dtpagto = '2017-02-22';
$std->ideestab[0]->pgtoresidext->tprendimento = 140;
$std->ideestab[0]->pgtoresidext->formatributacao = 12;
$std->ideestab[0]->pgtoresidext->vlrpgto = 2000;
$std->ideestab[0]->pgtoresidext->vlrret = 22.95;

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtPgtosDivs(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Evento::r2070($json, $std, $certificate)->toXML();
    //$json = Event::evtPgtosDivs($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
