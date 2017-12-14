<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtTabProcesso Event R-1070 constructor
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

class EvtTabProcesso extends Factory implements FactoryInterface
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
        $params->evtName = 'evtTabProcesso';
        $params->evtTag = 'evtTabProcesso';
        $params->evtAlias = 'R-1070';
        parent::__construct($config, $std, $params, $certificate, $date);
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
        $info = $this->dom->createElement("infoProcesso");
        
        $ideProcesso  = $this->dom->createElement("ideProcesso");
        $this->dom->addChild(
            $ideProcesso,
            "tpProc",
            $this->std->tpproc,
            true
        );
        $this->dom->addChild(
            $ideProcesso,
            "nrProc",
            $this->std->nrproc,
            true
        );
        
        if ($this->std->modo == 'INC') {
            //inclusao
            $node = $this->dom->createElement("inclusao");
            $this->dom->addChild(
                $ideProcesso,
                "iniValid",
                $this->std->inivalid,
                true
            );
            $this->dom->addChild(
                $ideProcesso,
                "fimValid",
                !empty($this->std->fimvalid) ? $this->std->fimvalid : null,
                false
            );
            $this->dom->addChild(
                $ideProcesso,
                "indAutoria",
                !empty($this->std->indautoria) ? $this->std->indautoria : null,
                false
            );
        } elseif ($this->std->modo == 'ALT') {
            //alteracao
            $node = $this->dom->createElement("alteracao");
            $this->dom->addChild(
                $ideProcesso,
                "indAutoria",
                !empty($this->std->indautoria) ? $this->std->indautoria : null,
                false
            );
            $this->dom->addChild(
                $ideProcesso,
                "iniValid",
                $this->std->inivalid,
                true
            );
            $this->dom->addChild(
                $ideProcesso,
                "fimValid",
                !empty($this->std->fimvalid) ? $this->std->fimvalid : null,
                false
            );
        } else {
            //exclusao
            $node = $this->dom->createElement("exclusao");
            $this->dom->addChild(
                $ideProcesso,
                "iniValid",
                $this->std->inivalid,
                true
            );
            $this->dom->addChild(
                $ideProcesso,
                "fimValid",
                !empty($this->std->fimvalid) ? $this->std->fimvalid : null,
                false
            );
        }
        foreach ($this->std->infosusp as $i) {
            $infosusp = $this->dom->createElement("infoSusp");
            $this->dom->addChild(
                $infosusp,
                "codSusp",
                !empty($i->codsusp) ? $i->codsusp : null,
                false
            );
            $this->dom->addChild(
                $infosusp,
                "indSusp",
                $i->indsusp,
                true
            );
            $this->dom->addChild(
                $infosusp,
                "dtDecisao",
                $i->dtdecisao,
                true
            );
            $this->dom->addChild(
                $infosusp,
                "indDeposito",
                $i->inddeposito,
                true
            );
            if ($this->std->modo !== 'EXC') {
                $ideProcesso->appendChild($infosusp);
            }
        }
        if (!empty($this->std->dadosprocjud)) {
            $dadosProcJud = $this->dom->createElement("dadosProcJud");
            $d = $this->std->dadosprocjud;
            $this->dom->addChild(
                $dadosProcJud,
                "ufVara",
                $d->ufvara,
                true
            );
            $this->dom->addChild(
                $dadosProcJud,
                "codMunic",
                !empty($d->codmunic) ? $d->codmunic : null,
                false
            );
            $this->dom->addChild(
                $dadosProcJud,
                "idVara",
                $d->idvara,
                true
            );
            if ($this->std->modo !== 'EXC') {
                $ideProcesso->appendChild($dadosProcJud);
            }
        }
        $node->appendChild($ideProcesso);
        
        if (!empty($this->std->novavalidade) && $this->std->modo == 'ALT') {
            $novaValidade = $this->dom->createElement("novaValidade");
            $n = $this->std->novavalidade;
            $this->dom->addChild(
                $novaValidade,
                "iniValid",
                $n->inivalid,
                true
            );
            $this->dom->addChild(
                $novaValidade,
                "fimValid",
                !empty($n->fimvalid) ? $n->fimvalid : null,
                false
            );
            $node->appendChild($novaValidade);
        }
        
        $info->appendChild($node);
        
        $this->node->appendChild($info);
        
        $this->reinf->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->reinf);
        $this->sign($this->evtTag);
    }
}
