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
use NFePHP\Common\Strings;
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
        if ($this->std->modo == 'INC') {
            $this->std->fimvalid = null;
        }
        if ($this->std->modo == 'ALT' && empty($this->std->fimvalid)) {
            throw new \Exception("Numa alteração é obrigatório informar o FIM da VALIDADE do evento anterior.");
        }
        $this->dom->addChild(
            $idePeriodo,
            "fimValid",
            !empty($this->std->fimvalid) ? $this->std->fimvalid : null,
            false
        );
        $infocadastro = null;
        if ($this->std->modo != 'EXC' && empty($this->std->infocadastro)) {
            throw new \Exception("Nos modos de INCLUSÃO e ALTERAÇÃO os dados de cadastro são OBRIGATÓRIOS");
        }
        if (!empty($this->std->infocadastro) && $this->std->modo != 'EXC') {
            //se existe infocadastro e o modo não é exclusao
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
            if ($this->tpInsc == 2) {
                $cad->indsitpj = null;
            }
            $this->dom->addChild(
                $infocadastro,
                "indSitPJ",
                isset($cad->indsitpj) ? $cad->indsitpj : null,
                false
            );
            $contato = $this->dom->createElement("contato");
            $this->dom->addChild(
                $contato,
                "nmCtt",
                Strings::replaceUnacceptableCharacters($cad->contato->nmctt),
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
                    ? Strings::replaceUnacceptableCharacters(
                        strtolower($cad->contato->email)
                    )
                    : null,
                false
            );
            $infocadastro->appendChild($contato);
            if (!empty($this->std->softhouse)) {
                foreach ($this->std->softhouse as $soft) {
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
                        Strings::replaceUnacceptableCharacters($soft->nmrazao),
                        true
                    );
                    $this->dom->addChild(
                        $softhouse,
                        "nmCont",
                        Strings::replaceUnacceptableCharacters($soft->nmcont),
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
                        !empty($soft->email) ? Strings::replaceUnacceptableCharacters($soft->email) : null,
                        false
                    );
                    $infocadastro->appendChild($softhouse);
                }
            }
            if (!empty($this->std->infoefr)) {
                $infoEFR = $this->dom->createElement("infoEFR");
                $this->dom->addChild(
                    $infoEFR,
                    "ideEFR",
                    $this->std->infoefr->ideefr,
                    true
                );
                $this->dom->addChild(
                    $infoEFR,
                    "cnpjEFR",
                    !empty($this->std->infoefr->cnpjefr) ? $this->std->infoefr->cnpjefr : null,
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
            if (empty($this->std->novavalidade)) {
                throw new \Exception("Numa alteração é obrigatório indicar o inicio da NOVA validade.");
            }
            if (!empty($this->std->novavalidade)) {
                $new = $this->std->novavalidade;
                $nval = $this->dom->createElement("novaValidade");
                $this->dom->addChild(
                    $nval,
                    "iniValid",
                    $new->inivalid,
                    true
                );
                $this->dom->addChild(
                    $nval,
                    "fimValid",
                    !empty($new->fimvalid) ? $new->fimvalid : null,
                    false
                );
                $modo->appendChild($nval);
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
