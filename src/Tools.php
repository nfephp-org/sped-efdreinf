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
 * @link      http://github.com/nfephp-org/sped-esocial for the canonical source repository
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
    public $lastRequest;
    public $lastResponse;
    
    /**
     * @var NFePHP\Common\Soap\SoapInterface
     */
    protected $soap;
    protected $soapnamespaces = [
        'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
        'xmlns:xsd' => "http://www.w3.org/2001/XMLSchema",
        'xmlns:soap' => "http://www.w3.org/2003/05/soap-envelope",
        'xmlns:sped'=> "http://sped.fazenda.gov.br/"
    ];
    protected $objHeader;
    protected $xmlns;
    protected $uri = [
        '1' => '',
        '2' => 'https://preprodefdreinf.receita.fazenda.gov.br/RecepcaoLoteReinf.svc'
    ];
    protected $action;
    protected $method;
    protected $parameters;

    public function __construct($config, Certificate $certificate = null)
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
     * @param string $protocolo
     * @return string
     */
    public function consultaLoteEventos($protocolo)
    {
    }
    
    /**
     * Send batch of events
     * @param array $eventos
     * @return string
     */
    public function envioLoteEventos($eventos = [])
    {
        if (empty($eventos)) {
            return '';
        }
        $xml = "";
        $nEvt = count($eventos);
        if ($nEvt > 100) {
            throw ProcessException::wrongArgument(2000, $nEvt);
        }

      
        $this->method = "ReceberLoteEventos";
        
        $this->action = "http://sped.fazenda.gov.br/RecepcaoLoteReinf/ReceberLoteEventos";
        
        $uri = [
            '1' => '',
            '2' => 'https://preprodefdreinf.receita.fazenda.gov.br/RecepcaoLoteReinf.svc',
            '3' => 'https://preprodefdreinf.receita.fazenda.gov.br/RecepcaoLoteReinf.svc'
        ];
        
        
        $request = "<eSocial xmlns=\"http://www.esocial.gov.br/schema/lote"
            . "/eventos/envio/$operationVersion\" "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">"
            . "<envioLoteEventos grupo=\"$grupo\">"
            . "<ideEmpregador>"
            . "<tpInsc>$this->tpInsc</tpInsc>"
            . "<nrInsc>$this->nrInsc</nrInsc>"
            . "</ideEmpregador>"
            . "<ideTransmissor>"
            . "<tpInsc>$this->transmissortpInsc</tpInsc>"
            . "<nrInsc>$this->transmissornrInsc</nrInsc>"
            . "</ideTransmissor>"
            . "<eventos>"
            . "$xml"
            . "</eventos>"
            . "</envioLoteEventos>"
            . "</eSocial>";

        /*
        //validar a requisição conforme o seu respectivo XSD
        Validator::isValid($request, $this->path
            . "schemes/comunicacao/$this->serviceStr/"
            . "EnvioLoteEventos-$operationVersion.xsd");
        */
        
        $body = "<sped:ReceberLoteEventos>"
            . "<sped:loteEventos>"
            . $request
            . "</sped:loteEventos>"
            . "</sped:ReceberLoteEventos>";
        
        $this->lastRequest = $body;
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
        //return $envelope;
        return (string) $this->soap->send(
            $this->method,
            $this->uri,
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
        if (empty($this->certificate)) {
            //try to get certificate from event
            $certificate = $evento->getCertificate();
            if (empty($certificate)) {
                //oops no certificate avaiable
                throw ProcessException::wrongArgument(2001, '');
            }
            $this->certificate = $certificate;
        } else {
            $certificate = $evento->getCertificate();
            if (empty($certificate)) {
                $evento->setCertificate($this->certificate);
            }
        }
    }
}
