<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
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
    'transmissor' => [
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999999999' //numero do documento
    ]
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 1; //Obrigatório
$std->nrrecibo = '1-12-1234-123456-123456576'; //Obrigatório APENAS em caso de retificação
$std->dtapuracao = '2017-12-01'; //Obrigatório data de realização do espetáculo desportivo
$std->nrinscestab = '12345678901234'; //Obrigatório CNPJ completo do estabelecimento declarante nem sempre é o mesmo do declarante pode ser uma filial

//até 500 boletins são aceitos
$std->boletim[0] = new \stdClass(); //Obrigatório
$std->boletim[0]->nrboletim = '1234'; //Obrigatório numero do boletim
$std->boletim[0]->tpcompeticao = 1; //Obrigatório Tipo de competicao 1 - Oficial; 2 - Não Oficial.
$std->boletim[0]->categevento = 4; //Obrigatório Categoria do evento desportivo 1 - Internacional; 2 - Interestadual; 3 - Estadual; 4 - Local
$std->boletim[0]->moddesportiva = 'Sub 17'; //Obrigatório modalidade do evento desportivo
$std->boletim[0]->nomecompeticao = 'Torneio tornado'; //Obrigatório
$std->boletim[0]->cnpjmandante = '12345678901234'; //Obrigatório
$std->boletim[0]->cnpjvisitante = '12345678901234'; //Opcional CNPJ do clube visitante
$std->boletim[0]->nomevisitante = 'Quebra Toco FC'; //Opcional Nome do clube visitante, obrigatório se não passa o CNPJ do visitante
$std->boletim[0]->pracadesportiva = 'Estadio do outro'; //Obrigatório nome da Praça desportiva do local do evento
$std->boletim[0]->codmunic = '1234567'; //Obrigatório código do municipio
$std->boletim[0]->uf = 'PR'; //Obrigatório UF
$std->boletim[0]->qtdepagantes = 63; //Obrigatório  numero total de pagantes
$std->boletim[0]->qtdenaopagantes = 12; //Obrigatório numero total de não pagantes

//deve haver pelo menos um registro para cada tipo/valor de ingresso vendido
$std->boletim[0]->receitaingressos[0] = new \stdClass(); //Obrigatório
$std->boletim[0]->receitaingressos[0]->tpingresso = 4; //Obrigatório 1 - Arquibancada; 2 - Geral; 3 - Cadeiras; 4 - Camarote;
$std->boletim[0]->receitaingressos[0]->descingr = 'Especial coberto'; //Obrigatório
$std->boletim[0]->receitaingressos[0]->qtdeingrvenda = 68; //Obrigatório
$std->boletim[0]->receitaingressos[0]->qtdeingrvendidos = 63; //Obrigatório
$std->boletim[0]->receitaingressos[0]->qtdeingrdev = 3; //Obrigatório
$std->boletim[0]->receitaingressos[0]->precoindiv = 253.76; //Obrigatório valor individual desse ingresso
$std->boletim[0]->receitaingressos[0]->vlrtotal = 15225.60; //Obrigatório valor total

//até 500 boletins são aceitos
$std->boletim[1] = new \stdClass(); //Obrigatório
$std->boletim[1]->nrboletim = '1234'; //Obrigatório numero do boletim
$std->boletim[1]->tpcompeticao = 1; //Obrigatório Tipo de competicao 1 - Oficial; 2 - Não Oficial.
$std->boletim[1]->categevento = 4; //Obrigatório Categoria do evento desportivo 1 - Internacional; 2 - Interestadual; 3 - Estadual; 4 - Local
$std->boletim[1]->moddesportiva = 'Sub 17'; //Obrigatório modalidade do evento desportivo
$std->boletim[1]->nomecompeticao = 'Torneio tornado'; //Obrigatório
$std->boletim[1]->cnpjmandante = '12345678901234'; //Obrigatório
$std->boletim[1]->cnpjvisitante = '12345678901234'; //Opcional CNPJ do clube visitante
$std->boletim[1]->nomevisitante = 'Quebra Toco FC'; //Opcional Nome do clube visitante, obrigatório se não passa o CNPJ do visitante
$std->boletim[1]->pracadesportiva = 'Estadio do outro'; //Obrigatório nome da Praça desportiva do local do evento
$std->boletim[1]->codmunic = '1234567'; //Obrigatório código do municipio
$std->boletim[1]->uf = 'PR'; //Obrigatório UF
$std->boletim[1]->qtdepagantes = 3814; //Obrigatório  numero total de pagantes
$std->boletim[1]->qtdenaopagantes = 118; //Obrigatório numero total de não pagantes

//deve haver pelo menos um registro para cada tipo/valor de ingresso vendido
$std->boletim[1]->receitaingressos[0] = new \stdClass(); //Obrigatório
$std->boletim[1]->receitaingressos[0]->tpingresso = 1; //Obrigatório 1 - Arquibancada; 2 - Geral; 3 - Cadeiras; 4 - Camarote;
$std->boletim[1]->receitaingressos[0]->descingr = 'Normal'; //Obrigatório
$std->boletim[1]->receitaingressos[0]->qtdeingrvenda = 4068; //Obrigatório
$std->boletim[1]->receitaingressos[0]->qtdeingrvendidos = 3843;//Obrigatório
$std->boletim[1]->receitaingressos[0]->qtdeingrdev = 29;//Obrigatório
$std->boletim[1]->receitaingressos[0]->precoindiv = 56.00;//Obrigatório
$std->boletim[1]->receitaingressos[0]->vlrtotal = 213584.00;//Obrigatório

//podem haver de zero a 999 outras receitas sendo declaradas
$std->boletim[1]->outrasreceitas[0] = new \stdClass(); //opcional
$std->boletim[1]->outrasreceitas[0]->tpreceita = 5; //Obrigatório
$std->boletim[1]->outrasreceitas[0]->vlrreceita = 45800.00; //Obrigatório
$std->boletim[1]->outrasreceitas[0]->descreceita = 'direito de imagem'; //Obrigatório

$std->boletim[1]->outrasreceitas[1] = new \stdClass(); //opcional
$std->boletim[1]->outrasreceitas[1]->tpreceita = 1;  //Obrigatório
$std->boletim[1]->outrasreceitas[1]->vlrreceita = 125000.00;  //Obrigatório
$std->boletim[1]->outrasreceitas[1]->descreceita = 'TV Globo';  //Obrigatório


$std->receitatotal = new \stdClass();
$std->receitatotal->vlrreceitatotal = 399609.60; //Obrigatório
$std->receitatotal->vlrcp = 43957.06; //Obrigatório valor contribuição previdenciaria
$std->receitatotal->vlrcpsusptotal = 21978.53; //Obrigatório valor da suspensão total
$std->receitatotal->vlrreceitaclubes = 377631.07; //Obrigatório
$std->receitatotal->vlrretparc = 0.00;

//obrigatorio caso existam processos aplicaveis ao evento e devem estar declarados do R-1070
$std->receitatotal->infoproc[0] = new \stdClass();  //opcional
$std->receitatotal->infoproc[0]->vlrcpsusp = 21978.53;  //Obrigatório
$std->receitatotal->infoproc[0]->tpproc = 1; //Obrigatório
$std->receitatotal->infoproc[0]->nrproc = '123456789'; //Obrigatório
$std->receitatotal->infoproc[0]->codsusp = '12345678901234'; //Obrigatório

try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtEspDesportivo(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Evento::r3010($json, $std, $certificate)->toXML();
    //$json = Event::evtEspDesportivo($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
