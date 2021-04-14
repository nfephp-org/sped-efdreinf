<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Factory interface
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
use NFePHP\Common\Certificate;

interface FactoryInterface
{
    public function alias();

    public function toXML();

    public function toJson();

    public function toStd();

    public function toArray();

    public function getId();

    public function getCertificate();

    public function setCertificate(Certificate $certificate);
}
