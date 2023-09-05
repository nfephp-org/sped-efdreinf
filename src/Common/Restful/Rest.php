<?php

namespace NFePHP\EFDReinf\Common\Restful;

use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\SoapException;
use NFePHP\EFDReinf\Common\Soap\SoapBase;

class Rest extends SoapBase implements RestInterface
{
    public function __construct(Certificate $certificate = null)
    {
        parent::__construct($certificate);
    }

    public function sendApi(string $method, string $url, string $content)
    {
        $msgSize = strlen($content);
        $parameters = [
            "Content-Type: application/xml",
            "Content-length: $msgSize"
        ];
        try {
            $this->saveTemporarilyKeyFiles();
            $oCurl = curl_init();
            $this->setCurlProxy($oCurl);
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($oCurl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soaptimeout);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $this->soaptimeout + 20);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->soapprotocol);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->tempdir . $this->certfile);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->tempdir . $this->prifile);
            if (!empty($this->temppass)) {
                curl_setopt($oCurl, CURLOPT_KEYPASSWD, $this->temppass);
            }
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
            if ($method === 'POST') {
                curl_setopt($oCurl, CURLOPT_POST, true);
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $content);
                //curl_setopt($oCurl, CURLOPT_HEADER, 0);
                curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parameters);
            } else {
                curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, 'GET');
            }
            $response = curl_exec($oCurl);
            $this->soaperror = curl_error($oCurl);
            $num = curl_errno($oCurl);
            $ainfo = curl_getinfo($oCurl);
            if (is_array($ainfo)) {
                $this->soapinfo = $ainfo;
            }
            $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
            $httpcode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
            curl_close($oCurl);
            $this->responseBody = $response;
        } catch (\Exception $e) {
            throw SoapException::unableToLoadCurl($e->getMessage());
        }
        if ($this->soaperror != '') {
            throw SoapException::soapFault($this->soaperror . " [$url]", $num);
        }
        if (in_array($httpcode, [200, 201, 422])) {
            return $this->responseBody;
        }
        if (in_array($httpcode, [495, 496])) {
            throw SoapException::soapFault("ERRO $httpcode : "
                . "Certificado INVALIDO (não reconhecido, expirado ou revogado)", $httpcode);
        }
        throw SoapException::soapFault(
            " [$url] HTTP Error code: $httpcode - {$this->responseBody}",
            $httpcode
        );
    }
}
