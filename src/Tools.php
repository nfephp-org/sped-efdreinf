<?php

namespace NFePHP\EFDReinf;

/**
 * Classe Tools, performs communication with the EFDReinf webservice
 *
 * @category  API
 * @package   NFePHP\EFDReinf\Tools
 * @copyright Copyright (c) 2017
 * @license   https://www.gnu.org/licenses/lgpl-3.0.txt LGPLv3
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @license   https://opensource.org/licenses/mit-license.php MIT
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */
use NFePHP\Common\Certificate;
use NFePHP\Common\Validator;
use NFePHP\EFDReinf\Common\Tools as ToolsBase;
use NFePHP\EFDReinf\Common\FactoryInterface;
use NFePHP\EFDReinf\Common\Soap\SoapCurl;
use NFePHP\EFDReinf\Common\Soap\SoapInterface;
use NFePHP\EFDReinf\Exception\ProcessException;

class Tools extends ToolsBase
{
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
        '1' => 'https://reinf.receita.fazenda.gov.br/WsREINF/ConsultasReinf.svc',
        '2' => 'https://preprodefdreinf.receita.fazenda.gov.br/WsREINF/ConsultasReinf.svc'
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
     * Event batch query
     * @param string $recibofechamento
     * @return string
     */
    public function consultar($recibofechamento)
    {
        if (empty($recibofechamento)) {
            return '';
        }
        $this->method = "ConsultaInformacoesConsolidadas";
        $this->action = "http://sped.fazenda.gov.br/ConsultasReinf/".$this->method;
        $request = "<sped:tipoInscricaoContribuinte>$this->tpInsc</sped:tipoInscricaoContribuinte>";
        $request .= "<sped:numeroInscricaoContribuinte>$this->nrInsc</sped:numeroInscricaoContribuinte>";
        $request .= "<sped:numeroProtocoloFechamento>$recibofechamento</sped:numeroProtocoloFechamento>";
        $body = "<sped:ConsultaInformacoesConsolidadas>"
            . $request
            . "</sped:ConsultaInformacoesConsolidadas>";
        
        $this->lastResponse = $this->sendRequest($body);
        return $this->lastResponse;
    }
    
    /**
     * Send batch of events
     * @param  integer $grupo
     * @param array $eventos
     * @return string
     */
    public function enviarLoteEventos($grupo, $eventos = [])
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
        $this->action = "http://sped.fazenda.gov.br/RecepcaoLoteReinf/ReceberLoteEventos";
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
     * Send request to webservice
     * @param string $request
     * @return string
     */
    protected function sendRequest($request)
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
        if ($this->method == 'ReceberLoteEventos') {
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
     * @throws RuntimeException
     */
    protected function checkCertificate(FactoryInterface $evento)
    {
        //try to get certificate from event
        $certificate = $evento->getCertificate();
        if (empty($certificate)) {
            $evento->setCertificate($this->certificate);
        }
    }
}
