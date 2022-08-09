<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtEspDesportivo Event R-3010 constructor
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
use NFePHP\Common\Strings;
use stdClass;

class EvtEspDesportivo extends Factory implements FactoryInterface
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
        $data = null
    ) {
        $params = new \stdClass();
        $params->evtName = 'evtEspDesportivo';
        $params->evtTag = 'evtEspDesportivo';
        $params->evtAlias = 'R-3010';
        parent::__construct($config, $std, $params, $certificate, $data);
    }

    /**
     * Node constructor
     */
    protected function toNode()
    {
        $ideContri = $this->node->getElementsByTagName('ideContri')->item(0);
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
            "dtApuracao",
            $this->std->dtapuracao,
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
            "1",
            true
        );
        $this->dom->addChild(
            $ideEstab,
            "nrInscEstab",
            $this->std->nrinscestab,
            true
        );

        foreach ($this->std->boletim as $bo) {
            $boletim = $this->dom->createElement("boletim");
            $this->dom->addChild(
                $boletim,
                "nrBoletim",
                $bo->nrboletim,
                true
            );
            $this->dom->addChild(
                $boletim,
                "tpCompeticao",
                $bo->tpcompeticao,
                true
            );
            $this->dom->addChild(
                $boletim,
                "categEvento",
                $bo->categevento,
                true
            );
            $this->dom->addChild(
                $boletim,
                "modDesportiva",
                Strings::replaceUnacceptableCharacters($bo->moddesportiva),
                true
            );
            $this->dom->addChild(
                $boletim,
                "nomeCompeticao",
                Strings::replaceUnacceptableCharacters($bo->nomecompeticao),
                true
            );
            $this->dom->addChild(
                $boletim,
                "cnpjMandante",
                $bo->cnpjmandante,
                true
            );
            $this->dom->addChild(
                $boletim,
                "cnpjVisitante",
                !empty($bo->cnpjvisitante) ? $bo->cnpjvisitante : null,
                true
            );
            $this->dom->addChild(
                $boletim,
                "nomeVisitante",
                !empty($bo->nomevisitante) ?  Strings::replaceUnacceptableCharacters($bo->nomevisitante) : null,
                true
            );
            $this->dom->addChild(
                $boletim,
                "pracaDesportiva",
                Strings::replaceUnacceptableCharacters($bo->pracadesportiva),
                true
            );
            $this->dom->addChild(
                $boletim,
                "codMunic",
                !empty($bo->codmunic) ? $bo->codmunic : null,
                true
            );
            $this->dom->addChild(
                $boletim,
                "uf",
                $bo->uf,
                true
            );
            $this->dom->addChild(
                $boletim,
                "qtdePagantes",
                $bo->qtdepagantes,
                true
            );
            $this->dom->addChild(
                $boletim,
                "qtdeNaoPagantes",
                $bo->qtdenaopagantes,
                true
            );

            foreach ($bo->receitaingressos as $rec) {
                $recIng = $this->dom->createElement("receitaIngressos");
                $this->dom->addChild(
                    $recIng,
                    "tpIngresso",
                    $rec->tpingresso,
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "descIngr",
                    Strings::replaceUnacceptableCharacters($rec->descingr),
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "qtdeIngrVenda",
                    $rec->qtdeingrvenda,
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "qtdeIngrVendidos",
                    $rec->qtdeingrvendidos,
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "qtdeIngrDev",
                    $rec->qtdeingrdev,
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "precoIndiv",
                    number_format($rec->precoindiv, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $recIng,
                    "vlrTotal",
                    number_format($rec->vlrtotal, 2, ',', ''),
                    true
                );
                $boletim->appendChild($recIng);
            }

            if (!empty($bo->outrasreceitas)) {
                foreach ($bo->outrasreceitas as $or) {
                    $oRec = $this->dom->createElement("outrasReceitas");
                    $this->dom->addChild(
                        $oRec,
                        "tpReceita",
                        $or->tpreceita,
                        true
                    );
                    $this->dom->addChild(
                        $oRec,
                        "vlrReceita",
                        number_format($or->vlrreceita, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $oRec,
                        "descReceita",
                        Strings::replaceUnacceptableCharacters($or->descreceita),
                        true
                    );
                    $boletim->appendChild($oRec);
                }
            }
            $ideEstab->appendChild($boletim);
        }
        $rt = $this->std->receitatotal;
        $recTot = $this->dom->createElement("receitaTotal");
        $this->dom->addChild(
            $recTot,
            "vlrReceitaTotal",
            number_format($rt->vlrreceitatotal, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $recTot,
            "vlrCP",
            number_format($rt->vlrcp, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $recTot,
            "vlrCPSuspTotal",
            !empty($rt->vlrcpsusptotal) ? number_format($rt->vlrcpsusptotal, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $recTot,
            "vlrReceitaClubes",
            number_format($rt->vlrreceitaclubes, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $recTot,
            "vlrRetParc",
            number_format($rt->vlrretparc, 2, ',', ''),
            true
        );
        if (!empty($rt->infoproc)) {
            foreach ($rt->infoproc as $ifp) {
                $infoProc = $this->dom->createElement("infoProc");
                $this->dom->addChild(
                    $infoProc,
                    "tpProc",
                    $ifp->tpproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "nrProc",
                    $ifp->nrproc,
                    true
                );
                $this->dom->addChild(
                    $infoProc,
                    "codSusp",
                    !empty($ifp->codsusp) ? $ifp->codsusp : null,
                    false
                );
                $this->dom->addChild(
                    $infoProc,
                    "vlrCPSusp",
                    number_format($ifp->vlrcpsusp, 2, ',', ''),
                    true
                );
                $recTot->appendChild($infoProc);
            }
            $ideEstab->appendChild($recTot);
            $ideContri->appendChild($ideEstab);
        }
        $this->node->appendChild($ideContri);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
