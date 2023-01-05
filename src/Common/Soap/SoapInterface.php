<?php

namespace NFePHP\EFDReinf\Common\Soap;

/**
 * Soap class interface
 *
 * @category  NFePHP
 * @package   NFePHP\EFDReinf\Common\Soap\SoapInterface
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */

use NFePHP\Common\Certificate;

interface SoapInterface
{
    /**
     *
     * @param Certificate $certificate
     * @return void
     */
    public function loadCertificate(Certificate $certificate);

    /**
     * Set timeout for connection
     * @param int $timesecs
     */
    public function timeout(int $timesecs): int;

    /**
     * Set security protocol for soap communications
     * @param int $protocol
     */
    public function protocol(int $protocol = 0): int;

    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     * @return void
     */
    public function proxy(string $ip, int $port, string $user, string $password);

    /**
     * Send soap message
     * @param string $operation
     * @param string $url
     * @param string $action
     * @param string $envelope
     * @param array $parameters
     * @return string
     */
    public function send(
        string $operation,
        string $url,
        string $action,
        string $envelope,
        array $parameters
    ): string;
}
