<?php
namespace NFePHP\EFDReinf\Exception;

/**
 * @category   NFePHP
 * @package    NFePHP\EFDReinf\Exception
 * @copyright  Copyright (c) 2008-2017
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-common for the canonical source repository
 */

class ProcessException extends \InvalidArgumentException implements ExceptionInterface
{
    public static $list = [
        2000 => "O numero máximo de eventos em um lote é 100, você está tentando enviar {{ msg }} eventos !",
        2001 => "Não temos um certificado disponível!",
        2002 => "",
        2003 => "",
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
