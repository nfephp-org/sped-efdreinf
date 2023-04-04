# sped-efdreinf

## NOVA VERSÃO 2.1.1 ENTRA EM PRODUÇÃO EM MARÇO/2023

A nova versão dos layouts v2.1.1 entra em vigor a partir da competência de março/2023, até fevereiro de 2023 deve ser utilizada a versão 1.5.1.

>NOTA: o envio dos lotes passará a ser ASSINCRONO !!, e portanto serão necessárias DUAS operações "ENVIO DO LOTE" e posteriormente a "CONSULTA DO LOTE"

>NOTA: A versão 2.1.1 já está em testes, concomitantemente com a versão 1.5.1.

## NOVA VERSÃO 2.1.2 ENTRA EM PRODUÇÃO EM SETEMBRO/2023  

### Eventos com alteração de 2.1.1 para 2.1.2

- R-1000
- R-2030
- R-4010
- R-4020
- R-4040
- R-4080
- R-9001
- R-9005
- R-9011
- R-9015


## Alterações na nova versão v2.1.1 

Vide [FALHAS](FALHAS.md)                       
Vide [REGRAS](REGRAS.md)

1. Novos pacotes de XSD (lembrar de sempre renomear os xsd de acordo com os namespaces indicados nos próprios arquivos XSD).
2. Devem haver algumas alterações em validações dos eventos anteriores e no número de ocorrências de alguns campos.
3. Inclusão de novos eventos:

- R-1050 Tabela de entidades ligadas (ok)
- R-4010 Pagamentos/créditos a beneficiário pessoa física (está problemas no XSD, falta desenvolver jsoonschema e obter xsd corrgido)
- R-4020 Pagamentos/créditos a beneficiário pessoa jurídica (está problemas no XSD, e falta desenvolver tudo e obter xsd corrgido)
- R-4040 Pagamentos/créditos a beneficiários não identificados (ok)
- R-4080 Retenção no recebimento (ok)
- R-4099 Fechamento/Reabertura dos eventos da série R-4000 (ok)

> NOTA: Ainda existem diferenças entre os ambientes de Produção e Pre-produção fique atento durante a transição de versões !!

*Utilize o chat do Gitter para iniciar discussões especificas sobre o desenvolvimento deste pacote.*

[![Chat][ico-gitter]][link-gitter]

[![Latest Stable Version][ico-stable]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

Este pacote é aderente com os [PSR-1], [PSR-2] e [PSR-4]. Se você observar negligências de conformidade, por favor envie um patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp) para acompanhar o desenvolvimento e participar das discussões e tirar duvidas!

## Obrigatoriedade e entrega da EFD-Reinf

Estão obrigados a prestar informações por meio da EFD-Reinf 1 , exceto o empregador doméstico, os seguintes sujeitos passivos, ainda que imunes ou isentos:

