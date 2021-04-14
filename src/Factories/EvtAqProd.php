<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtAqProd Event R-2055 constructor
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

use NFePHP\EFDReinf\Common\Factory;
use NFePHP\EFDReinf\Common\FactoryInterface;
use NFePHP\EFDReinf\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtAqProd extends Factory implements FactoryInterface
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
        $params->evtName = 'evt2055AquisicaoProdRural';
        $params->evtTag = 'evtAqProd';
        $params->evtAlias = 'R-2055';
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
        $this->dom->addChild(
            $ideEvento,
            "retifS1250",
            !empty($this->std->retifs1250) ? $this->std->retifs1250 : null,
            false
        );
        $this->node->insertBefore($ideEvento, $ideContri);

        $info = $this->dom->createElement("infoAquisProd");
        $ideEstab = $this->dom->createElement("ideEstabAdquir");
        $this->dom->addChild(
            $ideEstab,
            "tpInscAdq",
            $this->std->tpinscadq,
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "nrInscAdq",
            $this->std->nrinscadq,
            true
        );

        $ideprod = $this->dom->createElement("ideProdutor");
        $this->dom->addChild(
            $ideprod,
            "tpInscProd",
            $this->std->tpinscprod,
            true
        );
        $this->dom->addChild(
            $ideprod,
            "nrInscProd",
            $this->std->nrinscprod,
            true
        );
        $this->dom->addChild(
            $ideprod,
            "indOpcCP",
            !empty($this->std->indopccp) ? $this->std->indopccp : null,
            false
        );
        foreach ($this->std->detaquis as $det) {
            $detaq = $this->dom->createElement("detAquis");
            $this->dom->addChild(
                $detaq,
                "indAquis",
                $det->indaquis,
                true
            );
            $this->dom->addChild(
                $detaq,
                "vlrBruto",
                number_format($det->vlrbruto, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $detaq,
                "vlrCPDescPR",
                number_format($det->vlrcpdescpr, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $detaq,
                "vlrRatDescPR",
                number_format($det->vlrratdescpr, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $detaq,
                "vlrSenarDesc",
                number_format($det->vlrsenardesc, 2, ',', ''),
                true
            );
            foreach ($det->infoprocjud as $jud) {
                $procjud = $this->dom->createElement("infoProcJud");
                $this->dom->addChild(
                    $procjud,
                    "nrProcJud",
                    $jud->nrprocjud,
                    true
                );
                $this->dom->addChild(
                    $procjud,
                    "codSusp",
                    !empty($jud->codsusp) ? $jud->codsusp : null,
                    false
                );
                $this->dom->addChild(
                    $procjud,
                    "vlrCPNRet",
                    !empty($jud->vlrcpnret) ? number_format($jud->vlrcpnret, 2, ',', '') : null,
                    false
                );
                $this->dom->addChild(
                    $procjud,
                    "vlrRatNRet",
                    !empty($jud->vlrratnret) ? number_format($jud->vlrratnret, 2, ',', '') : null,
                    false
                );
                $this->dom->addChild(
                    $procjud,
                    "vlrSenarNRet",
                    !empty($jud->vlrsenarnret) ? number_format($jud->vlrsenarnret, 2, ',', '') : null,
                    false
                );
                $detaq->appendChild($procjud);
            }
            $ideprod->appendChild($detaq);
        }
        $ideEstab->appendChild($ideprod);
        $info->appendChild($ideEstab);
        $this->node->appendChild($info);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
