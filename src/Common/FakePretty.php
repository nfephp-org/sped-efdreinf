<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class FakePretty shows event and fake comunication data for analises and debugging
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
class FakePretty
{
    public static function prettyPrint($response, $save = '')
    {
        if (empty($response)) {
            $html = "Sem resposta";
            return $html;
        }
        $std = json_decode($response, true);

        if (!empty($save) && !empty($std['body'])) {
            file_put_contents(
                "/var/www/sped/sped-efdreinf/tests/fixtures/xml/$save.xml",
                base64_decode($std['body'])
            );
        }
        if (!empty($std['body'])) {
            $doc = new \DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
            $doc->loadXML(base64_decode($std['body']));
        }
        $keys = array_keys($std);
        $html = "<h1>Dados de Envio SOAP</h1><pre>";
        if (in_array('method', $keys)) {
            $html = "<h1>Dados de Envio REST ASSINCRONO</h1><pre>";
        }
        foreach ($keys as $key) {
            if ($key === 'parameters') {
                $html .= "<h2>{$key}</h2>";
                foreach ($std['parameters'] as $chave => $param) {
                    $html .= "[$chave] => $param <br>";
                }
                continue;
            }
            if ($key === 'namespeces') {
                $html .= "<h2>{$key}</h2>";
                $an = json_decode(json_encode($std['namespaces']), true);
                foreach ($an as $chave => $nam) {
                    $html .= "[$chave] => $nam <br>";
                }
                $html .= "<br>";
                continue;
            }
            if ($key === 'body') {
                $html .= "<h2>{$key}</h2>";
                if (!empty($std['body'])) {
                    $html .= str_replace(
                        ['<', '>'],
                        ['&lt;', '&gt;'],
                        $doc->saveXML($doc->documentElement)
                    );
                }
                continue;
            }
            $html .= "<h2>{$key}</h2>";
            if (is_array($std[$key])) {
                foreach ($std[$key] as $chave => $content) {
                    $html .= "[$chave] => $content <br>";
                }
                continue;
            }
            $html .= $std[$key];
        }
        $html .= "</pre>";
        return $html;
    }
}
