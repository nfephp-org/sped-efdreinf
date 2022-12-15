<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class Common\Tools, basic structures
 *
 * @category  Library
 * @package   NFePHP\EFDReinf
 * @copyright NFePHP Copyright (c) 2017 - 2021
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
    const EVT_INICIAIS = 1;
    const EVT_NAO_PERIODICOS = 2;
    const EVT_PERIODICOS = 3;

    /**
     * @var string
     */
    protected $path;
    /**
     * @var DateTime
     */
    protected $date;
    /**
     * @var bool
     */
    protected $admpublica = false;
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
    protected $doc;
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
     * @var array
     */
    protected $eventGroup = [
        1 => 'EVENTOS INICIAIS',
        2 => 'EVENTOS NÃO PERIÓDICOS',
        3 => 'EVENTOS PERIÓDICOS',
    ];
    /**
     * @var array
     */
    protected $grupos = [
        1 => [ //EVENTOS INICIAIS grupo [1]
            'R-1000',
            'R-1070'
        ],
        2 => [ //EVENTOS NÃO PERIÓDICOS grupo [2]
            'R-3010',
            'R-5001',
            'R-5011',
            'R-9000'
        ],
        3 => [ //EVENTOS PERIÓDICOS grupo [3]
            'R-2010',
            'R-2020',
            'R-2030',
            'R-2040',
            'R-2050',
            'R-2055',
            'R-2060',
            'R-2070',
            'R-2098',
            'R-2099',
        ],
    ];

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
        $this->admpublica = $stdConf->contribuinte->admPublica ?? false;
        $this->tpInsc = $stdConf->contribuinte->tpInsc;
        $this->nrInsc = $stdConf->contribuinte->nrInsc;
        $this->nmRazao = $stdConf->contribuinte->nmRazao;
        $this->transmissortpInsc = $stdConf->transmissor->tpInsc;
        $this->transmissornrInsc = $stdConf->transmissor->nrInsc;
        $this->certificate = $certificate;
        $this->doc = $this->nrInsc;
        if ($this->tpInsc == 1 && !$this->admpublica) {
            $this->doc = substr($this->nrInsc, 0, 8);
        }
        
        $this->path = realpath(
            __DIR__ . '/../../'
        ).'/';

        $this->serviceXsd = XsdSeeker::seek(
            $this->path . "schemes/comunicacao/v$this->serviceVersion/"
        );
    }
}
