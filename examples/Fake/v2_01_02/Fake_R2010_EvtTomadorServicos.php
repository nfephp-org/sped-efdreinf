<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\EFDReinf\Event;
use NFePHP\Common\Certificate;
use JsonSchema\Validator;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - Produção restrita
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
    'transmissor' => [
        'tpInsc' => 1,  //1-CNPJ, 2-CPF
        'nrInsc' => '99999999999999' //numero do documento
    ]
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 2; //Obrigatório 1-para arquivo original ou 2-para arquivo de retificação
$std->nrrecibo = '1-00-1234-1234-1234556789012345'; //Opcional, mas deve ser informado quando for uma retificacao
$std->perapur = '2017-11';
$std->tpinscestab = "1"; //Obrigatório tipo de inscrição do estabelecimento contratante dos serviços:
            //1 - CNPJ;
            //4 - CNO - Cadastro Nacional de Obras.
$std->nrinscestab = '12345678901234'; //Obrigatório numero de inscrição do estabelecimento contratante dos serviços
$std->indobra = 0; //Obrigatório Indicativo de prestação de serviços em obra de construção civil:
            //0 - Não é obra de construção civil ou não está sujeita a matrícula de obra;
            //1 - É obra de construção civil, modalidade empreitada total;
            //2 - É obra de construção civil, modalidade empreitada parcial.

$std->cnpjprestador = '12345678901234'; //Obrigatório CNPJ do prestador de serviços
$std->vlrtotalbruto = 200.00; //Obrigatório Valor bruto da(s) nota(s) fiscal(is)
                // SOMA de $std->nfs[$n]->vlrbruto

$std->vlrtotalbaseret = 200.00; //Obrigatório Valor soma da base de cálculo da retenção da contribuição previdenciária das notas fiscais emitidas para o contratante.
                // SOMA $std->nfs[$n]->infotpserv[$i]->vlrbaseret

$std->vlrtotalretprinc = 22.00; //Obrigatório  Soma do valor da retenção sobre o valor das notas fiscais de serviço emitidas para o contratante
                // SOMA ($std->nfs[$n]->infotpserv[$i]->vlrretencao - $std->nfs[$n]->infotpserv[$i]->vlrretsub)

$std->vlrtotalretadic = 0.00; //Opcional Valor da soma do valor do adicional de retenção sobre os valores das notas fiscai
               // SOMA $std->nfs[$n]->infotpserv[$i]->vlradicional

$std->vlrtotalnretprinc = 0.00; //Opcional Valor da retenção principal que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial
               // SOMA $std->nfs[$n]->infotpserv[$i]->vlrnretprinc

$std->vlrtotalnretadic = 0.00; //Opcional Valor da retenção adicional que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial.
               // SOMA $std->nfs[$n]->infotpserv[$i]->vlrnretadic

$std->indcprb = 0;  //Obrigatório Indicativo se o prestador é contribuinte da contribuição previdenciária sobre a
                    //receita bruta (CPRB), a qual reduz a alíquota de 11% para 3,5% na retenção
                    //de contribuição previdenciária:
            //0 - Não é contribuinte da CPRB - retenção de 11%;
            //1 - É contribuinte da CPRB - retenção de 3,5%.

//dados das NFSe ou RPS deve haver pelo menos uma
$std->nfs[0] = new \stdClass();
$std->nfs[0]->serie = '001'; //Obrigatorio número de série da nota fiscal/fatura ou do Recibo Provisório de Serviço - RPS ou de outro documento fiscal válido.
                  // Preencher com 0 (zero) caso não exista número de série
$std->nfs[0]->numdocto = '265465'; //Obrigatório Número da nota fiscal/fatura ou outro documento fiscal válido, como Recibo Provisório de Serviço - RPS, CT-e, entre outros.
$std->nfs[0]->dtemissaonf = '2017-01-22'; //Obrigatório Data de emissão da nota fiscal/fatura ou do Recibo Provisório de Serviço -
                                            //RPS ou de outro documento fiscal válido.
                    //Validação: O mês/ano informado deve ser igual ao mês/ano indicado no registro de abertura do arquivo.
