<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtFech4000';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evtFech4000",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 99999
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "iderespinf": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "nmresp": {
                    "required": true,
                    "type": "string",
                    "minLength": 1,
                    "maxLength": 70
                },
                "cpfresp": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{11}$"
                },
                "telefone": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9 ()-]{1,13}$"
                },
                "email": {
                    "required": false,
                    "type": ["string","null"],
                    "minLength": 5,
                    "maxLength": 60
                }
            }
        },
        "fechret": {
            "required": true,
            "type": "string",
            "pattern": "^(0|1)$"
        }
    }
}';


$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->perapur = '2017-11';
$std->iderespinf= new \stdClass();
$std->iderespinf->nmresp = str_pad('Ciclano de Tal', 70, '_', STR_PAD_RIGHT);
$std->iderespinf->cpfresp = '12345678901';
$std->iderespinf->telefone = '115555-5555';
$std->iderespinf->email = 'ciclano@mail.com';
$std->fechret = '0'; //0-fecha ou 1-reabre



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
