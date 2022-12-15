<?php

namespace NFePHP\EFDReinf\Factories\Traits;

trait FormatNumber
{
    /**
     * Number format
     * @param $value
     * @param $decimals
     * @return string|null
     */
    protected static function format($value = null, $decimals = 2, $separator = ',')
    {
        if ($value === null) {
            return null;
        }
        if (empty($value)) {
            $value = 0;
        }
        return number_format($value, $decimals, $separator, '');
    }
}
