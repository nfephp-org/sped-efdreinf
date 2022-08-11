<?php

namespace NFePHP\EFDReinf\Factories\Traits;

/*
    O endereço de correio eletrônico deve obedecer às regras:
      - Ter o formato <prefixo>@<domínio>;
      - Devem ser utilizados os caracteres: “A…Z”, “a…z”, “0…9”, “-”, “@”, “.”, “_”;
      - Ter no mínimo dois caracteres no <prefixo>;
      - Ter no máximo cinquenta caracteres;
      - Conter o caractere "@" (arroba);
      - O caractere "@" (arroba) não pode ser o primeiro caractere;
      - O caractere "@" (arroba) não pode ser o último caractere;
      - Não pode conter espaço (ASCII: 32);
      - Não pode conter barra vertical “|”;
      - Não pode conter “..” (dois pontos) consecutivos;
      - Não pode conter “@.” (arroba e ponto) consecutivos;
      - Não pode conter “.@” (ponto e arroba) consecutivos;
      - O <domínio> deve ter no mínimo o formato <nome>.<categoria>.<país>;
      - O <país> é opcional para alguns domínios;
      - O <nome> válido deve obedecer às seguintes regras:
      • Deve ter no mínimo 2 e no máximo 26 caracteres;
      • Caracteres válidos: letras (a–z); números (0–9), hífen “-“; e os seguintes caracteres
         acentuados: “à, á, â, ã, é, ê, í, ó, ô, õ, ú, ü, ç”;
      • Não conter somente números;
      • Não iniciar ou terminar por hífen.

      Prefixo (informação antes do caractere @)
      São permitidos os seguintes caracteres:
            - Alfabéticos maiúsculos e minúsculos: A–Z, a–z (ASCII: 65–90, 97–122);
           - Numéricos: 0–9 (ASCII: 48-57);
           - Caracteres especiais: !#$%&'*+-/=?^_`{|}~ (ASCII: 33, 35-39, 42, 43, 45, 47, 61, 63, 94-96, 123-126);
           - Ponto (ASCII: 46); não pode ser o primeiro, nem o último caractere; não pode aparecer consecutivamente;
           - Ter no mínimo dois caracteres.
      Domínio (informação depois do caractere @)
      São permitidos os seguintes caracteres:
           - Alfabéticos maiúsculos e minúsculos: A–Z, a–z (ASCII: 65–90, 97–122);
           - Numéricos: 0–9 (ASCII: 48-57);
           - Caractere especial: _ (ASCII: 95);
           - Ponto (ASCII: 46), não pode ser o primeiro, nem o último caractere; não pode aparecer consecutivamente;
           - É composto por uma série de nomes unidos por ponto; cada nome terá no máximo 63 caracteres
 */

use NFePHP\Common\Strings;
use NFePHP\EFDReinf\Exception\EventsException;

trait RegraEmailValido
{
    protected static function validateEmail($address = null)
    {
        if ($address === null) {
            return null;
        }
        $origin = $address;
        //remome caracteres não UTF-8
        $name = Strings::normalize($address);
        //remove multiplos espaços
        $address = preg_replace('/(?:\s\s+)/', ' ', $address);
        $address = trim(strtolower($address));
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw EventsException::wrongArgument(1004, "O email [$origin] não é valido!");
        }
        return $address;
    }
}
