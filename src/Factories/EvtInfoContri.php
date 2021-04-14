<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtInfoContri Event R-1000 constructor
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

class EvtInfoContri extends Factory implements FactoryInterface
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
        $params->evtName = 'evtInfoContribuinte';
        $params->evtTag = 'evtInfoContri';
        $params->evtAlias = 'R-1000';
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
        //tag deste evento em particular
        $infoContri = $this->dom->createElement("infoContri");
        //se aplica a todos os modos
        $idePeriodo = $this->dom->createElement("idePeriodo");
        $this->dom->addChild(
            $idePeriodo,
            "iniValid",
            $this->std->inivalid,
            true
        );
        $this->dom->addChild(
            $idePeriodo,
            "fimValid",
            !empty($this->std->fimvalid) ? $this->std->fimvalid : null,
            false
        );
        $infocadastro = null;
        if (!empty($this->std->infocadastro)) {
            $cad = $this->std->infocadastro;
            $infocadastro = $this->dom->createElement("infoCadastro");
            $this->dom->addChild(
                $infocadastro,
                "classTrib",
                $cad->classtrib,
                true
            );
            $this->dom->addChild(
                $infocadastro,
                "indEscrituracao",
                $cad->indescrituracao,
                true
            );
            $this->dom->addChild(
                $infocadastro,
                "indDesoneracao",
                $cad->inddesoneracao,
                true
            );
            $this->dom->addChild(
                $infocadastro,
                "indAcordoIsenMulta",
                $cad->indacordoisenmulta,
                true
            );
            $indsitpj = null;
            if (isset($cad->indsitpj)) {
                $indsitpj = $cad->indsitpj;
            }
            $this->dom->addChild(
                $infocadastro,
                "indSitPJ",
                $indsitpj,
                false
            );
            $contato = $this->dom->createElement("contato");
            $this->dom->addChild(
                $contato,
                "nmCtt",
                $cad->contato->nmctt,
                true
            );
            $this->dom->addChild(
                $contato,
                "cpfCtt",
                $cad->contato->cpfctt,
                true
            );
            $this->dom->addChild(
                $contato,
                "foneFixo",
                !empty($cad->contato->fonefixo)
                    ? $cad->contato->fonefixo
                    : null,
                false
            );
            $this->dom->addChild(
                $contato,
                "foneCel",
                !empty($cad->contato->fonecel)
                    ? $cad->contato->fonecel
                    : null,
                false
            );
            $this->dom->addChild(
                $contato,
                "email",
                !empty($cad->contato->email)
                    ? $cad->contato->email
                    : null,
                false
            );
            $infocadastro->appendChild($contato);
            if (!empty($cad->softhouse)) {
                foreach ($cad->softhouse as $soft) {
                    $softhouse = $this->dom->createElement("softHouse");
                    $this->dom->addChild(
                        $softhouse,
                        "cnpjSoftHouse",
                        $soft->cnpjsofthouse,
                        true
                    );
                    $this->dom->addChild(
                        $softhouse,
                        "nmRazao",
                        $soft->nmrazao,
                        true
                    );
                    $this->dom->addChild(
                        $softhouse,
                        "nmCont",
                        $soft->nmcont,
                        true
                    );
                    $this->dom->addChild(
                        $softhouse,
                        "telefone",
                        !empty($soft->telefone) ? $soft->telefone : null,
                        false
                    );
                    $this->dom->addChild(
                        $softhouse,
                        "email",
                        !empty($soft->email) ? $soft->email : null,
                        false
                    );
                    $infocadastro->appendChild($softhouse);
                }
            }
            if (!empty($cad->infoefr)) {
                $infoEFR = $this->dom->createElement("infoEFR");
                $this->dom->addChild(
                    $infoEFR,
                    "ideEFR",
                    $cad->infoefr->ideefr,
                    true
                );
                $this->dom->addChild(
                    $infoEFR,
                    "cnpjEFR",
                    !empty($cad->infoefr->cnpjefr) ? $cad->infoefr->cnpjefr : null,
                    false
                );
                $infocadastro->appendChild($infoEFR);
            }
        }
        if ($this->std->modo == 'INC') {
            $modo = $this->dom->createElement("inclusao");
            $modo->appendChild($idePeriodo);
            if (!empty($infocadastro)) {
                $modo->appendChild($infocadastro);
            }
        } elseif ($this->std->modo == 'ALT') {
            $modo = $this->dom->createElement("alteracao");
            $modo->appendChild($idePeriodo);
            if (!empty($infocadastro)) {
                $modo->appendChild($infocadastro);
            }
        } else {
            $modo = $this->dom->createElement("exclusao");
            $modo->appendChild($idePeriodo);
        }
        //finalização do xml
        $infoContri->appendChild($modo);
        $this->node->appendChild($infoContri);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
