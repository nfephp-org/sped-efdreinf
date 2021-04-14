<?php

namespace NFePHP\EFDReinf\Common;

/**
 * Class Sanitize to clean entities from string
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

class Sanitize
{
    public static function text($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8', false);
    }
}
