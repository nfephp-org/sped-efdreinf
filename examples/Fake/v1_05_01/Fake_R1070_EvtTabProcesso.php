<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_1_5_1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_05_01', //versão do layout do evento
    'serviceVersion' => '1_05_01',//versão do webservice
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


//deve existir um evento TabProcesso para cada processo com sentença favorável ao 
//declarante na redução ou suspenção de recolhimento da contribuição
$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->tpproc = 1; //Obrigatório 1 - Administrativo;  2 - Judicial.
$std->nrproc = 'apc123455678'; //Obrigatório  numero do processo
$std->inivalid = '2016-11'; //Obrigatório inico da validade
$std->fimvalid = '2017-02'; //Opcional não deve ser declarado a não ser na ALTERAÇÃO
$std->indautoria = 1; //Obrigatório 1 - Próprio Contribuinte 2 - Outra Entidade ou Empresa
$std->modo = 'ALT'; //Obrigatório INC-inclusao ALT-alteraçaão EXC-exclusao

//indicar somente quando for uma alteração com novo periodo de validade
$std->novavalidade = new \stdClass(); //Opcional
$std->novavalidade->inivalid = '2017-02'; //Obrigatório inicio da validade dessa nova informação
$std->novavalidade->fimvalid = null; //Opcional não deve ser declarado o fim da validade, isso é usado em casos RAROS onde se sabe a data que o evento será modificado

//Informações de Suspensão de Exigibilidade de tributos podem existir de 1 até 50
$std->infosusp[0] = new \stdClass(); //Obrigatório
$std->infosusp[0]->codsusp = '234567890123'; //Opcional Código do Indicativo da Suspensão
$std->infosusp[0]->indsusp = '01'; //Obrigatório Indicativo de suspensão da exigibilidade:
                        //01 - Liminar em Mandado de Segurança;
                        //02 - Depósito Judicial do Montante Integral
                        //03 - Depósito Administrativo do Montante Integral
                        //04 - Antecipação de Tutela;
                        //05 - Liminar em Medida Cautelar;
                        //08 - Sentença em Mandado de Segurança Favorável ao Contribuinte;
                        //09 - Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmada pelo TRF;
                        //10 - Acórdão do TRF Favorável ao Contribuinte;
                        //11 - Acórdão do STJ em Recurso Especial Favorável ao Contribuinte;
                        //12 - Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte;
                        //13 - Sentença 1ª instância não transitada em julgado com efeito suspensivo;
                        //90 - Decisão Definitiva a favor do contribuinte (Transitada em Julgado);
                        //92 - Sem suspensão da exigibilidade

$std->infosusp[0]->dtdecisao = '2017-10-31'; //Obrigatório Data da decisão, sentença ou despacho administrativo
$std->infosusp[0]->inddeposito = 'S'; //Obrigatório Indicativo de Depósito do Montante Integral
                        //S - Sim;
                        //N - Não.

//Informações Complementares do Processo Judicial
$std->dadosprocjud = new \stdClass(); //Opcional
$std->dadosprocjud->ufvara = 'SP'; //Obrigatório UF da vara judicial
$std->dadosprocjud->codmunic = '3548714'; //Obrigatório codigo municipio IBGE
$std->dadosprocjud->idvara = '133'; //Obrigatório Código de Identificação da Vara 


try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtTabProcesso(
        $configJson,
        $std,
        $certificate
    )->toXml();
    
    //$xml = Event::r1070($configJson, $std, $certificate)->toXML();
    //$json = Event::evtTabProcesso($configJson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
