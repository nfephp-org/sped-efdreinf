# Lista de PONTOS FALHOS

> NOTA: Existem muitos erros nos XSD da versão 2.1.1

## Evento R-1070 versão 2.1.1 - ERRO NO XSD

Na validação do campo **infoSusp.indSusp** é especificado um regex incorreto:

```
<xs:length value="2"/>
<xs:pattern value="[01|02|03|04|05|08|09|10|11|12|13|90|92]"/>
```

**Correção**

```
<xs:pattern value="0[1-5]|0[8-9]|1[0-3]|90|92"/>
```

## Evento R-2099 e R-4099 versão 2.1.1 - ERRO NO XSD

No campo **ideRespInf.nmResp** (*Nome do responsável pelas informações*), o XSD **exige** que contenha EXATOS 70 caracteres.

Isso aparenta ser um erro nos XSD, portanto foi alterada a regra desses XSD, conforme a regra da versão anterior 1.5.1.

- evtFech4000-v2_01_01.xsd
- evtFechamento-v2_01_01.xsd

## Evento R-4010 e R-4020 versão 2.1.1 - ERRO NO XSD

Não é possivel usar os XSD fornecidos para realizar a validação do XML pois os mesmos estão com erro na sua construção.

- (Original) **evtRetPF-v2_01_01.xsd**  (Renomeado) **evt4010PagtoBeneficiarioPF-v2_01_01.xsd** 
- (Original) **evtRetPJ-v2_01_01.xsd**  (Renomeado) **evt4020PagtoBeneficiarioPJ-v2_01_01.xsd**

## Evento R-4040 versão 2.1.1 - ERRO NO XSD

Na validação do campo **ideNat.natRend** é especificado um regex incorreto:

```
<xs:pattern value="[19001|19009]"/>
```

**Correção** 

```
<xs:pattern value="(19001|19009)"/>
```
