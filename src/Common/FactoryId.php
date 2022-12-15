<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class FactoryId build ID event reference
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

use \DateTime;

class FactoryId
{
    /**
     * Build Id for EFDReinf event
     * @param int $tpInsc
     * @param string $nrInsc
     * @param DateTime|null $date
     * @param int|null $sequential
     * @return string
     */
    public static function build(
        $tpInsc,
        $nrInsc,
        DateTime $date = null,
        $sequential = null
    ) {
        if (empty($sequential)) {
            $mt = str_replace('.', '', (string) microtime(true));
            $sequential = rand(0, 9) . substr($mt, -4);
        }
        if (empty($date)) {
            $date = new DateTime();
        }
        return "ID"
            . $tpInsc
            . str_pad($nrInsc, 14, '0', STR_PAD_RIGHT)
            . $date->format('YmdHis')
            . str_pad($sequential, 5, '0', STR_PAD_LEFT);
    }
}