$std->nfs[0]->vlrbruto = 200.00; //Obrigatório valor bruto da nota fiscal ou do Recibo Provisório de Serviço - RPS
$std->nfs[0]->obs = 'observacao pode ser nula'; //Opcional Observações.

//detalhes dos itens da NF de 1 até 9 itens por NF
$std->nfs[0]->infotpserv[0] = new \stdClass(); //Obrigatório
$std->nfs[0]->infotpserv[0]->tpservico = '123456789'; //Obrigatório tipo de serviço, conforme Tabela 06
$std->nfs[0]->infotpserv[0]->vlrbaseret = 200.00; //Obrigatório Valor da base de cálculo da retenção da contribuição previdenciária.
$std->nfs[0]->infotpserv[0]->vlrretencao = 22.00; //Obrigatório valor da retenção apurada de acordo com o que determina a
                                                  //legislação vigente relativa aos serviços contidos na nota fiscal/fatura.
                    //Se {indCPRB}= [0] preencher com valor correspondente a 11% de {vlrBaseRet}.
                    //Se {indCPRB}= [1] preencher com valor correspondente a 3,5% de {vlrBaseRet}.
                    //NOTA: não pode ser maior que 11% de {vlrBaseRet}
$std->nfs[0]->infotpserv[0]->vlrretsub = null; //Opcional valor da retenção destacada na nota fiscal relativo aos serviços
                                                //subcontratados, se houver, desde que todos os documentos envolvidos se
                                                //refiram à mesma competência e ao mesmo serviço, conforme disciplina a legislação.
                        //NOTA: Não pode ser superior ao valor informado no campo {vlrRetencao}
$std->nfs[0]->infotpserv[0]->vlrnretprinc = null; //Opcional Valor da retenção principal que deixou de ser efetuada pelo contratante ou que
                                                     // foi depositada em juízo em decorrência de decisão judicial/administrativa
                        //NOTA: Não pode ser superior a ((11% de {vlrBaseRet} se {indCPRB} = [0] ou a 3,5% de {vlrBaseRet} se {indCPRB} = [1]) - {vlrRetSub})
$std->nfs[0]->infotpserv[0]->vlrservicos15 = null; //Opcional
$std->nfs[0]->infotpserv[0]->vlrservicos20 = null; //Opcional
$std->nfs[0]->infotpserv[0]->vlrservicos25 = null; //Opcional
$std->nfs[0]->infotpserv[0]->vlradicional = null; //Opcional
$std->nfs[0]->infotpserv[0]->vlrnretadic = null; //Opcional

//Informações de processos relacionados a não retenção de contribuição previdenciária.
//podem ocorrer de NENHUM até 50 processos
/*
$std->infoprocretpr[0] = new \stdClass(); //Opcional
$std->infoprocretpr[0]->tpprocretprinc = 1; //Obrigatório
$std->infoprocretpr[0]->nrprocretprinc = 'ZYX987'; //Obrigatório
$std->infoprocretpr[0]->codsuspprinc = '12345678901234'; //Opcional
$std->infoprocretpr[0]->valorprinc = 200.98; //Obrigatório
*/
//Informações de processos relacionados a não retenção de contribuição previdenciária adicional
/*
$std->infoprocretad[0] = new \stdClass(); //Opcional
$std->infoprocretad[0]->tpprocretadic = 1; //Obrigatório
$std->infoprocretad[0]->nrprocretadic = 'ACB21'; //Obrigatório
$std->infoprocretad[0]->codsuspadic = '12345678901234'; //Opcional
$std->infoprocretad[0]->valoradic = 1000.23; //Obrigatório
*/
try {

    //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtServTom(
        $configJson,
        $std,
        $certificate
    )->toXml();

    //$xml = Evento::r2010($json, $std, $certificate)->toXML();
    //$json = Event::evtServTom($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
