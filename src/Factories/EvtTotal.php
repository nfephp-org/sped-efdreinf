<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtTotal Event R-5001 constructor
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

class EvtTotal extends Factory implements FactoryInterface
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
        $params->evtName = 'evtTotal';
        $params->evtTag = 'evtTotal';
        $params->evtAlias = 'R-5001';
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
        $infoTotal = $this->dom->createElement("infoTotal");
        $this->dom->addChild(
            $infoTotal,
            "nrRecArqBase",
            !empty($this->std->nrrecarqbase) ? $this->std->nrrecarqbase : null,
            false
        );
        $this->dom->addChild(
            $infoTotal,
            "indExistInfo",
            $this->std->indexistinfo,
            true
        );
        $this->node->appendChild($infoTotal);
        $infoContrib = $this->dom->createElement("infoContrib");
        $this->dom->addChild(
            $infoContrib,
            "indEscrituracao",
            $this->std->indescrituracao,
            true
        );
        $this->dom->addChild(
            $infoContrib,
            "indDesoneracao",
            $this->std->inddesoneracao,
            true
        );
        $this->dom->addChild(
            $infoContrib,
            "indAcordoIsenMulta",
            $this->std->indacordoisenmulta,
            true
        );
        if (!empty($this->std->rtom)) {
            foreach ($this->std->rtom as $r) {
                $rTom = $this->dom->createElement("RTom");
                $this->dom->addChild(
                    $rTom,
                    "cnpjPrestador",
                    $r->cnpjprestador,
                    true
                );
                $this->dom->addChild(
                    $rTom,
                    "vlrTotalBaseRet",
                    $r->vlrtotalbaseret,
                    true
                );
                $this->dom->addChild(
                    $rTom,
                    "vlrTotalRetPrinc",
                    $r->vlrtotalretprinc,
                    true
                );
                $this->dom->addChild(
                    $rTom,
                    "vlrTotalRetAdic",
                    !empty($r->vlrtotalretadic) ? $r->vlrtotalretadic : null,
                    false
                );
                $this->dom->addChild(
                    $rTom,
                    "vlrTotalNRetPrinc",
                    !empty($r->vlrtotalnretprinc) ? $r->vlrtotalnretprinc : null,
                    false
                );
                $this->dom->addChild(
                    $rTom,
                    "vlrTotalNRetAdic",
                    !empty($r->vlrtotalnretadic) ? $r->vlrtotalnretadic : null,
                    false
                );
                $infoContrib->appendChild($rTom);
            }
        }
        if (!empty($this->std->rprest)) {
            foreach ($this->std->rprest as $r) {
                $rPrest = $this->dom->createElement("RPrest");
                $this->dom->addChild(
                    $rPrest,
                    "tpInscTomador",
                    $r->tpinsctomador,
                    true
                );
                $this->dom->addChild(
                    $rPrest,
                    "nrInscTomador",
                    $r->nrinsctomador,
                    true
                );
                $this->dom->addChild(
                    $rPrest,
                    "vlrTotalBaseRet",
                    $r->vlrtotalbaseret,
                    true
                );
                $this->dom->addChild(
                    $rPrest,
                    "vlrTotalRetPrinc",
                    $r->vlrtotalretprinc,
                    true
                );
                $this->dom->addChild(
                    $rPrest,
                    "vlrTotalRetAdic",
                    !empty($r->vlrtotalretadic) ? $r->vlrtotalretadic : null,
                    false
                );
                $this->dom->addChild(
                    $rPrest,
                    "vlrTotalNRetPrinc",
                    !empty($r->vlrtotalnretprinc) ? $r->vlrtotalnretprinc : null,
                    false
                );
                $this->dom->addChild(
                    $rPrest,
                    "vlrTotalNRetAdic",
                    !empty($r->vlrtotalnretadic) ? $r->vlrtotalnretadic : null,
                    false
                );
                $infoContrib->appendChild($rPrest);
            }
        }
        if (!empty($this->std->rrecrepad)) {
            foreach ($this->std->rrecrepad as $r) {
                $rRecRepAD = $this->dom->createElement("RRecRepAD");
                $this->dom->addChild(
                    $rRecRepAD,
                    "cnpjAssocDesp",
                    $r->cnpjassocdesp,
                    true
                );
                $this->dom->addChild(
                    $rRecRepAD,
                    "vlrTotalRep",
                    $r->vlrtotalrep,
                    true
                );
                $this->dom->addChild(
                    $rRecRepAD,
                    "vlrTotalRet",
                    $r->vlrtotalret,
                    true
                );
                $this->dom->addChild(
                    $rRecRepAD,
                    "vlrTotalNRet",
                    !empty($r->vlrtotalnret) ? $r->vlrtotalnret : null,
                    false
                );
                $infoContrib->appendChild($rRecRepAD);
            }
        }
        if (!empty($this->std->rcoml)) {
            $rComl = $this->dom->createElement("RComl");
            $r = $this->std->rcoml;
            $this->dom->addChild(
                $rComl,
                "vlrCPApur",
                $r->vlrcpapur,
                true
            );
            $this->dom->addChild(
                $rComl,
                "vlrRatApur",
                $r->vlrratapur,
                true
            );
            $this->dom->addChild(
                $rComl,
                "vlrSenarApur",
                $r->vlrsenarapur,
                true
            );
            $this->dom->addChild(
                $rComl,
                "vlrCPSusp",
                !empty($r->vlrcpsusp) ? $r->vlrcpsusp : null,
                false
            );
            $this->dom->addChild(
                $rComl,
                "vlrRatSusp",
                !empty($r->vlrratsusp) ? $r->vlrratsusp : null,
                false
            );
            $this->dom->addChild(
                $rComl,
                "vlrSenarSusp",
                !empty($r->vlrsenarsusp) ? $r->vlrsenarsusp : null,
                false
            );
            $infoContrib->appendChild($rComl);
        }
        if (!empty($this->std->rcprb)) {
            foreach ($this->std->rcprb as $r) {
                $rCPRB = $this->dom->createElement("RCPRB");
                $this->dom->addChild(
                    $rCPRB,
                    "codRec",
                    $r->codrec,
                    true
                );
                $this->dom->addChild(
                    $rCPRB,
                    "vlrCPApurTotal",
                    $r->vlrcpapurtotal,
                    true
                );
                $this->dom->addChild(
                    $rCPRB,
                    "vlrCPRBSusp",
                    !empty($r->vlrcprbsusp) ? $r->vlrcprbsusp : null,
                    false
                );
                $infoContrib->appendChild($rCPRB);
            }
        }
        $this->node->appendChild($infoContrib);
        
        $this->reinf->appendChild($this->node);
        $this->xml = $this->dom->saveXML($this->reinf);
        //$this->sign($this->evtTag);
    }
}
