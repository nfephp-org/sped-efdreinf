<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\EFDReinf\Tools;
use NFePHP\EFDReinf\Common\FakePretty;
use NFePHP\EFDReinf\Common\Restful\RestFake;
use NFePHP\EFDReinf\Event;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_1_1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_01', //versão do layout do evento
    'serviceVersion' => '1_00_00',//versão do webservice
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

try {
    //carrega a classe responsavel por lidar com os certificados
    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //usar a classe Fake para não tentar enviar apenas ver o resultado da chamada
    $rest = new RestFake();
    //desativa a validação da validade do certificado
    //estamos usando um certificado vencido nesse teste
    $rest->disableCertValidation(true);
    $rest->loadCertificate($certificate);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadRestClass($rest);

    //cria o evento
    $std = new \stdClass();
    //$std->sequencial = 1;
    $std->modo = 'INC';
    $std->inivalid = '2017-01';
    //$std->fimvalid = '2017-12';

    $std->infocadastro = new \stdClass();
    $std->infocadastro->classtrib = '01';
    $std->infocadastro->indescrituracao = 0;
    $std->infocadastro->inddesoneracao = 0;
    $std->infocadastro->indacordoisenmulta = 0;
    $std->infocadastro->indsitpj = 0;

    $std->infocadastro->contato = new \stdClass();
    $std->infocadastro->contato->nmctt = 'Fulano de Tal';
    $std->infocadastro->contato->cpfctt = '12345678901';
    $std->infocadastro->contato->fonefixo = '115555555';
    $std->infocadastro->contato->fonecel = '1199999999';
    $std->infocadastro->contato->email = 'fulano@email.com';

    $std->infocadastro->softhouse[0] = new \stdClass();
    $std->infocadastro->softhouse[0]->cnpjsofthouse = '12345678901234';
    $std->infocadastro->softhouse[0]->nmrazao = 'Razao Social';
    $std->infocadastro->softhouse[0]->nmcont = 'Fulano de Tal';
    $std->infocadastro->softhouse[0]->telefone = '115555555';
    $std->infocadastro->softhouse[0]->email = 'fulano@email.com';

    $std->infocadastro->infoefr = new \stdClass();
    $std->infocadastro->infoefr->ideefr = 'N';
    $std->infocadastro->infoefr->cnpjefr = '12345678901234';

    $xml = Event::evtInfoContri(
        $configJson,
        $std,
        $certificate
    )->toXml();

    $axml[] = $xml;

    /*
    $std = new \stdClass();
    //$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
    $std->tpevento = 'R-2010'; //R-2010 a R-2070 e R-3010
    //$std->nrrecevt = '121212-23-1245-55555-125498787888858552';
    $std->nrrecevt = '30795-08-2010-1805-30795';
    $std->perapur = '2017-11';

    //cria o evento e retorna o XML assinado
    $xml = Event::evtExclusao(
        $configJson,
        $std,
        $certificate
    )->toXml();

    $axml[] = $xml;
    */
    $response = $tools->enviaLoteXmlAssincrono($tools::EVT_INICIAIS, $axml);

    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, '');

} catch (\Exception $e) {
    echo $e->getMessage();
}
