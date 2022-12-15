<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtFechaEvPer Event R-2099 constructor
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
use NFePHP\EFDReinf\Factories\Traits\RegraNomeValido;
use stdClass;

class EvtFechaEvPer extends Factory implements FactoryInterface
{
    use RegraNomeValido;

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
        $params->evtName = 'evtFechamento';
        $params->evtTag = 'evtFechaEvPer';
        $params->evtAlias = 'R-2099';
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
        if (!empty($this->std->iderespinf)) {
            $ide = $this->std->iderespinf;
            $ideRespInf = $this->dom->createElement("ideRespInf");
            //aplica TRAIT RegraNomeValido
            $nome = self::validateName($ide->nmresp);
            $this->dom->addChild(
                $ideRespInf,
                "nmResp",
                $nome,
                true
            );
            $this->dom->addChild(
                $ideRespInf,
                "cpfResp",
                $ide->cpfresp,
                true
            );
            $this->dom->addChild(
                $ideRespInf,
                "telefone",
                !empty($ide->telefone) ? $ide->telefone : null,
                false
            );
            $this->dom->addChild(
                $ideRespInf,
                "email",
                !empty($ide->email)
                    ? Strings::replaceUnacceptableCharacters(
                        strtolower($ide->email)
                    )
                    : null,
                false
            );
            $this->node->appendChild($ideRespInf);
        }
        $infoFech = $this->dom->createElement("infoFech");
        $this->dom->addChild(
            $infoFech,
            "evtServTm",
            $this->std->evtservtm,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtServPr",
            $this->std->evtservpr,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtAssDespRec",
            $this->std->evtassdesprec,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtAssDespRep",
            $this->std->evtassdesprep,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtComProd",
            $this->std->evtcomprod,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtCPRB",
            $this->std->evtcprb,
            true
        );
        $this->dom->addChild(
            $infoFech,
            "evtAquis",
            $this->std->evtaquis,
            true
        );
        //estes campos não existem na versão 2.1.1
        if ($this->config->eventoVersion === '1_05_01') {
            if (!empty($this->std->evtpgtos)) {
                $this->dom->addChild(
                    $infoFech,
                    "evtPgtos",
                    $this->std->evtpgtos,
                    true
                );
            }
            $this->dom->addChild(
                $infoFech,
                "compSemMovto",
                !empty($this->std->compsemmovto) ? $this->std->compsemmovto : null,
                false
            );
        }
        $this->node->appendChild($infoFech);
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
