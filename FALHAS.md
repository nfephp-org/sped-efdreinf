# Lista de PONTOS FALHOS

## Evento R-2099 e R-4099 versão 2.1.1 - ERRO NO XSD

No campo **ideRespInf.nmResp** (*Nome do responsável pelas informações*), o XSD **exige** que contenha EXATOS 70 caracteres.

Isso aparenta ser um erro nos XSD, portanto foi alterada a regra desses XSD, conforme a regra da versão anterior 1.5.1.

- evtFech4000-v2_01_01.xsd
- evtFechamento-v2_01_01.xsd

