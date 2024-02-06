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
use NFePHP\Common\Strings;
use NFePHP\Common\Validator;
use NFePHP\EFDReinf\Common\Factory;
use NFePHP\EFDReinf\Common\FactoryInterface;
use NFePHP\EFDReinf\Common\Restful\Rest;
use NFePHP\EFDReinf\Common\Soap\SoapCurl;
use NFePHP\EFDReinf\Common\Soap\SoapInterface;
use NFePHP\EFDReinf\Common\Tools as ToolsBase;
use NFePHP\EFDReinf\Exception\ProcessException;
use NFePHP\EFDReinf\Common\Restful\RestInterface;
use stdClass;

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
    const CONSULTA_R4099 = 15;
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
     * @var RestInterface
     */
    public $restclass;
    /**
     * @var string
     */
    public $namespace = 'http://sped.fazenda.gov.br/';
    /**
     * @var array
     */
    protected $soapnamespaces = [
        'xmlns:soapenv' => "http://schemas.xmlsoap.org/soap/envelope/",
        'xmlns:sped' => "http://sped.fazenda.gov.br/"
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

    /**
     * @var string[]
     */
    protected $urlloteassincrono = [
        '1' => 'https://reinf.receita.economia.gov.br/recepcao/lotes',
        '2' => 'https://pre-reinf.receita.economia.gov.br/recepcao/lotes',
    ];

    /**
     * @var string[]
     */
    protected $urlconsultaassincrono = [
        '1' => 'https://reinf.receita.economia.gov.br/consulta/lotes',
        '2' => 'https://pre-reinf.receita.economia.gov.br/consulta/lotes'
    ];

    /**
     * @var string[]
     */
    protected $urlconsultaeventoassincrono = [
        '1' => 'https://reinf.receita.economia.gov.br/consulta/reciboevento',
        '2' => 'https://pre-reinf.receita.economia.gov.br/consulta/reciboevento'
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
     * @var string
     */
    protected $xsdassincrono;

    /**
     * Constructor
     * @param string $config
     * @param Certificate $certificate
     */
    public function __construct($config, Certificate $certificate)
    {
        parent::__construct($config, $certificate);
        $this->xsdassincrono = __DIR__ . '/../schemes/v2_01_01/envioLoteEventosAssincrono-v1_00_00.xsd';
    }

    /**
     * SOAP communication dependency injection
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }

    public function loadRestClass(RestInterface $rest)
    {
        $this->restclass = $rest;
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
            case 15:
                $evt = 4099;
                $request = $this->consultR409($evt, $std);
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
                'type' => ['string', "null"],
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
            $request .= "<sped:perApur>{$std->perapur}</sped:perApur>";
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
                'type' => ['string', "null"],
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
                'type' => ['string', "null"],
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
                'type' => ['string', "null"],
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
                'type' => ['string', "null"],
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
                'type' => ['string', "null"],
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
     * Consultation R4099
     * @param integer $evt
     * @param stdClass $std
     * @return string
     */
    protected function consultR409(int $evt, stdClass $std): string
    {
        $properties = [
            'perapur' => [
                'required' => false,
                'type' => ['string', "null"],
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
     * @param integer $grupo
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
            if (!in_array($evt->alias(), $this->grupos[$grupo])) {
                throw new \RuntimeException(
                    'O evento ' . $evt->alias() . ' não pertence a este grupo [ '
                    . $this->eventGroup[$grupo] . ' ].'
                );
            }
            $this->checkCertificate($evt);
            $xml .= "<evento id=\"" . $evt->getId() . "\">";
            $xml .= $evt->toXML();
            $xml .= "</evento>";
        }
        //build request
        $request = "<Reinf xmlns=\"http://www.reinf.esocial.gov.br/schemas/envioLoteEventos/v"
            . $this->serviceVersion . "\" >"
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
        if ($nEvt > 50) {
            throw ProcessException::wrongArgument(2000, $nEvt);
        }
        $xml = '';
        foreach ($eventos as $evt) {
            if (!is_a($evt, '\NFePHP\EFDReinf\Common\FactoryInterface')) {
                throw ProcessException::wrongArgument(2002, '');
            }
            //verifica se o evento pertence ao grupo indicado
            if (!in_array($evt->alias(), $this->grupos[$grupo])) {
                throw new \RuntimeException(
                    'O evento ' . $evt->alias() . ' não pertence a este grupo [ '
                    . $this->eventGroup[$grupo] . ' ].'
                );
            }
            $this->checkCertificate($evt);
            $xml .= "<evento Id=\"" . $evt->getId() . "\">";
            $xml .= $evt->toXML();
            $xml .= "</evento>";
        }
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
            . "<Reinf xmlns=\"http://www.reinf.esocial.gov.br/schemas/envioLoteEventosAssincrono/v"
            . $this->serviceVersion . "\">"
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
        //validate requisition with XSD
        $xsd = $this->path
            . "schemes/comunicacao/v$this->serviceVersion/"
            . $this->serviceXsd['EnvioLoteEventos']['name'];
        Validator::isValid($content, $xsd);
        $url = $this->urlloteassincrono[$this->tpAmb];
        $this->lastResponse = $this->sendApi('POST', $url, $content);
        return $this->lastResponse;
    }

    /**
     *
     * @param string $xml
     * @return mixed
     */
    protected function getIdFromXml(string $xml)
    {
        $possibles = [
            'evtInfoContri' => 1,
            'evtTabLig' => 1,
            'evtTabProcesso' => 1,
            'evtServTom' => 2,
            'evtServPrest' => 2,
            'evtAssocDespRec' => 2,
            'evtAssocDespRep' => 2,
            'evtComProd' => 2,
            'evtAqProd' => 2,
            'evtCPRB' => 2,
            'evtReabreEvPer' => 2,
            'evtFechaEvPer' => 4,
            'evtEspDesportivo' => 3,
            'evtRetPF' => 2,
            'evtRetPJ' => 2,
            'evtBenefNId' => 2,
            'evtRetRec' => 2,
            'evtFech' => 4,
            'evtExclusao' => 3
        ];
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        $keys = array_keys($possibles);
        $id = null;
        $grupo = null;
        foreach ($keys as $tagname) {
            $id = null;
            if (!empty($dom->getElementsByTagName($tagname)->item(0))) {
                $tag = $dom->getElementsByTagName($tagname)->item(0);
                $id = $tag->getAttribute('id');
                $grupo = $possibles[$tagname];
            }
            if (!empty($id)) {
                break;
            }
        }
        return ['id' => $id, 'grupo' => $grupo];
    }

    /**
     * Envio Assincrono por API REST de lote de eventos em XML assindado
     * @param int $grupo
     * @param array $eventos
     * @return string
     */
    public function enviaLoteXmlAssincrono(int $grupo, array $eventos = []): string
    {
        if (empty($eventos)) {
            return '';
        }
        //check number of events
        $nEvt = count($eventos);
        if ($nEvt > 50) {
            throw ProcessException::wrongArgument(2000, $nEvt);
        }
        $xml = '';
        $grp = null;
        foreach ($eventos as $evt) {
            $resp = $this->getIdFromXml($evt);
            if (empty($resp['id'])) {
                throw new \RuntimeException(
                    'Falha na localização do ID do evento.'
                );
            }
            if (empty($grp)) {
                $grp = $resp['grupo'];
            }
            if ($grp !== $resp['grupo']) {
                throw new \RuntimeException('Devem ser enviados em um lote apenas eventos '
                    . 'pertencentes ao mesmo grupo');
            }
            if ($grp !== $grupo) {
                throw new \RuntimeException('O grupo correto deve ser declarado e não pode diferir'
                    . 'do grupo dos eventos');
            }
            $id = $resp['id'];
            $xml .= "<evento Id=\"$id\">";
            $xml .= $evt;
            $xml .= "</evento>";
        }
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
            . "<Reinf xmlns=\"http://www.reinf.esocial.gov.br/schemas/envioLoteEventosAssincrono/v"
            . $this->serviceVersion . "\">"
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
        $xsd = $this->path
            . "schemes/comunicacao/v$this->serviceVersion/"
            . $this->serviceXsd['EnvioLoteEventos']['name'];
        Validator::isValid($content, $xsd);
        $url = $this->urlloteassincrono[$this->tpAmb];
        $this->lastResponse = $this->sendApi('POST', $url, $content);
        return $this->lastResponse;
    }

    /**
     * @param string $protocolo
     * @return string
     */
    public function consultaLoteAssincrono(string $protocolo): string
    {
        $url = $this->urlconsultaassincrono[$this->tpAmb] . "/$protocolo";
        $this->lastResponse = $this->sendApi('GET', $url, '');
        return $this->lastResponse;
    }

    /**
     * @param stdClass $std
     * @return bool|string
     */
    public function consultarEventoAssincono(stdClass $std): string
    {
        $possibleevents = [
            '1000',
            '1050',
            '1070',
            '2010',
            '2020',
            '2030',
            '2040',
            '2050',
            '2055',
            '2060',
            '2098',
            '2099',
            '3010',
            '4010',
            '4020',
            '4040',
            '4080',
            '4099'
        ];
        if (empty($std->evento)) {
            throw new \RuntimeException("Deve ser passada a variável evento.");
        }
        $evento = (string) preg_replace("/[^0-9]/", "", $std->evento);
        if (!in_array($evento, $possibleevents)) {
            throw new \RuntimeException("Esse evento [$evento] é desconhecido para uma consulta");
        }
        $std->evento = $evento;
        $url = $this->buildUrlConsultaAssincona($std);
        $this->lastResponse = $this->sendApi('GET', $url, '');
        return $this->lastResponse;
    }

    /**
     * @param string $evento
     * @param stdClass $std
     * @return string
     */
    private function buildUrlConsultaAssincona(stdClass $std): string
    {
        $evento = (string) $std->evento;
        $baseurl = $this->urlconsultaeventoassincrono[$this->tpAmb];
        $proc = "urlR$evento";
        $std->baseurl = $baseurl;
        $std->tpinsc = $this->tpInsc;
        $std->nrinsc = $this->nrInsc;
        return self::$proc($std);
    }

    /**
     * GET R1000 Assincrono
     * @param stdClass $std
     * @return string
     */
    private static function urlR1000(stdClass $std): string
    {
        return self::urlbase($std);
    }

    /**
     * GET R1050 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR1050(stdClass $std): string
    {
        return self::urlbase($std);
    }

    /**
     * GET R1070 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR1070(stdClass $std): string
    {
        return self::urlbase($std);
    }

    /**
     * GET R2010 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2010(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
            'cnpjprestador' => '/^[0-9]{14}$/'
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2020 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2020(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'nrinscestabprest' => '/^[0-9]{14}$/',
            'tpinsctomador' => '/^(1|4)$/',
            'nrinsctomador' => '/^[0-9]{12}|[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2030 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2030(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'nrinscestab' => '/^[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2040 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2040(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'nrinscestab' => '/^[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2050 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2050(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'nrinscestab' => '/^[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2055 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2055(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscadq' => '/^(1|3)$/',
            'nrinscadq' => '/^[0-9]{14}$/',
            'tpinscprod' => '/^(1|2)$/',
            'nrinscprod' => '/^[0-9]{11}|[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2060 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2060(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2098 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2098(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R2099 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR2099(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R3010 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR3010(stdClass $std): string
    {
        $required = [
            'dtapur' => '/^20([1-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/',
            'nrinscestabelecimento' => '/^[0-9]{14}/'
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R4010 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR4010(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
        ];
        $std = self::propertiesToLower($std);
        if (!empty($std->cpfbenef)) {
            $required['cpfbenef'] = '/^[0-9]{11}$/';
        }
        $val = self::validateConsultData($required, $std);
        if (!$val['status']) {
            $message = implode("\n", $val['errors']);
            throw new \RuntimeException('Campos foram passados com erro para a consulta. ' . $message);
        }
        $base = self::urlbase($std);
        if (!empty($std->cpfbenef)) {
            return "{$base}/{$std->perapur}/{$std->tpinscestab}/{$std->nrinscestab}/{$std->cpfbenef}";
        }
        return "{$std->baseurl}/R4010/semCpfBeneficiario/{$std->tpinsc}/{$std->nrinsc}/{$std->perapur}"
            . "/{$std->tpinscestab}/{$std->nrinscestab}";
    }

    /**
     * GET R4020 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR4020(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
        ];
        $std = self::propertiesToLower($std);
        if (!empty($std->cpfbenef)) {
            $required['cpfbenef'] = '/^[0-9]{11}$/';
        }
        $val = self::validateConsultData($required, $std);
        if (!$val['status']) {
            $message = implode("\n", $val['errors']);
            throw new \RuntimeException('Campos foram passados com erro para a consulta. ' . $message);
        }
        $base = self::urlbase($std);
        if (!empty($std->cpfbenef)) {
            return "{$base}/{$std->perapur}/{$std->tpinscestab}/{$std->nrinscestab}/{$std->cpfbenef}";
        }
        return "{$std->baseurl}/R4020/semCpfBeneficiario/{$std->tpinsc}/{$std->nrinsc}/{$std->perapur}"
            . "/{$std->tpinscestab}/{$std->nrinscestab}";
    }

    /**
     * GET R4040 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR4040(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R4080 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR4080(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
            'tpinscestab' => '/^(1|4)$/',
            'nrinscestab' => '/^[0-9]{12}|[0-9]{14}$/',
            'cnpjfonte' => '/^[0-9]{14}$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * GET R4099 Assincrona
     * @param stdClass $std
     * @return string
     */
    private static function urlR4099(stdClass $std): string
    {
        $required = [
            'perapur' => '/^20([0-9][0-9])-(0[1-9]|1[0-2])$/',
        ];
        return self::complement($required, $std);
    }

    /**
     * Monta a URL basica
     * @param stdClass $std
     * @return string
     */
    private static function urlbase(stdClass $std): string
    {
        return "{$std->baseurl}/R{$std->evento}/{$std->tpinsc}/{$std->nrinsc}";
    }

    /**
     * Monta a URL completa, com os campos adicionais
     * @param array $required
     * @param stdClass $std
     * @return false|string
     */
    private static function complement(array $required, stdClass $std)
    {
        $std = self::propertiesToLower($std);
        $val = self::validateConsultData($required, $std);
        if (!$val['status']) {
            $message = implode("\n", $val['errors']);
            throw new \RuntimeException('Campos foram passados com erro para a consulta. ' . $message);
        }
        $base = self::urlbase($std);
        $compl = "$base/";
        foreach ($required as $key => $regex) {
            $compl .= $std->$key . '/';
        }
        return substr($compl, 0, strlen($compl)-1);
    }
}
