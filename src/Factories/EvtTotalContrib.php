<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtTotalContrib Event R-5011 constructor
 * NOTA : Alterado para R-9011 na versão 2.0.0
 * @category  API
 * @package   NFePHP\EFDReinf
 * @copyright NFePHP Copyright (c) 2017-2019
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

class EvtTotalContrib extends Factory implements FactoryInterface
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
        $params->evtName = 'evtTotalContrib';
        $params->evtTag = 'evtTotalContrib';
        $params->evtAlias = 'R-5011';
        //$params->evtAlias = 'R-9011';
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
            "perApur",
            $this->std->perapur,
            true
        );
        $this->node->insertBefore($ideEvento, $ideContri);
        //tag deste evento em particular
        $ideRecRetorno = $this->dom->createElement("ideRecRetorno");
        $ideStatus = $this->dom->createElement("ideStatus");
        $this->dom->addChild(
            $ideStatus,
            "cdRetorno",
            $this->std->cdretorno,
            true
        );
        $this->dom->addChild(
            $ideStatus,
            "descRetorno",
            $this->std->descretorno,
            true
        );
        
        
        if (!empty($this->std->regocorrs)) {
            foreach ($this->std->regocorrs as $r) {
                $regOcorrs = $this->dom->createElement("regOcorrs");
                $this->dom->addChild(
                    $regOcorrs,
                    "tpOcorr",
                    $r->tpocorr,
                    true
                );
                $this->dom->addChild(
                    $regOcorrs,
                    "localErroAviso",
                    $r->localerroaviso,
                    true
                );
                $this->dom->addChild(
                    $regOcorrs,
                    "codResp",
                    $r->codresp,
                    true
                );
                $this->dom->addChild(
                    $regOcorrs,
                    "dscResp",
                    $r->dscresp,
                    true
                );
                $ideStatus->appendChild($regOcorrs);
            }
        }
        $ideRecRetorno->appendChild($ideStatus);
        $this->node->appendChild($ideRecRetorno);
        
        
        $infoRecEv = $this->dom->createElement("infoRecEv");
        $this->dom->addChild(
            $infoRecEv,
            "dhProcess",
            $this->std->dhprocess,
            true
        );
        $this->dom->addChild(
            $infoRecEv,
            "tpEv",
            $this->std->tpev,
            true
        );
        $this->dom->addChild(
            $infoRecEv,
            "idEv",
            $this->std->idev,
            true
        );
        $this->dom->addChild(
            $infoRecEv,
            "hash",
            $this->std->hash,
            true
        );
        $this->node->appendChild($infoRecEv);
        
        if (!empty($this->std->infototalcontrib)) {
            foreach ($this->std->infototalcontrib as $info) {
                $infoTotal = $this->dom->createElement("infoTotalContrib");
                $this->dom->addChild(
                    $infoTotal,
                    "nrRecArqBase",
                    $info->nrrecarqbase,
                    false
                );
                $this->dom->addChild(
                    $infoTotal,
                    "indExistInfo",
                    $info->indexistinfo,
                    true
                );
                $infoTotal = $this->incRTom(
                    $infoTotal,
                    !empty($info->rtom) ? $info->rtom : null
                );
                $infoTotal = $this->incRPrest(
                    $infoTotal,
                    !empty($info->rprest) ? $info->rprest : null
                );
                $infoTotal = $this->incRRecRepAD(
                    $infoTotal,
                    !empty($info->rrecrepad) ? $info->rrecrepad : null
                );
                $infoTotal = $this->incRComl(
                    $infoTotal,
                    !empty($info->rcoml) ? $info->rcoml : null
                );
                $infoTotal = $this->incRCPRB(
                    $infoTotal,
                    !empty($info->rcprb) ? $info->rcprb : null
                );
                $this->node->appendChild($infoTotal);
                $infoTotal = null;
            }
        }
        //$this->node->appendChild($infoContrib);
        
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
    
    /**
     * Include RTom node
     * @param \DOMElement $info
     * @param \stdClass $rtom
     * @return \DOMElement
     */
    protected function incRTom($info, $rtom)
    {
        if (empty($rtom)) {
            return $info;
        }
        foreach ($rtom as $r) {
            $i = $this->dom->createElement("RTom");
            $this->dom->addChild(
                $i,
                "cnpjPrestador",
                $r->cnpjprestador,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrTotalBaseRet",
                number_format($r->vlrtotalbaseret, 2, ',', ''),
                true
            );
            if (!empty($r->infocrtom)) {
                foreach ($r->infocrtom as $itom) {
                    $infoCRTom = $this->dom->createElement("infoCRTom");
                    $this->dom->addChild(
                        $infoCRTom,
                        "CRTom",
                        $itom->crtom,
                        true
                    );
                    $this->dom->addChild(
                        $infoCRTom,
                        "vlrCRTom",
                        !empty($itom->vlrcrtom) ? number_format($itom->vlrcrtom, 2, ',', '') : null,
                        false
                    );
                    $this->dom->addChild(
                        $infoCRTom,
                        "vlrCRTomSusp",
                        !empty($itom->vlrcrtomsusp) ? number_format($itom->vlrcrtomsusp, 2, ',', '') : null,
                        false
                    );
                    $i->appendChild($infoCRTom);
                }
            }
            $info->appendChild($i);
        }
        return $info;
    }
    
    /**
     * Include RPrest node
     * @param \DOMElement $info
     * @param \stdClass $rprest
     * @return \DOMElement
     */
    protected function incRPrest($info, $rprest)
    {
        if (empty($rprest)) {
            return $info;
        }
        foreach ($rprest as $r) {
            $i = $this->dom->createElement("RPrest");
            $this->dom->addChild(
                $i,
                "tpInscTomador",
                $r->tpinsctomador,
                true
            );
            $this->dom->addChild(
                $i,
                "nrInscTomador",
                $r->nrinsctomador,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrTotalBaseRet",
                number_format($r->vlrtotalbaseret, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "vlrTotalRetPrinc",
                number_format($r->vlrtotalretprinc, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "vlrTotalRetAdic",
                !empty($r->vlrtotalretadic) ? number_format($r->vlrtotalretadic, 2, ',', '') : null,
                false
            );
            $this->dom->addChild(
                $i,
                "vlrTotalNRetPrinc",
                !empty($r->vlrtotalnretprinc) ? number_format($r->vlrtotalnretprinc, 2, ',', '') : null,
                false
            );
            $this->dom->addChild(
                $i,
                "vlrTotalNRetAdic",
                !empty($r->vlrtotalnretadic) ? number_format($r->vlrtotalnretadic, 2, ',', '') : null,
                false
            );
            $info->appendChild($i);
        }
        return $info;
    }
    
    /**
     * Include RRecRepAD node
     * @param \DOMElement $info
     * @param \stdClass $rrecrepad
     * @return \DOMElement
     */
    protected function incRRecRepAD($info, $rrecrepad)
    {
        if (empty($rrecrepad)) {
            return $info;
        }
        foreach ($rrecrepad as $r) {
            $i = $this->dom->createElement("RRecRepAD");
            $this->dom->addChild(
                $i,
                "cnpjAssocDesp",
                $r->cnpjassocdesp,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrTotalRep",
                number_format($r->vlrtotalrep, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "CRRecRepAD",
                $r->crrecrepad,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRRecRepAD",
                number_format($r->vlrcrrecrepad, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRRecRepADSusp",
                !empty($r->vlrcrrecrepadsusp) ? number_format($r->vlrcrrecrepadsusp, 2, ',', '') : null,
                false
            );
            $info->appendChild($i);
        }
        return $info;
    }
    
    /**
     * Include RComl node
     * @param \DOMElement $info
     * @param \stdClass $rcoml
     * @return \DOMElement
     */
    protected function incRComl($info, $rcoml)
    {
        if (empty($rcoml)) {
            return $info;
        }
        foreach ($rcoml as $r) {
            $i = $this->dom->createElement("RComl");
            $this->dom->addChild(
                $i,
                "CRComl",
                $r->crcoml,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRComl",
                number_format($r->vlrcrcoml, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRComlSusp",
                !empty($r->vlrcrcomlsusp) ? number_format($r->vlrcrcomlsusp, 2, ',', '') : null,
                false
            );
            $info->appendChild($i);
        }
        return $info;
    }
    
    /**
     * Include RCPRB node
     * @param \DOMElement $info
     * @param \stdClass $rcprb
     * @return \DOMElement
     */
    protected function incRCPRB($info, $rcprb)
    {
        if (empty($rcprb)) {
            return $info;
        }
        foreach ($rcprb as $r) {
            $i = $this->dom->createElement("RCPRB");
            $this->dom->addChild(
                $i,
                "CRCPRB",
                $r->crcprb,
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRCPRB",
                number_format($r->vlrcrcprb, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $i,
                "vlrCRCPRBSusp",
                !empty($r->vlrcrcprbsusp) ? number_format($r->vlrcrcprbsusp, 2, ',', '') : null,
                false
            );
            $info->appendChild($i);
        }
        return $info;
    }
}
