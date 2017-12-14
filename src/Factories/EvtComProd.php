<?php

namespace NFePHP\EFDReinf\Factories;

/**
 * Class EFD-Reinf EvtComProd Event R-2050 constructor
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

class EvtComProd extends Factory implements FactoryInterface
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
        parent::__construct($config, $std, $certificate, $date);
        
        //NOTA: indica o XSD e o namespace do XML
        $this->evtName = 'evtComProd';
        $this->evtTag = 'evtComProd';
        $this->evtAlias = 'R-2050';
    }
    
    /**
     * Node constructor
     */
    protected function toNode()
    {
    }
}