1. empresas que prestam e contratam serviços realizados mediante cessão de mão de obra, nos termos do art. 31 da Lei no 8.212, de 24 de julho de 1991;
2. pessoas jurídicas a que se referem os arts. 30 e 34 da Lei no 10.833, de 29 de dezembro de 2003 e o art. 64 da Lei no 9.430, de 27 de dezembro de 1996, responsáveis pela retenção da Contribuição para os Programas de Integração Social e de Formação do Patrimônio do Servidor Público (Contribuição para o PIS/Pasep), da Contribuição para o Financiamento da Seguridade Social (Cofins) e da Contribuição Social sobre o Lucro Líquido (CSLL);
3. empresas optantes pelo recolhimento da contribuição previdenciária sobre a receita bruta (CPRB), nos termos da Lei no 12.546, de 14 de dezembro de 2011;
4. produtor rural pessoa jurídica e agroindústria quando sujeitos a contribuição previdenciária substitutiva sobre a receita bruta proveniente da comercialização da produção rural nos termos do art. 25 da Lei no 8.870, de 15 de abril de 1994, na redação dada pela Lei no 10.256, de 9 de julho de 2001 e do art. 22A da Lei no 8.212, de 24 de julho de 1991, inserido pela Lei no 10.256, de 9 de julho de 2001, respectivamente;
5. adquirente de produto rural nos termos do art. 30 da Lei no 8.212, de 1991, e do art. 11 da Lei no 11.718, de 20 de junho de 2008;
6. associações desportivas que mantenham equipe de futebol profissional, que tenham recebido valores a título de patrocínio, licenciamento de uso de marcas e símbolos, publicidade, propaganda e transmissão de espetáculos desportivos;
7. empresa ou entidade patrocinadora que tenha destinado recursos a associação desportiva que mantenha equipe de futebol profissional a título de patrocínio, licenciamento de uso de marcas e símbolos, publicidade, propaganda e transmissão de espetáculos desportivos;
8. entidades promotoras de eventos desportivos realizados em território nacional, em qualquer modalidade desportiva, dos quais participe ao menos 1 (uma) associação desportiva que mantenha equipe de futebol profissional; e
9. pessoas jurídicas e físicas que pagaram ou creditaram rendimentos sobre os quais haja retenção do Imposto sobre a Renda Retido na Fonte (IRRF), por si ou como representantes de terceiros.

> Os sujeitos passivos das itens “2” e “9” acima somente passarão a ser obrigados ao envio de informações relativas à EFD-Reinf quando os eventos que substituirão a Declaração do Imposto de Renda Retido na Fonte - DIRF forem inseridos formalmente, com a antecedência e a publicidade devidas, nos leiautes daquela escrituração.


## Contribuindo
Este é um projeto totalmente *OpenSource*, para usa-lo e modifica-lo você não paga absolutamente nada. Porém para continuarmos a mante-lo é necessário qua alguma contribuição seja feita, seja auxiliando na codificação, na documentação ou na realização de testes e identificação de falhas e BUGs.

**Este pacote esta listado no [Packgist](https://packagist.org/) foi desenvolvido para uso do [Composer](https://getcomposer.org/), portanto não será explicitada nenhuma alternativa de instalação.**

*Durante a fase de desenvolvimento e testes este pacote deve ser instalado com:*
```bash
composer require nfephp-org/sped-efdreinf:dev-master
```

*Ou ainda alterando o composer.json do seu aplicativo inserindo:*
```json
"require": {
    "nfephp-org/sped-efdreinf" : "dev-master"
}
```

> NOTA: Ao utilizar este pacote ainda na fase de desenvolvimento não se esqueça de alterar o composer.json da sua aplicação para aceitar pacotes em desenvolvimento, alterando a propriedade "minimum-stability" de "stable" para "dev".
> ```json
> "minimum-stability": "dev"
> ```

*Após os stable realeases estarem disponíveis, pode ser instalado com:*
```bash
composer require nfephp-org/sped-efdreinf
```
Ou ainda alterando o composer.json do seu aplicativo inserindo:
```json
"require": {
    "nfephp-org/sped-efdreinf" : "^1.0"
}
```

## Forma de uso
Em breve ....

## Log de mudanças e versões
Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testing

Todos os testes são desenvolvidos para operar com o PHPUNIT

## Security

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Credits

Roberto L. Machado (owner and developer)

## License

Este pacote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-stable]: https://poser.pugx.org/nfephp-org/sped-efdreinf/version
[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-efdreinf/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square
[ico-gitter]: https://img.shields.io/badge/GITTER-4%20users%20online-green.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-efdreinf
[link-travis]: https://travis-ci.org/nfephp-org/sped-efdreinf
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-efdreinf
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-efdreinf/issues
[link-forks]: https://github.com/nfephp-org/sped-efdreinf/network
[link-stars]: https://github.com/nfephp-org/sped-efdreinf/stargazers
[link-gitter]: https://gitter.im/nfephp-org/sped-efdreinf?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge
