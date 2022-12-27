<?php

namespace NFePHP\EFDReinf;

/**
 * Classe Tools, performs communication with the EFDReinf webservice
 *
 * @category  Library
 * @package   NFePHP\EFDReinf\Tools
 * @copyright Copyright (c) 2017-2021
 * @license   https://www.gnu.org/licenses/lgpl-3.0.txt LGPLv3
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @license   https://opensource.org/licenses/mit-license.php MIT
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */
use NFePHP\Common\Certificate;
use NFePHP\Common\Validator;
use NFePHP\EFDReinf\Common\Rest;
use NFePHP\EFDReinf\Common\Tools as ToolsBase;
use NFePHP\EFDReinf\Common\FactoryInterface;
use NFePHP\EFDReinf\Common\Soap\SoapCurl;
use NFePHP\EFDReinf\Common\Soap\SoapInterface;
use NFePHP\EFDReinf\Exception\ProcessException;
use stdClass;
use NFePHP\EFDReinf\Common\Factory;

class Tools extends ToolsBase
{
    const CONSULTA_CONSOLIDADA = 1;
    const CONSULTA_R1000 = 2;
    const CONSULTA_R1070 = 3;
    const CONSULTA_R2010 = 4;
    const CONSULTA_R2020 = 5;
    const CONSULTA_R2030 = 6;
    const CONSULTA_R2040 = 7;
    const CONSULTA_R2050 = 8;
    const CONSULTA_R2055 = 13;
    const CONSULTA_R2060 = 9;
    const CONSULTA_R2098 = 10;
    const CONSULTA_R2099 = 11;
    const CONSULTA_R3010 = 12;
    const CONSULTA_FECHAMENTO = 14;

    /**
     * @var string
     */
    public $lastRequest;
    /**
     * @var string
     */
    public $lastResponse;
    /**
     * @var SoapInterface
     */
    public $soap;
    /**
     * @var string
     */
    public $namespace = 'http://sped.fazenda.gov.br/';
    /**
     * @var array
     */
    protected $soapnamespaces = [
        'xmlns:soapenv' => "http://schemas.xmlsoap.org/soap/envelope/",
        'xmlns:sped'=> "http://sped.fazenda.gov.br/"
    ];
    /**
     * @var array
     */
    protected $uri = [
        '1' => 'https://reinf.receita.fazenda.gov.br/WsREINF/RecepcaoLoteReinf.svc',
        '2' => 'https://preprodefdreinf.receita.fazenda.gov.br/WsREINF/RecepcaoLoteReinf.svc'

    ];
    /**
     * @var array
     */
    protected $uriconsulta = [
        '1' => 'https://reinf.receita.fazenda.gov.br/WsReinfConsultas/ConsultasReinf.svc',
        '2' => 'https://preprodefdreinf.receita.fazenda.gov.br/WsReinfConsultas/ConsultasReinf.svc'
    ];

    protected $urlloteassincrono = [
        '1' => 'https://reinf.receita.economia.gov.br/recepcao/lotes',
        '2' => 'https://pre-reinf.receita.economia.gov.br/recepcao/lotes',
    ];

    protected $urlconsultaassincrono = [
        '1' => 'https://reinf.receita.economia.gov.br/consulta/lotes',
        '2' => 'https://pre-reinf.receita.economia.gov.br/consulta/lotes'
    ];


    /**
     * @var string
     */
    protected $action;
    /**
     * @var string
     */
    protected $method;

    /**
     * Constructor
     * @param string $config
     * @param Certificate $certificate
     */
    public function __construct($config, Certificate $certificate)
    {
        parent::__construct($config, $certificate);
    }

    /**
     * SOAP communication dependency injection
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }

    /**
     * Run EFD-REINF Query
     * @param $mod
     * @param stdClass|null $std
     * @return string
     */
    public function consultar($mod, stdClass $std = null): string
    {
        if (isset($std)) {
            //converte os nomes das propriedades do stdClass para caixa baixa
            $std = Factory::propertiesToLower($std);
        }
        switch ($mod) {
            case 1:
                $evt = 0;
                $request = $this->consultConsolidadas($std);
                break;
            case 2:
                $evt = 1000;
                $request = $this->consultR1($evt);
                break;
            case 3:
                $evt = 1070;
                $request = $this->consultR1($evt);
                break;
            case 4:
                $evt = 2010;
                $request = $this->consultR2010($evt, $std);
                break;
            case 5:
                $evt = 2020;
                $request = $this->consultR2020($evt, $std);
                break;
            case 6:
                $evt = 2030;
                $request = $this->consultR20($evt, $std);
                break;
            case 7:
                $evt = 2040;
                $request = $this->consultR20($evt, $std);
                break;
            case 8:
                $evt = 2050;
                $request = $this->consultR20($evt, $std);
                break;
            case 9:
                $evt = 2060;
                $request = $this->consultR2060($evt, $std);
                break;
            case 10:
                $evt = 2098;
                $request = $this->consultR209($evt, $std);
                break;
            case 11:
                $evt = 2099;
                $request = $this->consultR209($evt, $std);
                break;
            case 12:
                $evt = 3010;
                $request = $this->consultR3010($evt, $std);
                break;
            case 13:
                $evt = 2055;
                $request = $this->consultR2055($evt, $std);
                break;
            case 14:
                $request = $this->consultFechamento($std);
                break;
            default:
                throw ProcessException::wrongArgument(2003, '');
        }
        $this->lastResponse = $this->sendRequest($request);
        return $this->lastResponse;
    }

