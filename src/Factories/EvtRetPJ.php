<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtRetCons Event R-4020 constructor
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
use NFePHP\EFDReinf\Factories\Traits\RegraNomeValido;
use stdClass;

class EvtRetPJ extends Factory implements FactoryInterface
{
    use FormatNumber, RegraNomeValido;

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
        $params->evtName = 'evt4020PagtoBeneficiarioPJ';
        $params->evtTag = 'evtRetPJ';
        $params->evtAlias = 'R-4020';
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
            "cnpjBenef",
            $this->std->idebenef->cnpjbenef ?? null,
            false
        );
        $nome = self::validateName($this->std->idebenef->nmbenef ?? null);
        $this->dom->addChild(
            $ideBenef,
            "nmfBenef",
            $nome,
            false
        );
        $this->dom->addChild(
            $ideEstab,
            "isenImun",
            $this->std->idebenef->isenimun,
            true
        );
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
                    "vlrBruto",
                    self::format($info->vlrbruto),
                    true
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
                    self::format($info->percscp ?? null, 0),
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
                if (!empty($info->retencoes)) {
                    $retencoes = $this->dom->createElement('retencoes');
                    $this->dom->addChild(
                        $retencoes,
                        "vlrBaseIR",
                        self::format($info->retencoes->vlrbaseir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrIR",
                        self::format($info->retencoes->vlrir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrBaseAgreg",
                        self::format($info->retencoes->vlrbaseagreg ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrAgreg",
                        self::format($info->retencoes->vlragreg ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrBaseCSLL",
                        self::format($info->retencoes->vlrbasecsll ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrCSLL",
                        self::format($info->retencoes->vlrcsll ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrBaseCofins",
                        self::format($info->retencoes->vlrbasecofins ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrCofins",
                        self::format($info->retencoes->vlrcofins ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrBasePP",
                        self::format($info->retencoes->vlrbasepp ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $retencoes,
                        "vlrPP",
                        self::format($info->retencoes->vlrpp ?? null),
                        false
                    );
                    $infoPgto->appendChild($retencoes);
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
                        "vlrBaseSuspIR",
                        self::format($ret->vlrbasesuspir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNIR",
                        self::format($ret->vlrnir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepIR",
                        self::format($ret->vlrdepir ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrBaseSuspCSLL",
                        self::format($ret->vlrbasesuspcsll ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNCSLL",
                        self::format($ret->vlrncsll ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepCSLL",
                        self::format($ret->vlrdepcsll ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrBaseSuspCofins",
                        self::format($ret->vlrbasesuspcofins ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNCofins",
                        self::format($ret->vlrncofins ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepCofins",
                        self::format($ret->vlrdepcofins ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrBaseSuspPP",
                        self::format($ret->vlrbasesusppp ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrNPP",
                        self::format($ret->vlrnpp ?? null),
                        false
                    );
                    $this->dom->addChild(
                        $infoProcRet,
                        "vlrDepPP",
                        self::format($ret->vlrdeppp ?? null),
                        false
                    );
                    $infoPgto->appendChild($infoProcRet);
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
                        $des = $jud->despprocjud;
                        $despProcJud = $this->dom->createElement('despProcJud');
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespCustas",
                            self::format($des->vlrdespcustas),
                            true
                        );
                        $this->dom->addChild(
                            $despProcJud,
                            "vlrDespAdvogados",
                            self::format($des->vlrdespadvogados),
                            true
                        );
                        foreach ($des->ideadv as $adv) {
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
                        "relFontPg",
                        $ext->relfontpg,
                        true
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
        $this->node->appendChild($ideBenef);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
