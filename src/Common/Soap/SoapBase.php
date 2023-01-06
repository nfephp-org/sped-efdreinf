<?php

namespace NFePHP\EFDReinf\Common\Soap;

/**
 * Soap base class
 *
 * @category  NFePHP
 * @package   NFePHP\EFDReinf\Common\Soap\SoapBase
 * @copyright NFePHP Copyright (c) 2017 - 2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */

use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\RuntimeException;
use NFePHP\Common\Exception\SoapException;
use NFePHP\Common\Strings;
use NFePHP\Common\Files;


abstract class SoapBase
{
    //constants
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2


    /**
     * @var int
     */
    protected $soapprotocol = self::SSL_DEFAULT;
    /**
     * @var int
     */
    protected $soaptimeout = 20;
    /**
     * @var string
     */
    protected $proxyIP;
    /**
     * @var int
     */
    protected $proxyPort;
    /**
     * @var string
     */
    protected $proxyUser;
    /**
     * @var string
     */
    protected $proxyPass;
    /**
     * @var array
     */
    protected $prefixes = [1 => 'soapenv', 2 => 'soap'];
    /**
     * @var Certificate|null
     */
    protected $certificate;
    /**
     * @var string
     */
    protected $tempdir;
    /**
     * @var string
     */
    protected $certsdir;
    /**
     * @var string
     */
    protected $debugdir;
    /**
     * @var string
     */
    protected $prifile;
    /**
     * @var string
     */
    protected $pubfile;
    /**
     * @var string
     */
    protected $certfile;
    /**
     * @var string
     */
    protected $casefaz;
    /**
     * @var bool
     */
    protected $disablesec = false;
    /**
     * @var bool
     */
    protected $disableCertValidation = false;
    /**
     * @var \NFePHP\Common\Files
     */
    protected $filesystem;
    /**
     * @var string
     */
    protected $temppass = '';
    /**
     * @var bool
     */
    protected $encriptPrivateKey = true;
    /**
     * @var bool
     */
    protected $debugmode = false;
    /**
     * @var string
     */
    public $responseHead;
    /**
     * @var string
     */
    public $responseBody;
    /**
     * @var string
     */
    public $requestHead;
    /**
     * @var string
     */
    public $requestBody;
    /**
     * @var string
     */
    public $soaperror;
    /**
     * @var array
     */
    public $soapinfo = [];
    /**
     * @var int
     */
    public $waitingTime = 45;

    /**
     * Constructor
     * @param Certificate|null $certificate
     */
    public function __construct(Certificate $certificate = null)
    {
        $this->certificate = $this->checkCertValidity($certificate);
        $this->setTemporaryFolder(sys_get_temp_dir() . '/sped/');
    }

    /**
     * Check if certificate is valid
     * @param Certificate|null $certificate
     * @return Certificate|null
     * @throws RuntimeException
     */
    private function checkCertValidity(Certificate $certificate = null)
    {
        if ($this->disableCertValidation) {
            return $certificate;
        }
        if (!empty($certificate)) {
            if ($certificate->isExpired()) {
                throw new RuntimeException(
                    'The validity of the certificate has expired.'
                );
            }
        }
        return $certificate;
    }

    /**
     * Destructor
     * Clean temporary files
     */
    public function __destruct()
    {
        $this->removeTemporarilyFiles();
    }

    /**
     * Disables the security checking of host and peer certificates
     * @param bool $flag
     */
    public function disableSecurity($flag = false)
    {
        $this->disablesec = $flag;
        return $this->disablesec;
    }

    /**
     * ONlY for tests
     * @param bool $flag
     * @return bool
     */
    public function disableCertValidation($flag = true)
    {
        $this->disableCertValidation = $flag;
        return $this->disableCertValidation;
    }

    /**
     * Load path to CA and enable to use on SOAP
     * @param string $capath
     */
    public function loadCA($capath)
    {
        if (is_file($capath)) {
            $this->casefaz = $capath;
        }
    }

    /**
     * Set option to encript private key before save in filesystem
     * for an additional layer of protection
     * @param bool $encript
     * @return bool
     */
    public function setEncriptPrivateKey($encript = true)
    {
        return $this->encriptPrivateKey = $encript;
    }

    /**
     * Set another temporayfolder for saving certificates for SOAP utilization
     * @param string $folderRealPath
     */
    public function setTemporaryFolder($folderRealPath)
    {
        $this->tempdir = $folderRealPath;
        $this->setLocalFolder($folderRealPath);
    }

    /**
     * Set Local folder for flysystem
     * @param string $folder
     */
    protected function setLocalFolder($folder = '')
    {
        $this->filesystem = new Files($folder);
    }

    /**
     * Set debug mode, this mode will save soap envelopes in temporary directory
     * @param bool $value
     * @return bool
     */
    public function setDebugMode($value = false)
    {
        return $this->debugmode = $value;
    }

    /**
     * Set certificate class for SSL comunications
     * @param Certificate $certificate
     * @return void
     */
    public function loadCertificate(Certificate $certificate)
    {
        $this->certificate = $this->checkCertValidity($certificate);
    }

