<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_1_4', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_05_01', //versão do layout do evento
    'serviceVersion' => '1_05_01',//versão do webservice
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
$std->modo = 'INC'; //INC-inclusão ALT-alteração ou EXC-exclusao
$std->inivalid = '2017-01';
$std->fimvalid = null; //opcional só é usada na alteração quando os dados apresentados não sejam mais validos 

$std->infocadastro = new \stdClass();
$std->infocadastro->classtrib = '01'; //Tabela 08 - Classificação tributária
$std->infocadastro->indescrituracao = 0; //0 - Empresa NÃO obrigada à ECD; 1 - Empresa obrigada à ECD.
$std->infocadastro->inddesoneracao = 0; //0-não 1-SIM se classtrib = [02 ou 03 ou 99] e optou pela apuração da contribuição previdenciária sobre a receita bruta – CPRB, nos termos dos arts. 7o a 9o da Lei no 12.546, de 2011 indica se tem desoneração com 1 
$std->infocadastro->indacordoisenmulta = 0; //Só pode ser igual a [1] se {classTrib} for igual a [60] .
$std->infocadastro->indsitpj = 0; //Informação obrigatória e exclusiva para pessoa jurídica. 
                                    //0 - Situação Normal;
                                    //1 - Extinção;
                                    //2 - Fusão;
                                    //3 - Cisão;
                                    //4 - Incorporação.

$std->infocadastro->contato = new \stdClass();
$std->infocadastro->contato->nmctt = 'Fulano de Tal'; //Nome do contato no contribuinte relativamente à EFD-Reinf.
$std->infocadastro->contato->cpfctt = '12345678901'; //Preencher com o número do CPF do contato.
$std->infocadastro->contato->fonefixo = '0115555555'; //Informar o número do telefone, com DDD  
$std->infocadastro->contato->fonecel = '1199999999'; //Telefone celular, com DDD min 10 digitos
$std->infocadastro->contato->email = 'fulano@email.com';

$std->infocadastro->softhouse[0] = new \stdClass();
$std->infocadastro->softhouse[0]->cnpjsofthouse = '12345678901234'; //CNPJ da empresa desenvolvedora do software.
$std->infocadastro->softhouse[0]->nmrazao = 'Razao Social';
$std->infocadastro->softhouse[0]->nmcont = 'Fulano de Tal';
$std->infocadastro->softhouse[0]->telefone = '0115555555';
$std->infocadastro->softhouse[0]->email = 'fulano@email.com';

$std->infocadastro->infoefr = new \stdClass();
$std->infocadastro->infoefr->ideefr = 'N';
$std->infocadastro->infoefr->cnpjefr = '12345678901234';

$json = json_encode($std, JSON_PRETTY_PRINT);

echo "<pre>";
print_r($json);
echo "</pre>";
die;

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
