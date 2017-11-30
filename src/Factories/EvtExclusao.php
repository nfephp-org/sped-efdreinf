<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtExclusao Event R-9000 constructor
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

use NFePHP\EFDReinf\Common\Factory;
use NFePHP\EFDReinf\Common\FactoryInterface;
use NFePHP\EFDReinf\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtExclusao extends Factory implements FactoryInterface
{
    /**
     * @var string
     */
    protected $evtName = 'evtExclusao';
    /**
     * @var string
     */
    protected $evtTag = 'evtExclusao';
    /**
     * @var string
     */
    protected $evtAlias = 'R-9000';

    /**
     * Constructor
     * @param string $config
     * @param stdClass $std
     * @param Certificate $certificate
     * @param string date
     */
    public function __construct(
        $config,
        stdClass $std,
        Certificate $certificate = null,
        $date = ''
    ) {
        parent::__construct($config, $std, $certificate, $date);
    }
    
    /**
     * Node constructor
     */
    protected function toNode()
    {
        $ideContri = $this->node->getElementsByTagName('ideContri')->item(0);
        //o idEvento pode variar de evento para evento
        //então cada factory individualmente terá de construir o seu
        $ideEvento = $this->dom->createElement("ideEvento");
        $this->dom->addChild(
            $ideEvento,
            "tpAmb",
            $this->tpAmb,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "procEmi",
            $this->procEmi,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "verProc",
            $this->verProc,
            true
        );
        $this->node->insertBefore($ideEvento, $ideContri);
        //tag deste evento em particular
        $infoExclusao = $this->dom->createElement("infoExclusao");
        $this->dom->addChild(
            $infoExclusao,
            "tpEvento",
            $this->std->tpevento,
            true
        );
        $this->dom->addChild(
            $infoExclusao,
            "nrRecEvt",
            $this->std->nrrecevt,
            true
        );
        $this->dom->addChild(
            $infoExclusao,
            "perApur",
            $this->std->perapur,
            true
        );
        $this->node->appendChild($infoExclusao);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign();
    }
}
