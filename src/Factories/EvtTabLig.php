<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtRetCons Event R-4020 constructor
 *
 * @category  Library
 * @package   NFePHP\EFDReinf
 * @copyright NFePHP Copyright (c) 2017 - 2022
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
use NFePHP\Common\Strings;
use NFePHP\EFDReinf\Exception\EventsException;
use stdClass;

class EvtTabLig extends Factory implements FactoryInterface
{
    /**
     * Constructor
     * @param string $config
     * @param stdClass $std
     * @param Certificate $certificate
     * @param string $data
     */
    public function __construct(
        $config,
        stdClass $std,
        Certificate $certificate = null,
        $data = ''
    ) {
        $params = new \stdClass();
        $params->evtName = 'evt1050TabEntidadesLigadas';
        $params->evtTag = 'evtTabLig';
        $params->evtAlias = 'R-1050';
        parent::__construct($config, $std, $params, $certificate, $data);
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
        $infoLig = $this->dom->createElement("infoLig");
        $ideEntLig = $this->dom->createElement("ideEntLig");
        $this->dom->addChild(
            $ideEntLig,
            "tpEntLig",
            $this->std->tpentlig ?? null,
            false
        );
        $this->dom->addChild(
            $ideEntLig,
            "cnpjLig",
            $this->std->cnpjlig,
            true
        );
        $this->dom->addChild(
            $ideEntLig,
            "iniValid",
            $this->std->inivalid,
            true
        );
        $this->dom->addChild(
            $ideEntLig,
            "fimValid",
            $this->std->fimvalid ?? null,
            false
        );
        if ($this->std->modo == 'INC') {
            if (empty($this->std->tpentlig)) {
                throw EventsException::wrongArgument(1004, "Violations: [tpentlig] The property tpentlig is required");
            }
            $modo = $this->dom->createElement("inclusao");
            $modo->appendChild($ideEntLig);
        } elseif ($this->std->modo == 'ALT') {
            if (empty($this->std->tpentlig)) {
                throw EventsException::wrongArgument(1004, "Violations: [tpentlig] The property tpentlig is required");
            }
            $modo = $this->dom->createElement("alteracao");
            $modo->appendChild($ideEntLig);
            $novaValidade = $this->dom->createElement("novaValidade");
            $new = $this->std->novavalidade;
            $this->dom->addChild(
                $novaValidade,
                "iniValid",
                $new->inivalid,
                true
            );
            $this->dom->addChild(
                $novaValidade,
                "fimValid",
                $new->fimvalid ?? null,
                false
            );
            $modo->appendChild($novaValidade);
        } else {
            $modo = $this->dom->createElement("exclusao");
            $child = $ideEntLig->getElementsByTagName('tpEntLig')->item(0);
            $ideEntLig->removeChild($child);
            $modo->appendChild($ideEntLig);
        }
        //finalização do xml
        $infoLig->appendChild($modo);
        $this->node->appendChild($infoLig);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
