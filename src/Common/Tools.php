<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class Common\Tools, basic structures
 *
 * @category  API
 * @package   NFePHP\EFDReinf
 * @copyright NFePHP Copyright (c) 2017
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */

use NFePHP\Common\Certificate;
use NFePHP\EFDReinf\Common\XsdSeeker;
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
     * @var Certificate
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
     * @param Certificate $certificate
     */
    public function __construct(
        $config,
        Certificate $certificate
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
            $this->path . "schemes/comunicacao/v$this->serviceVersion/"
        );
    }
}
