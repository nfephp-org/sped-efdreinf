<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evt1050TabEntidadesLigadas';
$version = '2_01_01';

$jsonSchema = '{
    "title": "evt1050TabEntidadesLigadas",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 99999
        },
        "modo": {
            "required": true,
            "type": "string",
            "pattern": "^(INC|ALT|EXC)$"
        },
        "tpentlig": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 4
        },
        "cnpjlig": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "inivalid": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "fimvalid": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "novavalidade": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "inivalid": {
                    "required": true,
                    "type": "string",
                    "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
                },
                "fimvalid": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
                }
            }
        }
    }
}';


$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->modo = 'EXC'; //Obrigatório INC-inclusao ALT-alteração EXC-exclusao
$std->tpentlig = 1; //Opcional
$std->cnpjlig = '12345678901234'; //Obrigatório
$std->inivalid = '2017-01'; //Obrigatório inicio da validade data de inicio da OBRIGATORIEDADE da declaração
$std->fimvalid = '2017-02'; //Opcional somente deve ser passado caso seja uma alteração de dados

//indicar somente quando for uma alteração com novo periodo de validade
$std->novavalidade = new \stdClass(); //Opcional
$std->novavalidade->inivalid = '2017-02'; //Obrigatório inicio da validade dessa nova informação
$std->novavalidade->fimvalid = null; //Opcional não deve ser declarado o fim da validade, isso é usado em casos RAROS onde se sabe a data que o evento será modificado


// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitação no schema ! Revise</h2>";
    echo "<pre>";
    print_r($jsonSchema);
    echo "</pre>";
    die();
}
// The SchemaStorage can resolve references, loading additional schemas from file as needed, etc.
$schemaStorage = new SchemaStorage();

// This does two things:
// 1) Mutates $jsonSchemaObject to normalize the references (to file://mySchema#/definitions/integerData, etc)
// 2) Tells $schemaStorage that references to file://mySchema... should be resolved by looking in $jsonSchemaObject
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);

// Provide $schemaStorage to the Validator so that references can be resolved during validation
$jsonValidator = new Validator(new Factory($schemaStorage));

// Do validation (use isValid() and getErrors() to check the result)
$jsonValidator->validate(
    $std,
    $jsonSchemaObject,
    Constraint::CHECK_MODE_COERCE_TYPES  //tenta converter o dado no tipo indicado no schema
);

if ($jsonValidator->isValid()) {
    echo "The supplied JSON validates against the schema.<br/>";
} else {
    echo "JSON does not validate. Violations:<br/>";
    foreach ($jsonValidator->getErrors() as $error) {
        echo sprintf("[%s] %s<br/>", $error['property'], $error['message']);
    }
    die;
}
//salva se sucesso
file_put_contents("../../../jsonSchemes/v$version/$evento.schema", $jsonSchema);
