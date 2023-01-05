<?php

namespace NFePHP\EFDReinf\Common\Restful;

use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;

interface RestInterface
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
     * @return int
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
     */
    public function proxy($ip, $port, $user, $password);


    public function sendApi(string $method, string $url, string $content);
}