    /**
     * Consultation of consolidated information
     * @param stdClass $std
     * @return string
     */
    public function consultConsolidadas(stdClass $std): string
    {
        $properties = [
            'numeroprotocolofechamento' => [
                'required' => true,
                'type' => 'string',
                'regex' => ''
            ],
        ];
        $this->validInputParameters($properties, $std);
        $this->method = "ConsultaInformacoesConsolidadas";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoInscricaoContribuinte>{$this->tpInsc}</sped:tipoInscricaoContribuinte>"
            . "<sped:numeroInscricaoContribuinte>{$this->doc}</sped:numeroInscricaoContribuinte>"
            . "<sped:numeroProtocoloFechamento>{$std->numeroprotocolofechamento}</sped:numeroProtocoloFechamento>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation of Fachamento
     * @param stdClass $std
     * @return string
     */
    public function consultFechamento(stdClass $std): string
    {
        $properties = [
            'numeroprotocolofechamento' => [
                'required' => true,
                'type' => 'string',
                'regex' => ''
            ],
        ];
        $this->validInputParameters($properties, $std);

        $this->method = "ConsultaResultadoFechamento2099";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>"
            . "<sped:numeroProtocoloFechamento>{$std->numeroprotocolofechamento}</sped:numeroProtocoloFechamento>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R1000 and R1070
     * @param integer $evt
     * @return string
     */
    protected function consultR1($evt): string
    {
        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R2010
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR2010($evt, $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
            ],
            'tpinscestab' => [
                'required' => true,
                'type' => 'integer',
                'min' => 1,
                'max' => 4
            ],
            'nrinscestab' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
            'cnpjprestador' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);

        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .=  "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "<sped:tpInscEstab>{$std->tpinscestab}</sped:tpInscEstab>"
            . "<sped:nrInscEstab>"
                . str_pad($std->nrinscestab, 14, '0', STR_PAD_LEFT)
                . "</sped:nrInscEstab>"
            . "<sped:cnpjPrestador>"
                . str_pad($std->cnpjprestador, 14, '0', STR_PAD_LEFT)
                . "</sped:cnpjPrestador>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R2020
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR2020($evt, stdClass $std)
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
             ],
            'nrinscestabprest' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
            'tpinsctomador' => [
                'required' => true,
                'type' => 'integer',
                'min' => 1,
                'max' => 4
            ],
            'nrinsctomador' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);

        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "<sped:nrInscEstabPrest>{$std->nrinscestabprest}</sped:nrInscEstabPrest>"
            . "<sped:tpInscTomador>{$std->tpinsctomador}</sped:tpInscTomador>"
            . "<sped:nrInscTomador>"
                . str_pad($std->nrinsctomador, 14, '0', STR_PAD_LEFT)
                . "</sped:nrInscTomador>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R2030, R2040, R2050
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR20(int $evt, stdClass $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
            ],
            'nrinscestab' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);
        if ($this->tpInsc !== 1) {
            throw new \InvalidArgumentException(
                "Somente com CNPJ essa consulta pode ser realizada."
                . " Seu config indica um CPF."
            );
        }
        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "<sped:nrInscEstab>"
                . str_pad($std->nrinscestab, 14, '0', STR_PAD_LEFT)
                . "</sped:nrInscEstab>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R2055
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR2055(int $evt, stdClass $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
            ],
            'nrinscestab' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);
        if ($this->tpInsc !== 1) {
            throw new \InvalidArgumentException(
                "Somente com CNPJ essa consulta pode ser realizada."
                . " Seu config indica um CPF."
            );
        }
        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "<sped:tpInscAdq>{$std->tpInscAdq}</sped:tpInscAdq>"
            . "<sped:nrInscAdq>{$std->nrInscAdq}</sped:nrInscAdq>"
            . "<sped:tpInscProd>{$std->tpInscProd}</sped:tpInscProd>"
            . "<sped:nrInscProd>{$std->nrInscProd}</sped:nrInscProd>"
            . "</sped:{$this->method}>";
        return $request;
    }


    /**
     * Consultation R2060
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR2060(int $evt, stdClass $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
            ],
            'nrinscestabprest' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
            'tpinscestab' => [
                'required' => true,
                'type' => 'integer',
                'min' => 1,
                'max' => 4
            ],
            'nrinscestab' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);

        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "<sped:tpInscEstab>{$std->tpinscestab}</sped:tpInscEstab>"
            . "<sped:nrInscEstab>"
                . str_pad($std->nrinscestab, 14, '0', STR_PAD_LEFT)
                . "</sped:nrInscEstab>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R2098 and R2099
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR209(int $evt, stdClass $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string',"null"],
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])$'
            ],
        ];
        $this->validInputParameters($properties, $std);

        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>";
        if (!empty($std->perapur)) {
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
        }
        $request .= "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Consultation R3010
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR3010(int $evt, stdClass $std): string
    {
        $properties = [
            'dtapur' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-](0?[1-9]|1[0-2])[-](0?[1-9]|[1-2][0-9]|3[0-1])$'
            ],
            'nrinscestabelecimento' => [
                'required' => true,
                'type' => 'string',
                'regex' => '^[0-9]{11,14}$'
            ],
        ];
        $this->validInputParameters($properties, $std);
        if ($this->tpInsc !== 1) {
            throw new \InvalidArgumentException(
                "Somente com CNPJ essa consulta pode ser realizada."
                . " Seu config indica um CPF."
            );
        }
        $this->method = "ConsultaReciboEvento{$evt}";
        $this->action = "{$this->namespace}ConsultasReinf/{$this->method}";
        $request = "<sped:{$this->method}>"
            . "<sped:tipoEvento>{$evt}</sped:tipoEvento>"
            . "<sped:tpInsc>{$this->tpInsc}</sped:tpInsc>"
            . "<sped:nrInsc>{$this->doc}</sped:nrInsc>"
            . "<sped:dtApur>{$std->dtapur}</sped:dtApur>"
            . "<sped:nrInscEstabelecimento>"
                . str_pad($std->nrinscestabelecimento, 14, '0', STR_PAD_LEFT)
                . "</sped:nrInscEstabelecimento>"
            . "</sped:{$this->method}>";
        return $request;
    }

    /**
     * Send batch of events
     * @param  integer $grupo
     * @param array $eventos
     * @return string
     */
    public function enviarLoteEventos(int $grupo, array $eventos = []): string
    {
        if (empty($eventos)) {
            return '';
        }
        //check number of events
        $nEvt = count($eventos);
        if ($nEvt > 100) {
            throw ProcessException::wrongArgument(2000, $nEvt);
        }
        $this->method = "ReceberLoteEventos";
        $this->action = "{$this->namespace}RecepcaoLoteReinf/{$this->method}";
        $xml = "";
        foreach ($eventos as $evt) {
            if (!is_a($evt, '\NFePHP\EFDReinf\Common\FactoryInterface')) {
                throw ProcessException::wrongArgument(2002, '');
            }
            //verifica se o evento pertence ao grupo indicado
            if (! in_array($evt->alias(), $this->grupos[$grupo])) {
                throw new \RuntimeException(
                    'O evento ' . $evt->alias() . ' não pertence a este grupo [ '
                    . $this->eventGroup[$grupo] . ' ].'
                );
            }
            $this->checkCertificate($evt);
            $xml .= "<evento id=\"".$evt->getId()."\">";
            $xml .= $evt->toXML();
            $xml .= "</evento>";
        }
        //build request
        $request = "<Reinf xmlns=\"http://www.reinf.esocial.gov.br/schemas/envioLoteEventos/v"
            . $this->serviceVersion."\" >"
            . "<loteEventos>"
            . $xml
            . "</loteEventos>"
            . "</Reinf>";
        //validate requisition with XSD
        $xsd = $this->path
            . "schemes/comunicacao/v$this->serviceVersion/"
            . $this->serviceXsd['EnvioLoteEventos']['name'];
        Validator::isValid($request, $xsd);
        //build soap body
        $body = "<sped:ReceberLoteEventos>"
            . "<sped:loteEventos>"
            . $request
            . "</sped:loteEventos>"
            . "</sped:ReceberLoteEventos>";
        $this->lastResponse = $this->sendRequest($body);
        return $this->lastResponse;
    }

    /**
     * Envia lote Assincrono por API REST
     * @param int $grupo
     * @param array $eventos
     * @return string
     */
    public function enviaLoteAssincrono(int $grupo, array $eventos = []): string
    {
        if (empty($eventos)) {
            return '';
        }
        //check number of events
        $nEvt = count($eventos);
        if ($nEvt > 100) {
            throw ProcessException::wrongArgument(2000, $nEvt);
        }
        $xml = '';
        foreach ($eventos as $evt) {
            if (!is_a($evt, '\NFePHP\EFDReinf\Common\FactoryInterface')) {
                throw ProcessException::wrongArgument(2002, '');
            }
            //verifica se o evento pertence ao grupo indicado
            if (! in_array($evt->alias(), $this->grupos[$grupo])) {
                throw new \RuntimeException(
                    'O evento ' . $evt->alias() . ' não pertence a este grupo [ '
                    . $this->eventGroup[$grupo] . ' ].'
                );
            }
            $this->checkCertificate($evt);
            $xml .= "<evento id=\"".$evt->getId()."\">";
            $xml .= $evt->toXML();
            $xml .= "</evento>";
        }
        $content = "<Reinf xmlns=\"http://www.reinf.esocial.gov.br/schemas/envioLoteEventosAssincrono/v1_00_00\">"
            . "<envioLoteEventos>"
            . "<ideContribuinte>"
            . "<tpInsc>{$this->tpInsc}</tpInsc>"
            . "<nrInsc>{$this->nrInsc}</nrInsc>"
            . "</ideContribuinte>"
            . "<eventos>"
            . $xml
            . "</eventos>"
            . "</envioLoteEventos>"
            . "</Reinf>";

        $url = $this->urlloteassincrono[$this->tpAmb];
        $rest = new Rest($this->certificate);
        $this->lastResponse = $rest->sendApi('POST', $url, $content);
        return $this->lastResponse;
    }

    /**
     * @param string $protocolo
     * @return string
     */
    public function consultaLoteAssincrono(string $protocolo): string
    {
        $url = $this->urlconsultaassincrono[$this->tpAmb] . "/$protocolo";
        $rest = new Rest($this->certificate);
        $this->lastResponse = $rest->sendApi('GET', $url, '');
        return $this->lastResponse;
    }

    /**
     * Send request to webservice
     * @param string $request
     * @return string
     */
    protected function sendRequest(string $request): string
    {
        if (empty($this->soap)) {
            $this->soap = new SoapCurl($this->certificate);
        }
        $envelope = "<soapenv:Envelope ";
        foreach ($this->soapnamespaces as $key => $xmlns) {
            $envelope .= "$key = \"$xmlns\" ";
        }
        $envelope .= ">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>"
            . $request
            . "</soapenv:Body>"
            . "</soapenv:Envelope>";

        $msgSize = strlen($envelope);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$this->action\"",
            "Content-length: $msgSize"
        ];
        if ($this->method === 'ReceberLoteEventos') {
            $url = $this->uri[$this->tpAmb];
        } else {
            $url = $this->uriconsulta[$this->tpAmb];
        }
        $this->lastRequest = $envelope;
        return (string) $this->soap->send(
            $this->method,
            $url,
            $this->action,
            $envelope,
            $parameters
        );
    }

    /**
     * Verify the availability of a digital certificate.
     * If available, place it where it is needed
     * @param FactoryInterface $evento
     * @return void
     */
    protected function checkCertificate(FactoryInterface $evento)
    {
        //try to get certificate from event
        $certificate = $evento->getCertificate();
        if (empty($certificate)) {
            $evento->setCertificate($this->certificate);
        }
    }

    /**
     * Valid input parameters
     * @param array $properties
     * @param stdClass $std
     * @return void
     * @throws \Exception
     */
    protected function validInputParameters(array $properties, stdClass $std)
    {
        foreach ($properties as $key => $rules) {
            $r = json_decode(json_encode($rules));
            if ($r->required) {
                if (!isset($std->$key)) {
                    throw new \Exception("$key não foi passado como parâmetro e é obrigatório.");
                }
                $value = $std->$key;
                if ($r->type === 'integer') {
                    if ($value < $r->min || $value > $r->max) {
                        throw new \Exception("$key contêm um valor invalido [$value].");
                    }
                }
                if ($r->type === 'string') {
                    if (!preg_match("/{$r->regex}/", $value)) {
                        throw new \Exception("$key contêm um valor invalido [$value].");
                    }
                }
            }
        }
    }
}
