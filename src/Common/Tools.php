<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class Common\Tools, basic structures
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
use NFePHP\EFDReinf\Common\Restful\Rest;
use NFePHP\EFDReinf\Common\Soap\SoapCurl;
use DateTime;
use stdClass;

class Tools
{
    const EVT_INICIAIS = 1;
    const EVT_NAO_PERIODICOS = 2;
    const EVT_PERIODICOS = 3;
    const EVT_FINAIS = 4;

    /**
     * @var string
     */
    protected $path;
    /**
     * @var DateTime
     */
    protected $date;
    /**
     * @var bool
     */
    protected $admpublica = false;
    /**
     * @var int
     */
    protected $tpInsc;
    /**
     * @var string
     */
    protected $nrInsc;
    /**
     * @var string
     */
    protected $doc;
    /**
     * @var string
     */
    protected $nmRazao;
    /**
     * @var int
     */
    protected $tpAmb;
    /**
     * @var string
     */
    protected $verProc;
    /**
     * @var string
     */
    protected $eventoVersion;
    /**
     * @var string
     */
    protected $serviceVersion;
    /**
     * @var string
     */
    protected $layoutStr;
    /**
     * @var string
     */
    protected $serviceStr;
    /**
     * @var array
     */
    protected $serviceXsd = [];
    /**
     * @var Certificate
     */
    protected $certificate;
    /**
     * @var int
     */
    protected $transmissortpInsc;
    /**
     * @var string
     */
    protected $transmissornrInsc;
    /**
     * @var array
     */
    protected $eventGroup = [
        1 => 'EVENTOS INICIAIS',
        2 => 'EVENTOS NÃO PERIÓDICOS',
        3 => 'EVENTOS PERIÓDICOS',
    ];
    /**
     * @var array
     */
    protected $grupos = [
        1 => [ //EVENTOS INICIAIS grupo [1]
            'R-1000',
            'R-1050',
            'R-1070'
        ],
        2 => [ //EVENTOS NÃO PERIÓDICOS grupo [2]
            'R-3010',
            'R-9000'
        ],
        3 => [ //EVENTOS PERIÓDICOS grupo [3]
            'R-2010',
            'R-2020',
            'R-2030',
            'R-2040',
            'R-2050',
            'R-2055',
            'R-2060',
            'R-2070',
            'R-2098',
            'R-4010',
            'R-4020',
            'R-4040',
            'R-4080'
        ],
        4 => [ //EVENTOS FINAIS grupo [4]
            'R-2099',
            'R-4099'
        ]
    ];

    /**
     * Constructor
     * @param string $config
     * @param Certificate $certificate
     */
    public function __construct(
        $config,
        Certificate $certificate
    ) {
        //set properties from config
        $stdConf = json_decode($config);
        $this->tpAmb = $stdConf->tpAmb;
        $this->verProc = $stdConf->verProc;
        $this->eventoVersion = $stdConf->eventoVersion;
        $this->serviceVersion = $stdConf->serviceVersion;
        $this->date = new DateTime();
        $this->admpublica = $stdConf->contribuinte->admPublica ?? false;
        $this->tpInsc = $stdConf->contribuinte->tpInsc;
        $this->nrInsc = $stdConf->contribuinte->nrInsc;
        $this->nmRazao = $stdConf->contribuinte->nmRazao;
        $this->transmissortpInsc = $stdConf->transmissor->tpInsc;
        $this->transmissornrInsc = $stdConf->transmissor->nrInsc;
        $this->certificate = $certificate;
        $this->doc = $this->nrInsc;
        if ($this->tpInsc == 1 && !$this->admpublica) {
            $this->doc = substr($this->nrInsc, 0, 8);
        }

        $this->path = realpath(
            __DIR__ . '/../../'
        ).'/';

        $this->serviceXsd = XsdSeeker::seek(
            $this->path . "schemes/comunicacao/v$this->serviceVersion/"
        );
    }

