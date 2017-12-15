<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtPgtosDivs Event R-2070 constructor
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

class EvtPgtosDivs extends Factory implements FactoryInterface
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
        $params->evtName = 'evtPagtoDivs';
        $params->evtTag = 'evtPgtosDivs';
        $params->evtAlias = 'R-2070';
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
        $this->node->insertBefore($ideEvento, $ideContri);
        $ideBenef = $this->dom->createElement("ideBenef");
        $this->dom->addChild(
            $ideBenef,
            "codPgto",
            $this->std->codpgto,
            true
        );
        $this->dom->addChild(
            $ideBenef,
            "tpInscBenef",
            !empty($this->std->tpinscbenef) ? $this->std->tpinscbenef : null,
            false
        );
        $this->dom->addChild(
            $ideBenef,
            "nrInscBenef",
            !empty($this->std->nrinscbenef) ? $this->std->nrinscbenef : null,
            false
        );
        $this->dom->addChild(
            $ideBenef,
            "nmRazaoBenef",
            $this->std->nmrazaobenef,
            true
        );
        
        if (!empty($this->std->inforesidext)) {
            $info = $this->std->inforesidext;
            $infoResidExt = $this->dom->createElement("infoResidExt");
            $infoEnder = $this->dom->createElement("infoEnder");
            $this->dom->addChild(
                $infoEnder,
                "paisResid",
                $info->paisresid,
                true
            );
            $this->dom->addChild(
                $infoEnder,
                "dscLograd",
                $info->dsclograd,
                true
            );
            $this->dom->addChild(
                $infoEnder,
                "nrLograd",
                !empty($info->nrlograd) ? $info->nrlograd : null,
                false
            );
            $this->dom->addChild(
                $infoEnder,
                "complem",
                !empty($info->complem) ? $info->complem : null,
                false
            );
            $this->dom->addChild(
                $infoEnder,
                "bairro",
                !empty($info->bairro) ? $info->bairro : null,
                false
            );
            $this->dom->addChild(
                $infoEnder,
                "cidade",
                !empty($info->cidade) ? $info->cidade : null,
                false
            );
            $this->dom->addChild(
                $infoEnder,
                "codPostal",
                !empty($info->codpostal) ? $info->codpostal : null,
                false
            );
            $infoResidExt->appendChild($infoEnder);
            $infoFiscal = $this->dom->createElement("infoFiscal");
            $this->dom->addChild(
                $infoFiscal,
                "indNIF",
                $info->indnif,
                true
            );
            $this->dom->addChild(
                $infoFiscal,
                "nifBenef",
                !empty($info->nifbenef) ? $info->nifbenef : null,
                false
            );
            $this->dom->addChild(
                $infoFiscal,
                "relFontePagad",
                !empty($info->relfontepagad) ? $info->relfontepagad : null,
                false
            );
            $infoResidExt->appendChild($infoFiscal);
            $ideBenef->appendChild($infoResidExt);
        }
        if (!empty($this->std->infomolestia)) {
            $mol = $this->std->infomolestia;
            $infoMolestia = $this->dom->createElement("infoMolestia");
            $this->dom->addChild(
                $infoMolestia,
                "dtLaudo",
                $mol->dtlaudo,
                true
            );
            $ideBenef->appendChild($infoMolestia);
        }
        
        $infoPgto = $this->dom->createElement("infoPgto");
        foreach ($this->std->ideestab as $stab) {
            $ideEstab = $this->dom->createElement("ideEstab");
            $this->dom->addChild(
                $ideEstab,
                "tpInsc",
                $stab->tpinsc,
                true
            );
            $this->dom->addChild(
                $ideEstab,
                "nrInsc",
                $stab->nrinsc,
                true
            );
            $pgtoResidBR = null;
            if (!empty($stab->pgtopf)) {
                $pgtoResidBR = $this->dom->createElement("pgtoResidBR");
                foreach ($stab->pgtopf as $ppf) {
                    $pgtoPF = $this->dom->createElement("pgtoPF");
                    $this->dom->addChild(
                        $pgtoPF,
                        "dtPgto",
                        $ppf->dtpgto,
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPF,
                        "indSuspExig",
                        $ppf->indsuspexig,
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPF,
                        "indDecTerceiro",
                        $ppf->inddecterceiro,
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPF,
                        "vlrRendTributavel",
                        number_format($ppf->vlrrendtributavel, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPF,
                        "vlrIRRF",
                        number_format($ppf->vlrirrf, 2, ',', ''),
                        true
                    );
                    if (!empty($ppf->detdeducao)) {
                        foreach ($ppf->detdeducao as $dd) {
                            $detDeducao = $this->dom->createElement("detDeducao");
                            $this->dom->addChild(
                                $detDeducao,
                                "indTpDeducao",
                                $dd->indtpdeducao,
                                true
                            );
                            $this->dom->addChild(
                                $detDeducao,
                                "vlrDeducao",
                                number_format($dd->vlrdeducao, 2, ',', ''),
                                true
                            );
                            $pgtoPF->appendChild($detDeducao);
                        }
                    }
                    if (!empty($ppf->rendisento)) {
                        foreach ($ppf->rendisento as $ri) {
                            $rendIsento = $this->dom->createElement("rendIsento");
                            $this->dom->addChild(
                                $rendIsento,
                                "tpIsencao",
                                $ri->tpisencao,
                                true
                            );
                            $this->dom->addChild(
                                $rendIsento,
                                "vlrIsento",
                                number_format($ri->vlrisento, 2, ',', ''),
                                true
                            );
                            $this->dom->addChild(
                                $rendIsento,
                                "descRendimento",
                                !empty($ri->descrendimento) ? $ri->descrendimento : null,
                                false
                            );
                            $pgtoPF->appendChild($rendIsento);
                        }
                    }
                    if (!empty($ppf->detcompet)) {
                        foreach ($ppf->detcompet as $dc) {
                            $detCompet = $this->dom->createElement("detCompet");
                            $this->dom->addChild(
                                $detCompet,
                                "indPerReferencia",
                                $dc->indperreferencia,
                                true
                            );
                            $this->dom->addChild(
                                $detCompet,
                                "perRefPagto",
                                $dc->perrefpagto,
                                true
                            );
                            $this->dom->addChild(
                                $detCompet,
                                "vlrRendTributavel",
                                number_format($dc->vlrrendtributavel, 2, ',', ''),
                                true
                            );
                            $pgtoPF->appendChild($detCompet);
                        }
                    }
                    if (!empty($ppf->compjud)) {
                        $cjud = $ppf->compjud;
                        $compJud = $this->dom->createElement("compJud");
                        $this->dom->addChild(
                            $compJud,
                            "vlrCompAnoCalend",
                            !empty($cjud->vlrcompqnocalend) ? number_format($cjud->vlrcompqnocalend, 2, ',', '') : null,
                            false
                        );
                        $this->dom->addChild(
                            $compJud,
                            "vlrCompAnoAnt",
                            !empty($cjud->vlrcompanoant) ? number_format($cjud->vlrcompanoant, 2, ',', '') : null,
                            false
                        );
                        $pgtoPF->appendChild($compJud);
                    }
                    
                    if (!empty($ppf->inforra)) {
                        foreach ($ppf->inforra as $irra) {
                            $infoRRA = $this->dom->createElement("infoRRA");
                            $this->dom->addChild(
                                $infoRRA,
                                "tpProcRRA",
                                !empty($irra->tpprocrra) ? $irra->tpprocrra : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoRRA,
                                "nrProcRRA",
                                !empty($irra->nrprocrra) ? $irra->nrprocrra : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoRRA,
                                "codSusp",
                                !empty($irra->codsusp) ? $irra->codsusp : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoRRA,
                                "natRRA",
                                !empty($irra->natrra) ? $irra->natrra : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoRRA,
                                "qtdMesesRRA",
                                !empty($irra->qtdmesesrra) ? $irra->qtdmesesrra : null,
                                false
                            );
                            if (!empty($irra->despprocjud)) {
                                $dpj = $irra->despprocjud;
                                $despProcJud = $this->dom->createElement("despProcJud");
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespCustas",
                                    number_format($dpj->vlrdespcustas, 2, ',', ''),
                                    true
                                );
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespAdvogados",
                                    number_format($dpj->vlrdespadvogados, 2, ',', ''),
                                    true
                                );
                                if (!empty($dpj->ideadvogado)) {
                                    foreach ($dpj->ideadvogado as $ia) {
                                        $ideAdvogado = $this->dom->createElement("ideAdvogado");
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "tpInscAdvogado",
                                            $ia->tpinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "nrInscAdvogado",
                                            $ia->nrinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "vlrAdvogado",
                                            number_format($ia->vlradvogado, 2, ',', ''),
                                            true
                                        );
                                        $despProcJud->appendChild($ideAdvogado);
                                    }
                                }
                                $infoRRA->appendChild($despProcJud);
                            }
                            $pgtoPF->appendChild($infoRRA);
                        }
                    }
                    if (!empty($ppf->infoprocjud)) {
                        foreach ($ppf->infoprocjud as $ipj) {
                            $infoProcJud = $this->dom->createElement("infoProcJud");
                            $this->dom->addChild(
                                $infoProcJud,
                                "nrProcJud",
                                $ipj->nrprocjud,
                                true
                            );
                            $this->dom->addChild(
                                $infoProcJud,
                                "codSusp",
                                !empty($ipj->codsusp) ? $ipj->codsusp : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoProcJud,
                                "indOrigemRecursos",
                                $ipj->indorigemrecursos,
                                true
                            );
                            if (!empty($ipj->despprocjud)) {
                                $vdc = $ipj->despprocjud;
                                $despProcJud = $this->dom->createElement("despProcJud");
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespCustas",
                                    number_format($vdc->vlrdespcustas, 2, ',', ''),
                                    true
                                );
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespCustas",
                                    number_format($vdc->vlrdespadvogados, 2, ',', ''),
                                    true
                                );
                                if (!empty($ipj->ideadvogado)) {
                                    foreach ($ipj->ideadvogado as $ad) {
                                        $ideAdvogado = $this->dom->createElement("ideAdvogado");
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "tpInscAdvogado",
                                            $ad->tpinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "nrInscAdvogado",
                                            $ad->nrinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "vlrAdvogado",
                                            number_format($ad->vlradvogado, 2, ',', ''),
                                            true
                                        );
                                        $despProcJud->appendChild($ideAdvogado);
                                    }
                                }
                                $infoProcJud->appendChild($despProcJud);
                            }
                            if (!empty($ipj->origemrecursos)) {
                                $origemRecursos = $this->dom->createElement("origemRecursos");
                                $this->dom->addChild(
                                    $origemRecursos,
                                    "cnpjOrigemRecursos",
                                    $ipj->origemrecursos->cnpjorigemrecursos,
                                    true
                                );
                                $infoProcJud->appendChild($origemRecursos);
                            }
                            $pgtoPF->appendChild($infoProcJud);
                        }
                    }
                    if (!empty($ppf->depjudicial)) {
                        $dj = $ppf->depjudicial;
                        $depJudicial = $this->dom->createElement("depJudicial");
                        $this->dom->addChild(
                            $depJudicial,
                            "vlrDepJudicial",
                            !empty($dj->vlrdepjudicial) ? number_format($dj->vlrdepjudicial, 2, ',', '') : null,
                            false
                        );
                        $pgtoPF->appendChild($depJudicial);
                    }
                    $pgtoResidBR->appendChild($pgtoPF);
                }
            }
            if (!empty($stab->pgtopj)) {
                if (empty($pgtoResidBR)) {
                    $pgtoResidBR = $this->dom->createElement("pgtoResidBR");
                }
                foreach ($stab->pgtopj as $ppj) {
                    $pgtoPJ = $this->dom->createElement("pgtoPJ");
                    $this->dom->addChild(
                        $pgtoPJ,
                        "dtPagto",
                        $ppj->dtpagto,
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPJ,
                        "vlrRendTributavel",
                        number_format($ppj->vlrrendtributavel, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $pgtoPJ,
                        "vlrRet",
                        number_format($ppj->vlrret, 2, ',', ''),
                        true
                    );
                    if (!empty($ppj->infoprocjud)) {
                        foreach ($ppj->infoprocjud as $pj) {
                            $infoProcJud = $this->dom->createElement("infoProcJud");
                            $this->dom->addChild(
                                $infoProcJud,
                                "nrProcJud",
                                $pj->nrprocjud,
                                true
                            );
                            $this->dom->addChild(
                                $infoProcJud,
                                "codSusp",
                                !empty($pj->codsusp) ? $pj->codsusp : null,
                                false
                            );
                            $this->dom->addChild(
                                $infoProcJud,
                                "indOrigemRecursos",
                                $pj->indorigemrecursos,
                                true
                            );
                            if (!empty($pj->despprocjud)) {
                                $dpj = $pj->despprocjud;
                                $despProcJud = $this->dom->createElement("despProcJud");
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespCustas",
                                    number_format($dpj->vlrdespcustas, 2, ',', ''),
                                    true
                                );
                                $this->dom->addChild(
                                    $despProcJud,
                                    "vlrDespAdvogados",
                                    number_format($dpj->vlrdespadvogados, 2, ',', ''),
                                    true
                                );
                                if (!empty($dpj->ideadvogado)) {
                                    foreach ($dpj->ideadvogado as $iad) {
                                        $ideAdvogado = $this->dom->createElement("ideAdvogado");
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "tpInscAdvogado",
                                            $iad->tpinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "nrInscAdvogado",
                                            $iad->nrinscadvogado,
                                            true
                                        );
                                        $this->dom->addChild(
                                            $ideAdvogado,
                                            "vlrAdvogado",
                                            number_format($iad->vlradvogado, 2, ",", ""),
                                            true
                                        );
                                        $despProcJud->appendChild($ideAdvogado);
                                    }
                                }
                                $infoProcJud->appendChild($despProcJud);
                            }
                            if (!empty($pj->origemrecursos)) {
                                $origemRecursos = $this->dom->createElement("origemRecursos");
                                $this->dom->addChild(
                                    $ideAdvogado,
                                    "cnpjOrigemRecursos",
                                    $pj->origemrecursos->cnpjorigemrecursos,
                                    true
                                );
                                $infoProcJud->appendChild($origemRecursos);
                            }
                            $pgtoPJ->appendChild($infoProcJud);
                        }
                    }
                    $pgtoResidBR->appendChild($pgtoPJ);
                }
            }
            if (!empty($pgtoResidBR)) {
                $ideEstab->appendChild($pgtoResidBR);
                $pgtoResidBR = null;
            }
            if (!empty($stab->pgtoresidext)) {
                $ext = $stab->pgtoresidext;
                $pgtoResidExt = $this->dom->createElement("pgtoResidExt");
                $this->dom->addChild(
                    $pgtoResidExt,
                    "dtPagto",
                    $ext->dtpagto,
                    true
                );
                $this->dom->addChild(
                    $pgtoResidExt,
                    "tpRendimento",
                    $ext->tprendimento,
                    true
                );
                $this->dom->addChild(
                    $pgtoResidExt,
                    "formaTributacao",
                    $ext->formatributacao,
                    true
                );
                $this->dom->addChild(
                    $pgtoResidExt,
                    "vlrPgto",
                    number_format($ext->vlrpgto, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $pgtoResidExt,
                    "vlrRet",
                    number_format($ext->vlrret, 2, ',', ''),
                    true
                );
                $ideEstab->appendChild($pgtoResidExt);
            }
            $infoPgto->appendChild($ideEstab);
        }
        $ideBenef->appendChild($infoPgto);
        $this->node->appendChild($ideBenef);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
