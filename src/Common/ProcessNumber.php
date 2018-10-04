<?php

namespace NFePHP\EFDReinf\Common;

class ProcessNumber
{
    /**
     * Calculate check digit Algoritm Module 97 Base 10 (ISO 7064)
     * Anexo VIII da Resolução CNJ no 65, de 16 de dezembro de 2008.
     * @param string $input
     * @return string
     */
    public static function checkDigitJud($input)
    {
        $n = substr($input, 0, 7);
        $dd = substr($input, 7, 2);
        $a = substr($input, 9, 4);
        $jtr = substr($input, 13, 3);
        $o = substr($input, 16, 4);
        $r1 = $n % 97;
        $v2 = str_pad($r1, 2, '0', STR_PAD_LEFT)
            . str_pad($a, 4, '0', STR_PAD_LEFT)
            . str_pad($jtr, 3, '0', STR_PAD_LEFT);
        $r2 = $v2 % 97;
        $v3 = str_pad($r2, 2, '0', STR_PAD_LEFT)
            . str_pad($o, 4, '0', STR_PAD_LEFT)
            . "00";
        $r3 = $v3 % 97;
        return str_pad((98 - $r3), 2, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check if judicial process number have a valid check digit
     * @param string $input
     * @return boolean
     */
    public static function isValidProcJudNumber($input)
    {
        $num = self::clearString($input, 20);
        $dd = (string) substr($num, 7, 2);
        if ($dd !== self::checkDigitJud($num)) {
            return false;
        }
        return true;
    }

    /**
     * Calculate check digit Algoritm Module 11
     * @param string $input
     * @return string
     */
    public static function checkDigitAdm($input)
    {
        $n = substr($input, 0, 5);
        $x = substr($input, 5, 6);
        $y = substr($input, 11, 4);
        $dd = substr($input, -2);
        $value = $n.$x.$y;
        $a = str_split($value);
        $soma = 0;
        $i = 16;
        foreach ($a as $k) {
            $soma += $k * $i;
            $i--;
        }
        $m1 = 11 - ($soma % 11);
        if ($m1 > 9) {
            $m1 -= 10;
        }
        $a[] = $m1;
        $i = 17;
        $soma = 0;
        foreach ($a as $k) {
            $soma += ($k * $i);
            $i--;
        }
        $m2 = 11 - ($soma % 11);
        if ($m2 > 9) {
            $m2 -= 10;
        }
        return $m1.$m2;
    }
    
    /**
     * Check if judicial process number have a valid check digit
     * @param string $input
     * @return boolean
     */
    public static function isValidProcAdmNumber($input)
    {
        $num = self::clearString($input, 17);
        $dd = substr($num, -2);
        if ($dd !== self::checkDigitAdm($num)) {
            return false;
        }
        return true;
    }
    
    
    /**
     * Clear input number
     * @param string $input
     * @return string
     * @param int $lenght
     * @throws \InvalidArgumentException
     */
    protected static function clearString($input, $lenght)
    {
        $input = str_replace(['-','/','.'], '', $input);
        $input = substr($input, 0, $lenght-1);
        $input = str_pad($input, $lenght, '0', STR_PAD_RIGHT);
        if (!preg_match('/^[0-9]+$/', $input)) {
            throw new \InvalidArgumentException("O numero do processo tem estrutura incorreta.");
        }
        return (string) $input;
    }
}
