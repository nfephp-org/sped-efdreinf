<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\EFDReinf\Event;
use NFePHP\EFDReinf\Tools;
use NFePHP\EFDReinf\Common\FakePretty;
use NFePHP\EFDReinf\Common\Soap\SoapFake;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
    'verProc' => '0_2_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '2_01_02', //versão do layout do evento
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


try {
    //carrega a classe responsavel por lidar com os certificados
    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //usar a classe Fake para não tentar enviar apenas ver o resultado da chamada
    $soap = new SoapFake();
    //desativa a validação da validade do certificado
    //estamos usando um certificado vencido nesse teste
    $soap->disableCertValidation(true);

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

    $evento = Event::evtInfoContri($configJson, $std);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadSoapClass($soap);

    //executa o envio
    $response = $tools->enviarLoteEventos($tools::EVT_INICIAIS, [$evento]);

    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, '');

} catch (\Exception $e) {
    echo $e->getMessage();
}