    /**
     * Set timeout for communication
     * @param int $timesecs
     * @return int
     */
    public function timeout(int $timesecs): int
    {
        return $this->soaptimeout = $timesecs;
    }

    /**
     * Set security protocol
     * @param int $protocol
     * @return int
     */
    public function protocol(int $protocol = self::SSL_DEFAULT): int
    {
        return $this->soapprotocol = $protocol;
    }

    /**
     * Set prefixes
     * @param array $prefixes
     * @return array
     */
    public function setSoapPrefix(string $prefixes): string
    {
        return $this->prefixes = $prefixes;
    }

    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     * @return void
     */
    public function proxy($ip, $port, $user, $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;
    }

    /**
     * Set proxy into cURL parameters
     * @param resource $oCurl
     * @return void
     */
    protected function setCurlProxy(&$oCurl)
    {
        if ($this->proxyIP != '') {
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUser.':'.$this->proxyPass);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }


    /**
     * Send message to webservice
     */
    /*
    abstract public function send(
        $operation,
        $url,
        $action,
        $envelope,
        $parameters
    );*/

    /**
     * Temporarily saves the certificate keys for use cURL or SoapClient
     */
    public function saveTemporarilyKeyFiles()
    {
        if (!is_object($this->certificate)) {
            throw new RuntimeException(
                'Certificate not found.'
            );
        }
        $this->certsdir = $this->certificate->getCnpj() . '/certs/';
        $this->prifile = $this->certsdir. Strings::randomString(10).'.pem';
        $this->pubfile = $this->certsdir . Strings::randomString(10).'.pem';
        $this->certfile = $this->certsdir . Strings::randomString(10).'.pem';
        $ret = true;
        $private = $this->certificate->privateKey;
        if ($this->encriptPrivateKey) {
            //cria uma senha temporária ALEATÓRIA para salvar a chave primaria
            //portanto mesmo que localizada e identificada não estará acessível
            //pois sua senha não existe além do tempo de execução desta classe
            $this->temppass = Strings::randomString(16);
            //encripta a chave privada entes da gravação do filesystem
            openssl_pkey_export(
                $this->certificate->privateKey,
                $private,
                $this->temppass
            );
        }
        try {
            $ret &= $this->filesystem->put(
                $this->prifile,
                $private
            );
            $ret &= $this->filesystem->put(
                $this->pubfile,
                $this->certificate->publicKey
            );
            $ret &= $this->filesystem->put(
                $this->certfile,
                $private . "{$this->certificate}"
            );
        } catch(\Exception $e) {
            throw new SoapException(
                'Falha ao salvar temporariamente o certificado. ' . $e->getMessage()
            );
        }
    }

    /**
     * Delete all files in folder
     */
    public function removeTemporarilyFiles()
    {
        $contents = $this->filesystem->listContents($this->certsdir, true);
        //define um limite de $waitingTime min, ou seja qualquer arquivo criado a mais
        //de $waitingTime min será removido
        //NOTA: quando ocorre algum erro interno na execução do script, alguns
        //arquivos temporários podem permanecer
        //NOTA: O tempo default é de 45 minutos e pode ser alterado diretamente nas
        //propriedades da classe, esse tempo entre 5 a 45 min é recomendável pois
        //podem haver processos concorrentes para um mesmo usuário. Esses processos
        //como o DFe podem ser mais longos, dependendo a forma que o aplicativo
        //utilize a API. Outra solução para remover arquivos "perdidos" pode ser
        //encontrada oportunamente.
        $dt = new \DateTime();
        $tint = new \DateInterval("PT".$this->waitingTime."M");
        $tint->invert = 1;
        $tsLimit = $dt->add($tint)->getTimestamp();
        foreach ($contents as $item) {
            if ($item['type'] === 'file') {
                if ($item['path'] == $this->prifile
                    || $item['path'] == $this->pubfile
                    || $item['path'] == $this->certfile
                ) {
                    $this->filesystem->delete($item['path']);
                    continue;
                }
                $timestamp = $this->filesystem->getTimestamp($item['path']);
                if ($timestamp < $tsLimit) {
                    //remove arquivos criados a mais de 45 min
                    $this->filesystem->delete($item['path']);
                }
            }
        }
    }

    /**
     * Save request envelope and response for debug reasons
     * @param string $operation
     * @param string $request
     * @param string $response
     * @return void
     */
    public function saveDebugFiles($operation, $request, $response)
    {
        if (!$this->debugmode) {
            return;
        }
        $cnpj = '';
        if (!empty($this->certificate)) {
            $cnpj = $this->certificate->getCnpj();
        }
        $this->debugdir = $cnpj() . '/debug/';
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        $time = substr($now->format("ymdHisu"), 0, 16);
        try {
            $this->filesystem->put(
                $this->debugdir . $time . "_" . $operation . "_sol.txt",
                $request
            );
            $this->filesystem->put(
                $this->debugdir . $time . "_" . $operation . "_res.txt",
                $response
            );
        } catch (\Exception $e) {
            throw new RuntimeException(
                'Unable to create debug files.'
            );
        }
    }
}
