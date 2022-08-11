# REGRAS

## Regra Nome Valido

Trait RegraNomeValido

Método estático **validateName($name)**

Este método simplesmente ajusta o nome informado às regras estabelecidas pela receita, ou seja, se forem passados caracteres inaceitáveis ou qualquer outra condição inaceitável, isso será corrigido de forma automática pela biblioteca. 

## Regra Email Valido

Traite RegraEmailValido

Método estático **validateEmail($address)**

Este método testa o endereço de email e dispara um throw Exception caso esteja incorreto, se passar joga todo endereço para minusculas.  