    /**
     * Send request to webservice
     * @param string $request
     * @return string
     */
    protected function sendRequest(string $request): string
    {
        if (empty($this->soap)) {
            $this->soap = new SoapCurl($this->certificate);
        }
        $envelope = "<soapenv:Envelope ";
        foreach ($this->soapnamespaces as $key => $xmlns) {
            $envelope .= "$key = \"$xmlns\" ";
        }
        $envelope .= ">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>"
            . $request
            . "</soapenv:Body>"
            . "</soapenv:Envelope>";

        $msgSize = strlen($envelope);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$this->action\"",
            "Content-length: $msgSize"
        ];
        if ($this->method === 'ReceberLoteEventos') {
            $url = $this->uri[$this->tpAmb];
        } else {
            $url = $this->uriconsulta[$this->tpAmb];
        }
        $this->lastRequest = $envelope;
        return (string) $this->soap->send(
            $this->method,
            $url,
            $this->action,
            $envelope,
            $parameters
        );
    }

    protected function sendApi(string $method, string $url, string $content)
    {
        if (empty($this->restclass)) {
            $this->restclass = new Rest($this->certificate);
        }
        return $this->restclass->sendApi($method, $url, $content);
    }

    /**
     * Verify the availability of a digital certificate.
     * If available, place it where it is needed
     * @param FactoryInterface $evento
     * @return void
     */
    protected function checkCertificate(FactoryInterface $evento)
    {
        //try to get certificate from event
        $certificate = $evento->getCertificate();
        if (empty($certificate)) {
            $evento->setCertificate($this->certificate);
        }
    }

    /**
     * Valid input parameters
     * @param array $properties
     * @param stdClass $std
     * @return void
     * @throws \Exception
     */
    protected function validInputParameters(array $properties, stdClass $std)
    {
        foreach ($properties as $key => $rules) {
            $r = json_decode(json_encode($rules));
            if ($r->required) {
                if (!isset($std->$key)) {
                    throw new \Exception("$key não foi passado como parâmetro e é obrigatório.");
                }
                $value = $std->$key;
                if ($r->type === 'integer') {
                    if ($value < $r->min || $value > $r->max) {
                        throw new \Exception("$key contêm um valor invalido [$value].");
                    }
                }
                if ($r->type === 'string') {
                    if (!preg_match("/{$r->regex}/", $value)) {
                        throw new \Exception("$key contêm um valor invalido [$value].");
                    }
                }
            }
        }
    }

    /**
     * Converte para lower case todas as propriedades do stdClass
     * @param stdClass $std
     * @return stdClass
     */
    protected static function propertiesToLower(stdClass $std): stdClass
    {
        $array = json_decode(json_encode($std), true);
        $lkarray = self::arrayKeysToLowerRecursive($array);
        if (empty($lkarray)) {
            throw new \RuntimeException("Deve ser passado pelo menos um campo no com valor "
                . "diferente de null ou vazio.");
        }
        return (object) $lkarray;
    }

    /**
     * Converte as chaves de um array para lowercase
     * @param array $array
     * @return array
     */
    protected static function arrayKeysToLowerRecursive(array $array): array
    {
        return array_map(
            static function ($item) {
                if (is_array($item)) {
                    $item = self::arrayKeysToLowerRecursive($item);
                }
                return $item;
            },
            self::keyFilter($array)
        );
    }

    /**
     * Remove espaços extras das chaves de um array
     * @param $array
     * @return array
     */
    protected static function keyFilter($array)
    {
        $new = [];
        foreach ($array as $key => $value) {
            $key = preg_replace('/(?:\s\s+)/', ' ', $key);
            $key = strtolower(trim($key)); //converte para caixa baixa
            $new[$key] = $value;
        }
        return $new;
    }

    /**
     * Valida os dados passados para as consultas assincronas
     * @param array $required
     * @param stdClass $std
     * @return array
     */
    protected static function validateConsultData(array $required, stdClass $std): array
    {
        $errors = [];
        $status = true;
        foreach ($required as $rec => $regex) {
            $value = trim($std->$rec);
            if (empty($value)) {
                $errors[] = "O campo {$rec}, deve ser informado.";
                $status = false;
            }
            if (!preg_match_all($regex, $value, $matchs)) {
                $errors[] = "O valor {$value} é um conteúdo incorreto para o campo {$rec}.";
                $status = false;
            }
        }
        return ['status' => $status, 'errors' => $errors];
    }
}
