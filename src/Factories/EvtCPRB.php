<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtCPRB Event R-2060 constructor
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

class EvtCPRB extends Factory implements FactoryInterface
{
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
        $params = new \stdClass();
        $params->evtName = 'evtInfoCPRB';
        $params->evtTag = 'evtCPRB';
        $params->evtAlias = 'R-2060';
        parent::__construct($config, $std, $params, $certificate, $date);
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
            "indRetif",
            $this->std->indretif,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "nrRecibo",
            !empty($this->std->nrrecibo) ? $this->std->nrrecibo : null,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "perApur",
            $this->std->perapur,
            true
        );
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
        
        $info = $this->dom->createElement("infoCPRB");
        $ideEstab = $this->dom->createElement("ideEstab");
        $this->dom->addChild(
            $ideEstab,
            "tpInscEstab",
            $this->std->tpinscestab,
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "nrInscEstab",
            $this->std->nrinscestab,
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrRecBrutaTotal",
            $this->std->vlrrecbrutatotal,
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrCPApurTotal",
            $this->std->vlrcpapurtotal,
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrCPRBSuspTotal",
            !empty($this->std->vlrcprbsusptotal) ? $this->std->vlrcprbsusptotal : null,
            false
        );
        foreach ($this->std->tipocod as $t) {
            $tipoCod = $this->dom->createElement("tipoCod");
            $this->dom->addChild(
                $tipoCod,
                "codAtivEcon",
                $t->codativecon,
                true
            );
            $this->dom->addChild(
                $tipoCod,
                "vlrRecBrutaAtiv",
                $t->vlrrecbrutaativ,
                true
            );
            $this->dom->addChild(
                $tipoCod,
                "vlrExcRecBruta",
                $t->vlrexcrecbruta,
                true
            );
            $this->dom->addChild(
                $tipoCod,
                "vlrAdicRecBruta",
                $t->vlradicrecbruta,
                true
            );
            $this->dom->addChild(
                $tipoCod,
                "vlrBcCPRB",
                $t->vlrbccprb,
                true
            );
            $this->dom->addChild(
                $tipoCod,
                "vlrCPRBapur",
                !empty($t->vlrcprbapur) ? $t->vlrcprbapur : null,
                false
            );
            if (!empty($t->tipoajuste)) {
                foreach ($t->tipoajuste as $a) {
                    $tipoAjuste = $this->dom->createElement("tipoAjuste");
                    $this->dom->addChild(
                        $tipoAjuste,
                        "tpAjuste",
                        $a->tpajuste,
                        true
                    );
                    $this->dom->addChild(
                        $tipoAjuste,
                        "codAjuste",
                        $a->codajuste,
                        true
                    );
                    $this->dom->addChild(
                        $tipoAjuste,
                        "vlrAjuste",
                        $a->vlrajuste,
                        true
                    );
                    $this->dom->addChild(
                        $tipoAjuste,
                        "descAjuste",
                        $a->descajuste,
                        true
                    );
                    $this->dom->addChild(
                        $tipoAjuste,
                        "dtAjuste",
                        $a->dtajuste,
                        true
                    );
                    $tipoCod->appendChild($tipoAjuste);
                }
            }
            $ideEstab->appendChild($tipoCod);
        }
        if (!empty($this->std->infoproc)) {
            foreach ($this->std->infoproc as $i) {
                $infoProc = $this->dom->createElement("infoProc");
                $this->dom->addChild(
                    $infoProc,
                    "vlrCPRBSusp",
                    $i->vlrcprbsusp,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "tpProc",
                    $i->tpproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "nrProc",
                    $i->nrproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "codSusp",
                    !empty($i->codsusp) ? $i->codsusp : null,
                    false
                );
                $ideEstab->appendChild($infoProc);
            }
        }
        $info->appendChild($ideEstab);
        $this->node->appendChild($info);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
