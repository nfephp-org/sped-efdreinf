<?php
namespace NFePHP\EFDReinf\Exception;

/**
 * Class ProcessesException
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

use NFePHP\EFDReinf\Exception\ExceptionInterface;

class ProcessException extends \InvalidArgumentException implements ExceptionInterface
{
    public static $list = [
        2000 => "Ultrapassado o número máximo de eventos por lote, você está tentando enviar {{msg}} eventos"
            . ", consulte a documentação!",
        2001 => "Não temos um certificado disponível!",
        2002 => "Não foi passado um evento válido.",
        2003 => "Não foi passada uma consulta válida",
        2004 => "",
        2005 => ""
    ];

    public static function wrongArgument($code, $msg = '')
    {
        $msg = self::replaceMsg(self::$list[$code], $msg);
        return new static($msg);
    }

    private static function replaceMsg($input, $msg)
    {
        return str_replace('{{msg}}', $msg, $input);
    }
}
