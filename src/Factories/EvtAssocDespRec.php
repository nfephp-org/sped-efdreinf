<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtAssocDespRec Event R-2030 constructor
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

class EvtAssocDespRec extends Factory implements FactoryInterface
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
        $params->evtName = 'evtRecursoRecebidoAssociacao';
        $params->evtTag = 'evtAssocDespRec';
        $params->evtAlias = 'R-2030';
        parent::__construct($config, $std, $params, $certificate, $date);
    }
    
    /**
     * Node constructor
     */
    protected function toNode()
    {
        $ideContri = $this->node->getElementsByTagName('ideContri')->item(0);
        //remove ideContri
        $this->node->removeChild($ideContri);
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
        $this->node->appendChild($ideEvento);
        
        //cria novo ideContri
        $ideContri = $this->dom->createElement("ideContri");
        $this->dom->addChild(
            $ideContri,
            "tpInsc",
            $this->tpInsc,
            true
        );
        $this->dom->addChild(
            $ideContri,
            "nrInsc",
            $this->nrInsc,
            true
        );
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
        foreach ($this->std->recursosrec as $r) {
            $recursosRec = $this->dom->createElement("recursosRec");
            $this->dom->addChild(
                $recursosRec,
                "cnpjOrigRecurso",
                $r->cnpjorigrecurso,
                true
            );
            $this->dom->addChild(
                $recursosRec,
                "vlrTotalRec",
                $r->vlrtotalrec,
                true
            );
            $this->dom->addChild(
                $recursosRec,
                "vlrTotalRet",
                $r->vlrtotalret,
                true
            );
            $this->dom->addChild(
                $recursosRec,
                "vlrTotalNRet",
                !empty($r->vlrtotalnret) ? $r->vlrtotalnret : null,
                false
            );
            foreach ($r->inforecurso as $i) {
                $infoRecurso = $this->dom->createElement("infoRecurso");
                $this->dom->addChild(
                    $infoRecurso,
                    "tpRepasse",
                    $i->tprepasse,
                    true
                );
                $this->dom->addChild(
                    $infoRecurso,
                    "descRecurso",
                    $i->descrecurso,
                    true
                );
                $this->dom->addChild(
                    $infoRecurso,
                    "vlrBruto",
                    $i->vlrbruto,
                    true
                );
                $this->dom->addChild(
                    $infoRecurso,
                    "vlrRetApur",
                    $i->vlrretapur,
                    true
                );
                $recursosRec->appendChild($infoRecurso);
            }
            if (!empty($r->infoproc)) {
                foreach ($r->infoproc as $i) {
                    $infoProc = $this->dom->createElement("infoProc");
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
                        true
                    );
                    $this->dom->addChild(
                        $infoProc,
                        "vlrNRet",
                        $i->vlrnret,
                        true
                    );
                    $recursosRec->appendChild($infoProc);
                }
            }
            $ideEstab->appendChild($recursosRec);
        }

        $ideContri->appendChild($ideEstab);
        $this->node->appendChild($ideContri);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
