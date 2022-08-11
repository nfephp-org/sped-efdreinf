<?php

namespace NFePHP\EFDReinf\Factories\Traits;

/**
  O nome deve obedecer às seguintes regras:
    1 – Não pode conter caracteres diferentes de: [a-z], [A-Z], 'á', 'Á', 'à', 'À', 'ã', 'Ã', 'â', 'Â', 'é',
     'É', 'ê', 'Ê', 'í', 'Í', 'ó', 'Ó', 'ô', 'Ô', 'õ', 'Õ', 'ú', 'Ú', 'ü', 'Ü', 'ç', 'Ç', Chr(32);
    2 – Não pode conter barra vertical “|”;
    3 – Não pode conter mais de 70 caracteres;
    4 – Não pode conter mais de 15 partes;
    5 – Não pode conter 3 ou mais caracteres iguais consecutivos, exceto “III” (algarismo romano);
    6 – Não pode conter parte do nome com 21 ou mais caracteres consecutivos sem separação por espaço.
*/

use NFePHP\Common\Strings;

trait RegraNomeValido
{
    /**
     * Ajusta o Nome fornecido as regras estabelecidas pela Receita
     * @param string $name
     * @return string
     */
    protected static function validateName(string $name): string
    {
        //remome caracteres não UTF-8
        $name = Strings::normalize($name);
        //remove multiplos espaços
        $name = preg_replace('/(?:\s\s+)/', ' ', $name);
        //remove os caracteres inaceitáveis (regra 1 e regra 2)
        $name = preg_replace('/[^a-zA-Z áÁàÀãÃâÂéÉêÊíÍóÓôÔõÕúÚüÜçÇ]/i', '', $name);
        //não permite mais que 15 partes separadas por espaços (regra 3)
        $part = explode(' ', $name);
        if (count($part) > 14) {
            $name = '';
            for ($x=0; $x<=14; $x++) {
                //não permite parte com maisnde 21 caracteres (regra 6)
                if (strlen($part[$x]) > 21) {
                    $part[$x] = substr($part[$x],0, 21);
                }
                $name .= $part[$x] . ' ';
            }
            $name = trim($name);
        }
        $count = 0;
        $oldchar = '';
        $len = strlen($name);
        $newname = '';
        for ($x = 0; $x < $len; $x++) {
            if ($name[$x] === $oldchar) {
                $count++;
            } else {
                $count = 0;
            }
            if ($oldchar === 'I') {
                if ($count < 3) {
                    $newname .= $name[$x];
                }
            } else {
                if ($count < 2) {
                    $newname .= $name[$x];
                }
            }
            $oldchar = $name[$x];
        }
        return $newname;
    }
}
