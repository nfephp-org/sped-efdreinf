<?php

namespace NFePHP\EFDReinf\Common\Soap;

/**
 * Soap fake class used for development only
 *
 * @category  NFePHP
 * @package   NFePHP\EFDReinf\Common\Soap\SoapFake
 * @copyright NFePHP Copyright (c) 2017 - 2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */

class SoapFake extends SoapBase implements SoapInterface
{

    /**
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
    ): string {
        $requestHead = implode("\n", $parameters);
        $requestBody = base64_encode($envelope);
        return json_encode([
            'url' => $url,
            'operation' => $operation,
            'action' => $action,
            'soapver' => '1.1',
            'parameters' => $parameters,
            'header' => $requestHead,
            'body' => $requestBody
        ], JSON_PRETTY_PRINT);
    }
}
