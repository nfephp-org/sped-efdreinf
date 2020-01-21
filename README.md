# sped-efdreinf (versão 1.0 - layout 1.4)

## BETHA TESTS

> NOTA Layout 2.0 [(CANCELADO)](http://sped.rfb.gov.br/pagina/show/4184)

> NOTA Layout 2.1 [(Minuta)](http://sped.rfb.gov.br/pasta/show/4135)


*Utilize o chat do Gitter para iniciar discussões especificas sobre o desenvolvimento deste pacote.*

[![Chat][ico-gitter]][link-gitter]

[![Latest Stable Version][ico-stable]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
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
A aplicação da EFD-Reinf atualizada pela Instrução Normativa 1.767, publicada no dia 14/12/17, determina o seguinte cronograma:

I   para o 1º grupo, que compreende as entidades integrantes do “Grupo 2 – Entidades Empresariais”, com faturamento no ano de 2016 acima de R$ 78 milhões, **a partir de 1º de maio de 2018**, em relação aos fatos geradores ocorridos a partir dessa data;

II  para o 2º grupo, que compreende os demais contribuintes, exceto os previstos no inciso III, **a partir de 1º de novembro de 2018**, em relação aos fatos geradores ocorridos a partir dessa data; e

III para o 3º grupo, que compreende os entes públicos, integrantes do Grupo 1 – Administração Pública, a **partir de 1º de maio de 2019**, em relação aos fatos geradores ocorridos a partir dessa data.

Ressalte-se que o prazo de vencimento da obrigação foi alterado para até o dia 15 do mês subsequente.

A referida obrigação será entregue via DCTFWeb, sistema utilizado para fechamento previdenciário, que permitirá: 

- Apuração automática dos débitos tributários;
- Vinculações dos débitos e créditos tributários e compensações;
- Consulta e aproveitamento dos créditos tributários disponíveis;
- Emissão eletrônica de DARF com código de barras.


A Escrituração Fiscal Digital de Retenções e Outras Informações Fiscais EFD-Reinf é um dos módulos do Sistema Público de Escrituração Digital - SPED, a ser utilizado pelas pessoas jurídicas e físicas, em complemento ao Sistema de Escrituração Digital das Obrigações Fiscais, Previdenciárias e Trabalhistas – eSocial.
 
Tem por objeto a escrituração de rendimentos pagos e retenções de Imposto de Renda, Contribuição Social do contribuinte exceto aquelas relacionadas ao trabalho e informações sobre a receita bruta para a apuração das contribuições previdenciárias substituídas. Substituirá, portanto, o módulo da EFD-Contribuições que apura a Contribuição Previdenciária sobre a Receita Bruta (CPRB).
 
A EFD-Reinf junto ao eSocial, após o início de sua obrigatoriedade, abre espaço para substituição de informações solicitadas em outras obrigações acessórias, tais como a GFIP, a DIRF e também obrigações acessórias instituídas por outros órgãos de governo como a RAIS e o CAGED.
 
Esta escrituração está modularizada por eventos de informações, contemplando a possibilidade de múltiplas transmissões em períodos distintos, de acordo com a obrigatoriedade legal.
 
Dentre as informações prestadas através da EFD-Reinf, destacam-se aquelas associadas:

- aos serviços tomados/prestados mediante cessão de mão de obra ou empreitada;
- às retenções na fonte (IR, CSLL, COFINS, PIS/PASEP) incidentes sobre os pagamentos diversos efetuados a pessoas físicas e jurídicas;
- aos recursos recebidos por / repassados para associação desportiva que mantenha equipe de futebol profissional;
- à comercialização da produção e à apuração da contribuição previdenciária substituída pelas agroindústrias e demais produtores rurais pessoa jurídica;
- às empresas que se sujeitam à CPRB (cf. Lei 12.546/2011);
- às entidades promotoras de evento que envolva associação desportiva que mantenha clube de futebol profissional.

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
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-efdreinf.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square
[ico-gitter]: https://img.shields.io/badge/GITTER-4%20users%20online-green.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-efdreinf
[link-travis]: https://travis-ci.org/nfephp-org/sped-efdreinf
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-efdreinf/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-efdreinf
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-efdreinf
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-efdreinf/issues
[link-forks]: https://github.com/nfephp-org/sped-efdreinf/network
[link-stars]: https://github.com/nfephp-org/sped-efdreinf/stargazers
[link-gitter]: https://gitter.im/nfephp-org/sped-efdreinf?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge
