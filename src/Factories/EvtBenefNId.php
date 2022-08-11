<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtRetCons Event R-4040 constructor
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

class EvtBenefNId extends Factory implements FactoryInterface
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
        $params->evtName = 'evt4040PagtoBenefNaoIdentificado';
        $params->evtTag = 'evtBenefNId';
        $params->evtAlias = 'R-4040';
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
        foreach($this->std->idenat as $nat) {
            $ideNat = $this->dom->createElement("ideNat");
            $this->dom->addChild(
                $ideNat,
                "natRend",
                $nat->natrend,
                true
            );
            foreach ($nat->infopgto as $pgto) {
                $infoPgto = $this->dom->createElement("infoPgto");
                $this->dom->addChild(
                    $infoPgto,
                    "dtFG",
                    $pgto->dtfg,
                    true
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrLiq",
                    $this->format($pgto->vlrliq),
                    true
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrBaseIR",
                    $this->format($pgto->vlrbaseir),
                    true
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrIR",
                    $this->format($pgto->vlrir ?? null),
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "descr",
                    $pgto->descr,
                    true
                );
                foreach ($pgto->infoprocret as $ret) {
                    $infoProcRet = $this->dom->createElement("infoProcRet");
                    $this->dom->addChild(
                        $infoProcRet,
                        "tpProcRet",
                        $ret->tpprocret,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "nrProcRet",
                        $ret->nrprocret,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "codSusp",
                        $ret->codsusp ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrBaseSuspIR",
                        $this->format($ret->vlrbasesuspir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNIR",
                        $this->format($ret->vlrnir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepNIR",
                        $this->format($ret->vlrdepnir ?? null),
                        false
                    );
                    $infoPgto->appendChild($infoProcRet);
                }
                $ideNat->appendChild($infoPgto);
            }
            $ideEstab->appendChild($ideNat);
        }
        $this->node->appendChild($ideEstab);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
