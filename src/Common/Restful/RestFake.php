<?php

namespace NFePHP\EFDReinf\Common\Restful;

use NFePHP\EFDReinf\Common\Soap\SoapBase;

class RestFake extends SoapBase implements RestInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param string $content
     * @return string
     */
    public function sendApi(string $method, string $url, string $content): string
    {
        return json_encode([
            'url' => $url,
            'method' => $method,
            'body' => !empty($content) ? base64_encode($content) : ''
        ], JSON_PRETTY_PRINT);
    }
}
