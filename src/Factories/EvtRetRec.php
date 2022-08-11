<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtRetCons Event R-4098 constructor
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
use NFePHP\EFDReinf\Factories\Traits\FormatNumber;
use stdClass;

class EvtRetRec extends Factory implements FactoryInterface
{
    use FormatNumber;

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
        $params->evtName = 'evt4080RetencaoRecebimento';
        $params->evtTag = 'evtRetRec';
        $params->evtAlias = 'R-4080';
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
        if ($this->std->indretif == 1) {
            $this->std->nrrecibo = null;
        }
        if ($this->std->indretif == 2 && empty($this->std->nrrecibo)) {
            throw new \Exception("Para retificar o evento DEVE ser informado o "
                . "número do RECIBO do evento anterior que está retificando.");
        }
        $this->dom->addChild(
            $ideEvento,
            "nrRecibo",
            !empty($this->std->nrrecibo) ? $this->std->nrrecibo : null,
            $this->std->indretif == 2 ? true : false
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
        $ideEstab = $this->dom->createElement("ideEstab");
        $this->dom->addChild(
            $ideEstab,
            "tpInscEstab",
            $this->std->tpinscestab ?? '1',
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "nrInscEstab",
            $this->std->nrinscestab,
            true
        );
        $ideFont = $this->dom->createElement("ideFont");
        $this->dom->addChild(
            $ideFont,
            "cnpjFont",
            $this->std->cnpjfont,
            true
        );
        foreach ($this->std->iderend as $rend) {
            $ideRend = $this->dom->createElement("ideRend");
            $this->dom->addChild(
                $ideRend,
                "natRend",
                $rend->natrend,
                true
            );
            $this->dom->addChild(
                $ideRend,
                "observ",
                $rend->observ ?? null,
                false
            );
            foreach ($rend->inforec as $rec) {
                $infoRec = $this->dom->createElement("infoRec");
                $this->dom->addChild(
                    $infoRec,
                    "dtFG",
                    $rec->dtfg,
                    true
                );
                $this->dom->addChild(
                    $infoRec,
                    "vlrBruto",
                    $this->format($rec->vlrbruto),
                    true
                );
                $this->dom->addChild(
                    $infoRec,
                    "vlrBaseIR",
                    $this->format($rec->vlrbaseir),
                    true
                );
                $this->dom->addChild(
                    $infoRec,
                    "vlrIR",
                    $this->format($rec->vlrir),
                    true
                );
                foreach ($rec->infoprocret as $proc) {
                    $infoProcRet = $this->dom->createElement("infoProcRet");
                    $this->dom->addChild(
                        $infoProcRet,
                        "tpProcRet",
                        $proc->tpprocret,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "nrProcRet",
                        $proc->nrprocret,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "codSusp",
                        $proc->codsusp ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrBaseSuspIR",
                        $this->format($proc->vlrbasesuspir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNIR",
                        $this->format($proc->vlrnir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepIR",
                        $this->format($proc->vlrdepir ?? null),
                        false
                    );
                    $infoRec->appendChild($infoProcRet);
                }
                $ideRend->appendChild($infoRec);
            }
            $ideFont->appendChild($ideRend);
        }
        $ideEstab->appendChild($ideFont);
        $this->node->appendChild($ideEstab);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
