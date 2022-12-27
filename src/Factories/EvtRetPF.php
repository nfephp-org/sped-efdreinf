<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtRetCons Event R-4010 constructor
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
use NFePHP\Common\Certificate;
use NFePHP\EFDReinf\Factories\Traits\FormatNumber;
use stdClass;

class EvtRetPF extends Factory implements FactoryInterface
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
        $params->evtName = 'evt4010PagtoBeneficiarioPF';
        $params->evtTag = 'evtRetPF';
        $params->evtAlias = 'R-4010';
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
        if (!empty($this->std->natjur)) {
            $infoComplContri = $this->dom->createElement('infoComplContri');
            $this->dom->addChild(
                $infoComplContri,
                "natJur",
                $this->std->natjur,
                true
            );
            $ideContri->appendChild($infoComplContri);
        }
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
        $ideBenef = $this->dom->createElement('ideBenef');
        $this->dom->addChild(
            $ideBenef,
            "cpfBenef",
            $this->std->idebenef->cpfbenef ?? null,
            false
        );
        $this->dom->addChild(
            $ideBenef,
            "nmBenef",
            $this->std->idebenef->nmbenef ?? null,
            false
        );
        foreach ($this->std->idedep as $dep) {
            $ideDep = $this->dom->createElement('ideDep');
            $this->dom->addChild(
                $ideDep,
                "cpfDep",
                $dep->cpfdep,
                true
            );
            $this->dom->addChild(
                $ideDep,
                "relDep",
                $dep->reldep,
                true
            );
            $this->dom->addChild(
                $ideDep,
                "descrDep",
                $dep->descdep ?? null,
                false
            );
            $ideBenef->appendChild($ideDep);
        }
        foreach ($this->std->idepgto as $pgto) {
            $idePgto = $this->dom->createElement('idePgto');
            $this->dom->addChild(
                $idePgto,
                "natRend",
                $pgto->natrend,
                true
            );
            $this->dom->addChild(
                $idePgto,
                "observ",
                $pgto->observ ?? null,
                false
            );
            foreach ($pgto->infopgto as $info) {
                $infoPgto = $this->dom->createElement('infoPgto');
                $this->dom->addChild(
                    $infoPgto,
                    "dtFG",
                    $info->dtfg,
                    true
                );
                $this->dom->addChild(
                    $infoPgto,
                    "compFP",
                    $info->compfp ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "indDecTerc",
                    $info->inddecterc ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrRendBruto",
                    self::format($info->vlrrendbruto),
                    true
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrRendTrib",
                    self::format($info->vlrrendtrib),
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "vlrIR",
                    self::format($info->vlrir),
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "indRRA",
                    $info->indrra ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "indFciScp",
                    $info->indfciscp ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "nrInscFciScp",
                    $info->nrinscfciscp ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "percSCP",
                    !empty($info->percscp) ? self::format($info->percscp, 1) : null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "indJud",
                    $info->indjud ?? null,
                    false
                );
                $this->dom->addChild(
                    $infoPgto,
                    "paisResidExt",
                    $info->paisresidext ?? null,
                    false
                );
                foreach ($info->detded as $ded) {
                    $detDed = $this->dom->createElement('detDed');
                    $this->dom->addChild(
                        $detDed,
                        "indTpDeducao",
                        $ded->indtpdeducao,
                        true
                    );
                    $this->dom->addChild(
                        $detDed,
                        "vlrDeducao",
                        self::format($ded->vlrdeducao),
                        true
                    );
                    $this->dom->addChild(
                        $detDed,
                        "infoEntid",
                        $ded->infoentid ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $detDed,
                        "nrInscPrevComp",
                        $ded->nrinscprevcomp ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $detDed,
                        "vlrPatrocFunp",
                        self::format($ded->vlrpatrocfunp),
                        false
                    );
                    foreach ($ded->benefpen as $pen) {
                        $benefPen = $this->dom->createElement('benefPen');
                        $this->dom->addChild(
                            $benefPen,
                            "cpfDep",
                            $pen->cpfdep,
                            true
                        );
                        $this->dom->addChild(
                            $benefPen,
                            "vlrDepen",
                            self::format($pen->vlrdepen),
                            true
                        );
                        $detDed->appendChild($benefPen);
                    }
                    $infoPgto->appendChild($detDed);
                }
                foreach ($info->rendisento as $isento) {
                    $rendIsento = $this->dom->createElement('rendIsento');
                    $this->dom->addChild(
                        $rendIsento,
                        "tpIsencao",
                        $isento->tpisencao,
                        true
                    );
                    $this->dom->addChild(
                        $rendIsento,
                        "vlrIsento",
                        self::format($isento->vlrisento),
                        true
                    );
                    $this->dom->addChild(
                        $rendIsento,
                        "descRendimento",
                        $isento->descrendimento ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $rendIsento,
                        "dtLaudo",
                        $isento->dtlaudo ?? null,
                        false
                    );
                    $infoPgto->appendChild($rendIsento);
                }
                foreach ($info->infoprocret as $ret) {
                    $infoProcRet = $this->dom->createElement('infoProcRet');
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
                        "vlrNRetido",
                        self::format($ret->vlrnretido ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepJud",
                        self::format($ret->vlrdepjud ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrCmpAnoCal",
                        self::format($ret->vlrcmpanocal ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrCmpAnoAnt",
                        self::format($ret->vlrcmpanoant ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrRendSusp",
                        self::format($ret->vlrrendsusp ?? null),
                        false
                    );
                    foreach ($ret->dedsusp as $susp) {
                        $dedSusp = $this->dom->createElement('dedSusp');
                        $this->dom->addChild(
                            $dedSusp,
                            "indTpDeducao",
                            $susp->indtpdeducao,
                            true
                        );
                        $this->dom->addChild(
                            $dedSusp,
                            "vlrDedSusp",
                            self::format($susp->vlrdedsusp ?? null),
                            false
                        );
                        foreach ($susp->benefpen as $bpen) {
                            $benefPen = $this->dom->createElement('benefPen');
                            $this->dom->addChild(
                                $benefPen,
                                "cpfDep",
                                $bpen->cpfdep,
                                true
                            );
                            $this->dom->addChild(
                                $benefPen,
                                "vlrDepenSusp",
                                self::format($bpen->vlrdepensusp),
                                true
                            );
                            $dedSusp->appendChild($benefPen);
                        }
                        $infoProcRet->appendChild($dedSusp);
                    }
                    $infoPgto->appendChild($infoProcRet);
                }
                if (!empty($info->inforra)) {
                    $rra = $info->inforra;
                    $infoRRA = $this->dom->createElement('infoRRA');
                    $this->dom->addChild(
                        $infoRRA,
                        "tpProcRRA",
                        $rra->tpprocrra,
                        true
                    );
                    $this->dom->addChild(
                        $infoRRA,
                        "nrProcRRA",
                        $rra->nrprocrra ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoRRA,
                        "indOrigRec",
                        $rra->indorigrec,
                        true
                    );
                    $this->dom->addChild(
                        $infoRRA,
                        "descRRA",
                        $rra->descrra ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoRRA,
                        "qtdMesesRRA",
                        self::format($rra->qtdmesesrra, 1),
                        true
                    );
                    $this->dom->addChild(
                        $infoRRA,
                        "cnpjOrigRecurso",
                        $rra->cnpjorigrecurso ?? null,
                        false
                    );
                    if (!empty($rra->despprocjud)) {
                        $dpj = $rra->despprocjud;
                        $despProcJud = $this->dom->createElement('despProcJud');
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespCustas",
                            self::format($dpj->vlrdespcustas),
                            true
                        );
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespAdvogados",
                            self::format($dpj->vlrdespadvogados),
                            true
                        );
                        foreach ($dpj->ideadv as $adv) {
                            $ideAdv = $this->dom->createElement('ideAdv');
                            $this->dom->addChild(
                                $ideAdv,
                                "tpInscAdv",
                                $adv->tpinscadv,
                                true
                            );
                            $this->dom->addChild(
                                $ideAdv,
                                "nrInscAdv",
                                $adv->nrinscadv,
                                true
                            );
                            $this->dom->addChild(
                                $ideAdv,
                                "vlrAdv",
                                self::format($adv->vlradv ?? null),
                                false
                            );
                            $despProcJud->appendChild($ideAdv);
                        }
                        $infoRRA->appendChild($despProcJud);
                    }
                    $infoPgto->appendChild($infoRRA);
                }
                if (!empty($info->infoprocjud)) {
                    $jud = $info->infoprocjud;
                    $infoProcJud = $this->dom->createElement('infoProcJud');
                    $this->dom->addChild(
                        $infoProcJud,
                        "nrProc",
                        $jud->nrproc,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcJud,
                        "indOrigRec",
                        $jud->indorigrec,
                        true
                    );
                    $this->dom->addChild(
                        $infoProcJud,
                        "cnpjOrigRecurso",
                        $jud->cnpjorigrecurso ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoProcJud,
                        "desc",
                        $jud->desc ?? null,
                        false
                    );
                    if (!empty($jud->despprocjud)) {
                        $djud = $jud->despprocjud;
                        $despProcJud = $this->dom->createElement('despProcJud');
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespCustas",
                            self::format($djud->vlrdespcustas),
                            true
                        );
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespAdvogados",
                            self::format($djud->vlrdespadvogados),
                            true
                        );
                        foreach ($djud->ideadv as $adv) {
                            $ideAdv = $this->dom->createElement('ideAdv');
                            $this->dom->addChild(
                                $ideAdv,
                                "tpInscAdv",
                                $adv->tpinscadv,
                                true
                            );
                            $this->dom->addChild(
                                $ideAdv,
                                "nrInscAdv",
                                $adv->nrinscadv,
                                true
                            );
                            $this->dom->addChild(
                                $ideAdv,
                                "vlrAdv",
                                self::format($adv->vlradv ?? null),
                                false
                            );
                            $despProcJud->appendChild($ideAdv);
                        }
                        $infoProcJud->appendChild($despProcJud);
                    }
                    $infoPgto->appendChild($infoProcJud);
                }
                if (!empty($info->infopgtoext)) {
                    $ext = $info->infopgtoext;
                    $infoPgtoExt = $this->dom->createElement('infoPgtoExt');
                    $this->dom->addChild(
                        $infoPgtoExt,
                        "indNIF",
                        $ext->indnif,
                        true
                    );
                    $this->dom->addChild(
                        $infoPgtoExt,
                        "nifBenef",
                        $ext->nifbenef ?? null,
                        false
                    );
                    $this->dom->addChild(
                        $infoPgtoExt,
                        "frmTribut",
                        $ext->frmtribut,
                        true
                    );
                    if (!empty($ext->endext)) {
                        $end = $ext->endext;
                        $endExt = $this->dom->createElement('endExt');
                        $this->dom->addChild(
                            $endExt,
                            "dscLograd",
                            $end->dsclograd ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "nrLograd",
                            $end->nrlograd ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "complem",
                            $end->complem ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "bairro",
                            $end->bairro ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "cidade",
                            $end->cidade ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "estado",
                            $end->estado ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "codPostal",
                            $end->codpostal ?? null,
                            false
                        );
                        $this->dom->addChild(
                            $endExt,
                            "telef",
                            $end->telef ?? null,
                            false
                        );
                        $infoPgtoExt->appendChild($endExt);
                    }
                    $infoPgto->appendChild($infoPgtoExt);
                }
                $idePgto->appendChild($infoPgto);
            }
            $ideBenef->appendChild($idePgto);
        }
        foreach ($this->std->ideopsaude as $sau) {
            $ideOpSaude = $this->dom->createElement('ideOpSaude');
            $this->dom->addChild(
                $ideOpSaude,
                "nrInsc",
                $sau->nrinsc,
                true
            );
            $this->dom->addChild(
                $ideOpSaude,
                "regANS",
                $sau->regans ?? null,
                false
            );
            $this->dom->addChild(
                $ideOpSaude,
                "vlrSaude",
                self::format($sau->vlrsaude),
                true
            );
            foreach ($sau->inforeemb as $reem) {
                $infoReemb = $this->dom->createElement('infoReemb');
                $this->dom->addChild(
                    $infoReemb,
                    "tpInsc",
                    $reem->tpinsc,
                    true
                );
                $this->dom->addChild(
                    $infoReemb,
                    "nrInsc",
                    $reem->nrinsc,
                    true
                );
                $this->dom->addChild(
                    $infoReemb,
                    "vlrReemb",
                    self::format($reem->vlrreemb ?? null),
                    false
                );
                $this->dom->addChild(
                    $infoReemb,
                    "vlrReembAnt",
                    self::format($reem->vlrreembant ?? null),
                    false
                );
                $ideOpSaude->appendChild($infoReemb);
            }
            foreach ($sau->infodependpl as $dpl) {
                $infoDependPl = $this->dom->createElement('infoDependPl');
                $this->dom->addChild(
                    $infoDependPl,
                    "cpfDep",
                    $dpl->cpfdep,
                    true
                );
                $this->dom->addChild(
                    $infoDependPl,
                    "vlrSaude",
                    self::format($dpl->vlrsaude),
                    true
                );
                foreach ($dpl->inforeembdep as $reedep) {
                    $infoReembDep = $this->dom->createElement('infoReembDep');
                    $this->dom->addChild(
                        $infoReembDep,
                        "tpInsc",
                        $reedep->tpinsc,
                        true
                    );
                    $this->dom->addChild(
                        $infoReembDep,
                        "nrInsc",
                        $reedep->nrinsc,
                        true
                    );
                    $this->dom->addChild(
                        $infoReembDep,
                        "vlrReemb",
                        self::format($reedep->vlrreemb ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoReembDep,
                        "vlrReembAnt",
                        self::format($reedep->vlrreembant ?? null),
                        false
                    );
                    $infoDependPl->appendChild($infoReembDep);
                }
                $ideOpSaude->appendChild($infoDependPl);
            }
            $ideBenef->appendChild($ideOpSaude);
        }
        //finalização do xml
        $ideEstab->appendChild($ideBenef);
        $this->node->appendChild($ideEstab);
        //$this->node->appendChild($ideBenef);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
