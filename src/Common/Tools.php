<?php

namespace NFePHP\EFDReinf\Common;

use NFePHP\Common\Certificate;
use NFePHP\eSocial\Common\XsdSeeker;
use DateTime;

class Tools
{
    /**
     * @var string
     */
    protected $path;
    /**
     * @var DateTime
     */
    protected $date;
    /**
     * @var int
     */
    protected $tpInsc;
    /**
     * @var string
     */
    protected $nrInsc;
    /**
     * @var string
     */
    protected $nmRazao;
    /**
     * @var int
     */
    protected $tpAmb;
    /**
     * @var string
     */
    protected $verProc;
    /**
     * @var string
     */
    protected $eventoVersion;
    /**
     * @var string
     */
    protected $serviceVersion;
    /**
     * @var string
     */
    protected $layoutStr;
    /**
     * @var string
     */
    protected $serviceStr;
    /**
     * @var array
     */
    protected $serviceXsd = [];
    /**
     * @var Certificate|null
     */
    protected $certificate;
    /**
     * @var int
     */
    protected $transmissortpInsc;
    /**
     * @var string
     */
    protected $transmissornrInsc;
    

    /**
     * Constructor
     * @param string $config
     * @param Certificate|null $certificate
     */
    public function __construct(
        $config,
        Certificate $certificate = null
    ) {
        //set properties from config
        $stdConf = json_decode($config);
        $this->tpAmb = $stdConf->tpAmb;
        $this->verProc = $stdConf->verProc;
        $this->eventoVersion = $stdConf->eventoVersion;
        $this->serviceVersion = $stdConf->serviceVersion;
        $this->date = new DateTime();
        $this->tpInsc = $stdConf->empregador->tpInsc;
        $this->nrInsc = $stdConf->empregador->nrInsc;
        $this->nmRazao = $stdConf->empregador->nmRazao;
        $this->transmissortpInsc = $stdConf->transmissor->tpInsc;
        $this->transmissornrInsc = $stdConf->transmissor->nrInsc;
        $this->certificate = $certificate;
        
        $this->path = realpath(
            __DIR__ . '/../../'
        ).'/';
        
        $this->serviceXsd = XsdSeeker::seek(
            $this->path . "schemes/comunicacao/$this->serviceVersion/"
        );
    }
}
