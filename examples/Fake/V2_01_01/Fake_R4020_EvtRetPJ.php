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
    'serviceVersion' => '1_00_00', //versão do webservice
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
$std->indretif = 1;
$std->nrrecibo = '1-12-1234-123456-123456576';
$std->perapur = '2017-12';

$std->natjur = '1234'; //infoComplContri/natJur
$std->tpinscestab = '1';
$std->nrinscestab = '12345678901234';

$std->idebenef = new stdClass();
$std->idebenef->cnpjbenef = '12345678901234';
$std->idebenef->nmbenef = 'Fulano de Tal Ltda';
$std->idebenef->isenimun = '1';

$std->idepgto[0] = new stdclass();
$std->idepgto[0]->natrend = '10001';
$std->idepgto[0]->observ = 'bla bla bla';

$std->idepgto[0]->infopgto[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->dtFG = '2022-07-15';
$std->idepgto[0]->infopgto[0]->vlrBruto = 7834.45;
$std->idepgto[0]->infopgto[0]->indFciScp = '1';
$std->idepgto[0]->infopgto[0]->nrInscFciScp = '12345678901234';
$std->idepgto[0]->infopgto[0]->percscp = 2.0;
$std->idepgto[0]->infopgto[0]->indJud = 'N';
$std->idepgto[0]->infopgto[0]->paisResidExt = '169';

$std->idepgto[0]->infopgto[0]->retencoes = new stdclass();
$std->idepgto[0]->infopgto[0]->vlrBaseIR = 4324.56;
$std->idepgto[0]->infopgto[0]->vlrIR = 400.33;
$std->idepgto[0]->infopgto[0]->vlrBaseAgreg = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrAgreg = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrBaseCSLL = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrCSLL = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrBaseCofins = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrCofins = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrBasePP = 1000.00;
$std->idepgto[0]->infopgto[0]->vlrPP = 1000.00;

$std->idepgto[0]->infopgto[0]->infoprocret[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocret[0]->tpProcRet = '1';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->nrProcRet = '22222222';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->codSusp = '12345';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrBaseSuspIR = 200.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrNIR = 10.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrDepIR = 456.78;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrBaseSuspCSLL = 0.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrNCSLL = 10.11;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrDepCSLL = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrBaseSuspCofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrNCofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrDepCofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrBaseSuspPP = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrNPP = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrDepPP = 20.00;

$std->idepgto[0]->infopgto[0]->infoprocjud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->nrProc = '123456';
$std->idepgto[0]->infopgto[0]->infoprocjud->indOrigRec = '1';
$std->idepgto[0]->infopgto[0]->infoprocjud->cnpjOrigRecurso = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoprocjud->desc = 'blça bla bla';

$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->vlrDespCustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->vlrDespAdvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->ideAdv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->ideAdv[0]->tpInscAdv = '1';
$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->ideAdv[0]->nrInscAdv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoprocjud->despProcJud->ideAdv[0]->vlrAdv = 342.66;

$std->idepgto[0]->infopgto[0]->infopgtoext = new stdclass();
$std->idepgto[0]->infopgto[0]->infopgtoext->indNIF = '1';
$std->idepgto[0]->infopgto[0]->infopgtoext->nifBenef = '123456';
$std->idepgto[0]->infopgto[0]->infopgtoext->relFontPg = '500';
$std->idepgto[0]->infopgto[0]->infopgtoext->frmTribut = '10';

$std->idepgto[0]->infopgto[0]->infopgtoext->endExt = new stdclass();
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->dscLograd = 'logradouro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->nrLograd = '100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->complem = 'SALA 100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->bairro = 'bairro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->cidade = 'cidade';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->estado = 'estado';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->codPostal = '1234';
$std->idepgto[0]->infopgto[0]->infopgtoext->endExt->telef = '12345678901';

/*
echo "<pre>";
print_r($std);
echo "</pre>";
die;
*/


$json = json_encode($std, JSON_PRETTY_PRINT);


try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtRetPJ(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Event::r4020($configJson, $std, $certificate)->toXML();
    //$json = Event::evtRetPJ($configJson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
