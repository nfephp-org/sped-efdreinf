<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita (homologação)
    'verProc' => '0_1_5', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_05_01', //versão do layout do evento
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
$std->modo = 'ALT'; //Obrigatório INC-inclusao ALT-alteração EXC-exclusao
$std->inivalid = '2017-01'; //Obrigatório inicio da validade data de inicio da OBRIGATORIEDADE da declaração
$std->fimvalid = '2017-02'; //Opcional somente deve ser passado caso seja uma alteração de dados

//indicar somente quando for uma alteração com novo periodo de validade
$std->novavalidade = new \stdClass(); //Opcional
$std->novavalidade->inivalid = '2017-02'; //Obrigatório inicio da validade dessa nova informação
$std->novavalidade->fimvalid = null; //Opcional não deve ser declarado o fim da validade, isso é usado em casos RAROS onde se sabe a data que o evento será modificado

$std->infocadastro = new \stdClass(); //Obrigatório para INC e ALT, será ignorado em EXCLUSÕES então não deve ser passado nesses casos
$std->infocadastro->classtrib = '01'; //ObrigatórioTabela 08 - Classificação tributária
$std->infocadastro->indescrituracao = 0; //Obrigatório 0 - Empresa NÃO obrigada à ECD; 1 - Empresa obrigada à ECD.
$std->infocadastro->inddesoneracao = 0; //Obrigatório 0-não 1-SIM se classtrib = [02 ou 03 ou 99] e optou pela apuração da contribuição previdenciária sobre a receita bruta – CPRB, nos termos dos arts. 7o a 9o da Lei no 12.546, de 2011 indica se tem desoneração com 1 
$std->infocadastro->indacordoisenmulta = 0; //Obrigatório Só pode ser igual a [1] se {classTrib} for igual a [60] .
$std->infocadastro->indsitpj = 0; //Opcional exclusiva para pessoa jurídica, no caso de pessoa fisica não informar. 
                                    //0 - Situação Normal;
                                    //1 - Extinção;
                                    //2 - Fusão;
                                    //3 - Cisão;
                                    //4 - Incorporação.

$std->infocadastro->contato = new \stdClass(); //Obrigatório 
$std->infocadastro->contato->nmctt = 'Fulano de Tal'; //Obrigatório Nome do contato no contribuinte relativamente à EFD-Reinf.
$std->infocadastro->contato->cpfctt = '12345678901'; //Obrigatório Preencher com o número do CPF do contato.
$std->infocadastro->contato->fonefixo = '0115555555'; //Opcional Informar o número do telefone, com DDD  
$std->infocadastro->contato->fonecel = '1199999999'; //Opcional Telefone celular, com DDD min 10 digitos
$std->infocadastro->contato->email = 'fulano@email.com'; //Opcional

//pode haver mais de uma softhouse envolvida
$std->softhouse[0] = new \stdClass(); //Opcional
$std->softhouse[0]->cnpjsofthouse = '12345678901234'; //Obrigatório CNPJ da empresa desenvolvedora do software.
$std->softhouse[0]->nmrazao = 'Razao Social'; //Obrigatório 
$std->softhouse[0]->nmcont = 'Fulano de Tal'; //Obrigatório 
$std->softhouse[0]->telefone = '0115555555'; //Opcional
$std->softhouse[0]->email = 'fulano@email.com'; //Opcional

$std->softhouse[1] = new \stdClass(); //Opcional
$std->softhouse[1]->cnpjsofthouse = '12345678901234'; //Obrigatório CNPJ da empresa desenvolvedora do software.
$std->softhouse[1]->nmrazao = 'Razao Social'; //Obrigatório 
$std->softhouse[1]->nmcont = 'Fulano de Tal'; //Obrigatório 
$std->softhouse[1]->telefone = '0115555555'; //Opcional
$std->softhouse[1]->email = 'fulano@email.com'; //Opcional

//Informações de órgãos públicos estaduais e municipais relativas a Ente Federativo Responsável - EFR
//$std->infoefr = new \stdClass(); //Opcional
//$std->infoefr->ideefr = 'N'; //Obrigatório 
//$std->infoefr->cnpjefr = '12345678901234'; //Opcional

$json = json_encode($std, JSON_PRETTY_PRINT);


try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtInfoContri(
        $configJson,
        $std,
        $certificate
    )->toXml();
    
    //$xml = Event::r1000($configJson, $std, $certificate)->toXML();
    //$json = Event::evtInfoContri($configJson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
