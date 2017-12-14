<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtComProd Event R-2050 constructor
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

class EvtComProd extends Factory implements FactoryInterface
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
        $params->evtName = 'evtInfoProdRural';
        $params->evtTag = 'evtComProd';
        $params->evtAlias = 'R-2050';
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
        
        $info = $this->dom->createElement("infoComProd");
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
            number_format($this->std->vlrrecbrutatotal, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrCPApur",
            number_format($this->std->vlrcpapur, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrRatApur",
            number_format($this->std->vlrratapur, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrSenarApur",
            number_format($this->std->vlrsenarapur, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrCPSuspTotal",
            !empty($this->std->vlrcpsusptotal) ? number_format($this->std->vlrcpsusptotal, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrRatSuspTotal",
            !empty($this->std->vlrratsusptotal) ? number_format($this->std->vlrratsusptotal, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $ideEstab,
            "vlrSenarSuspTotal",
            !empty($this->std->vlrsenarsusptotal) ? number_format($this->std->vlrsenarsusptotal, 2, ',', '') : null,
            false
        );
        foreach ($this->std->tipocom as $tp) {
            $tipoCom = $this->dom->createElement("tipoCom");
            $this->dom->addChild(
                $tipoCom,
                "indCom",
                $tp->indcom,
                true
            );
            $this->dom->addChild(
                $tipoCom,
                "vlrRecBruta",
                number_format($tp->vlrrecbruta, 2, ',', ''),
                true
            );
            $ideEstab->appendChild($tipoCom);
        }
        if (!empty($this->std->infoproc)) {
            foreach($this->std->infoproc as $ip) {
                $infoProc = $this->dom->createElement("infoProc");
                $this->dom->addChild(
                    $infoProc,
                    "tpProc",
                    $ip->tpproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "nrProc",
                    $ip->nrproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "codSusp",
                    !empty($ip->codsusp) ? $ip->codsusp : null,
                    false
                );
                $this->dom->addChild(
                    $infoProc,
                    "vlrCPSusp",
                    !empty($ip->vlrcpsusp) ? number_format($ip->vlrcpsusp, 2, ',', '') : null,
                    false
                );
                $this->dom->addChild(
                    $infoProc,
                    "vlrRatSusp",
                    !empty($ip->vlrratsusp) ? number_format($ip->vlrratsusp, 2, ',', '') : null,
                    false
                );
                $this->dom->addChild(
                    $infoProc,
                    "vlrSenarSusp",
                    !empty($ip->vlrsenarsusp) ? number_format($ip->vlrsenarsusp, 2, ',', '') : null,
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
