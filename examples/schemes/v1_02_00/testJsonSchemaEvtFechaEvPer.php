<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtFechaEvPer';
$version = '1_02_00';

$jsonSchema = '{
    "title": "evtFechaEvPer",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 99999
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
        },
        "iderespinf": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "nmresp": {
                    "required": true,
                    "type": "string",
                    "maxLength": 70
                },
                "cpfresp": {
                    "required": true,
                    "type": "string",
                    "maxLength": 11,
                    "pattern": "^[0-9]"
                },
                "telefone": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 13
                },
                "email": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 60
                }
            }
        },
        "evtservtm": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtservpr": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtassdesprec": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtassdesprep": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtcomprod": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtcprb": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "evtpgtos": {
            "required": true,
            "type": "string",
            "pattern": "S|N"
        },
        "compsemmovto": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
        }
    }
}';


$std = new \stdClass();
$std->sequencial = 1;
$std->perapur = '2017-11';
$std->iderespinf= new \stdClass();
$std->iderespinf->nmresp = 'Ciclano de Tal';
$std->iderespinf->cpfresp = '12345678901';
$std->iderespinf->telefone = '5555-5555';
$std->iderespinf->email = 'ciclano@mail.com';

$std->evtservtm = 'S';
$std->evtservpr = 'S';
$std->evtassdesprec = 'S';
$std->evtassdesprep = 'S';
$std->evtcomprod = 'S';
$std->evtcprb = 'S';
$std->evtpgtos = 'S';
$std->compsemmovto = '2017-12';



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
