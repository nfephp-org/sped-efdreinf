<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita (homologação)
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
    'transmissor' => [  //refere ao proprietario do certificado digital usado
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999999999' //numero do documento
    ]
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 1;
$std->nrrecibo = '1-12-1234-1234-123456576';
$std->perapur = '2017-12';

$std->natjur = '1234'; //infoComplContri/natJur
$std->tpinscestab = '1';
$std->nrinscestab = '12345678901234';

$std->idebenef = new stdClass();
$std->idebenef->cpfbenef = '12345678901';
$std->idebenef->nmbenef = 'Fulano de Tal';
$std->idebenef->ideevtadic = 'AB345678';

$std->idedep[0] = new stdclass();
$std->idedep[0]->cpfdep = '12345678901';
$std->idedep[0]->reldep = '1';
$std->idedep[0]->descdep = 'esposa';

$std->idepgto[0] = new stdclass();
$std->idepgto[0]->natrend = '10001';
$std->idepgto[0]->observ = 'bla bla bla';

$std->idepgto[0]->infopgto[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->dtFG = '2022-07-15';
$std->idepgto[0]->infopgto[0]->compFP = '2022';
$std->idepgto[0]->infopgto[0]->indDecTerc = 'S';
$std->idepgto[0]->infopgto[0]->vlrRendBruto = 7834.45;
$std->idepgto[0]->infopgto[0]->vlrRendTrib = 4324.56;
$std->idepgto[0]->infopgto[0]->vlrIR = 400.33;
$std->idepgto[0]->infopgto[0]->indRRA = 'S';
$std->idepgto[0]->infopgto[0]->indFciScp = '1';
$std->idepgto[0]->infopgto[0]->nrInscFciScp = '12345678901234';
$std->idepgto[0]->infopgto[0]->percSCP = 2.4;
$std->idepgto[0]->infopgto[0]->indJud = 'N';
$std->idepgto[0]->infopgto[0]->paisResidExt = '169';
$std->idepgto[0]->infopgto[0]->dtescrcont = '2022-03-03';
$std->idepgto[0]->infopgto[0]->observ = 'Observações não sei para o que';

$std->idepgto[0]->infopgto[0]->detDed[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detDed[0]->indTpDeducao = '1';
$std->idepgto[0]->infopgto[0]->detDed[0]->vlrDeducao = 1230.67;
$std->idepgto[0]->infopgto[0]->detDed[0]->infoEntid = 'S';
$std->idepgto[0]->infopgto[0]->detDed[0]->nrInscPrevComp = '12345678901234';
$std->idepgto[0]->infopgto[0]->detDed[0]->vlrPatrocFunp = 987.44;

$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0]->cpfDep = '12345678901';
$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0]->vlrDepen = 874.55;

$std->idepgto[0]->infopgto[0]->rendIsento[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->rendIsento[0]->tpIsencao = '1';
$std->idepgto[0]->infopgto[0]->rendIsento[0]->vlrIsento = 2345.22;
$std->idepgto[0]->infopgto[0]->rendIsento[0]->descRendimento = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->rendIsento[0]->dtLaudo = '2021-01-15';

$std->idepgto[0]->infopgto[0]->infoProcRet[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->tpProcRet = '1';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->nrProcRet = '22222222';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->codSusp = '12345';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrNRetido = 200.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrDepJud = 10.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrCmpAnoCal = 456.78;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrCmpAnoAnt = 0.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrRendSusp = 10.11;

$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->indTpDeducao = '5';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->vlrDedSusp = 2500.25;

$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0]->cpfDep = '12345678901';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0]->vlrDepenSusp = 2500.25;

$std->idepgto[0]->infopgto[0]->infoRRA = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->tpProcRRA = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->nrProcRRA = '122344';
$std->idepgto[0]->infopgto[0]->infoRRA->indOrigRec = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->descRRA = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->infoRRA->qtdMesesRRA = 6.0;
$std->idepgto[0]->infopgto[0]->infoRRA->cnpjOrigRecurso = '12345678901234';

$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->vlrDespCustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->vlrDespAdvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->tpInscAdv = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->nrInscAdv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->vlrAdv = 342.66;

$std->idepgto[0]->infopgto[0]->infoProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->nrProc = '123456';
$std->idepgto[0]->infopgto[0]->infoProcJud->indOrigRec = '1';
$std->idepgto[0]->infopgto[0]->infoProcJud->cnpjOrigRecurso = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoProcJud->desc = 'blça bla bla';

$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->vlrDespCustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->vlrDespAdvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->tpInscAdv = '1';
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->nrInscAdv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->vlrAdv = 342.66;


$std->idepgto[0]->infopgto[0]->infoPgtoExt = new stdclass();
$std->idepgto[0]->infopgto[0]->infoPgtoExt->indNIF = '1';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->nifBenef = '123456';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->frmTribut = '10';

$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt = new stdclass();
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->dscLograd = 'logradouro';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->nrLograd = '100';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->complem = 'SALA 100';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->bairro = 'bairro';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->cidade = 'cidade';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->estado = 'estado';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->codPostal = '1234';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->telef = '12345678901';


$std->ideOpSaude[0] = new stdclass();
$std->ideOpSaude[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->regANS = '123456';
$std->ideOpSaude[0]->vlrSaude = 1893.22;

$std->ideOpSaude[0]->infoReemb[0] = new stdclass();
$std->ideOpSaude[0]->infoReemb[0]->tpInsc = '1';
$std->ideOpSaude[0]->infoReemb[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->infoReemb[0]->vlrReemb = 2000.00;
$std->ideOpSaude[0]->infoReemb[0]->vlrReembAnt = 1000.00;

$std->ideOpSaude[0]->infoDependPl[0] = new stdclass();
$std->ideOpSaude[0]->infoDependPl[0]->cpfDep = '12345678901';
$std->ideOpSaude[0]->infoDependPl[0]->vlrSaude = 543.22;

$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0] = new stdclass();
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->tpInsc = '1';
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->vlrReemb = 222.00;
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->vlrReembAnt = 10.00;

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
    $xml = Event::evtRetPF(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Event::r4010($configJson, $std, $certificate)->toXML();
    //$json = Event::evtRetPF($configJson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
