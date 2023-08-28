<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtTabProcesso';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evtTabProcesso",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 99999
        },
        "tpproc": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 2
        },
        "nrproc": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{1,21}$"
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
        "indautoria": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 2
        },
        "modo": {
            "required": true,
            "type": "string",
            "pattern": "INC|ALT|EXC"
        },
        "infosusp": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 50,
            "items": {
                "type": "object",
                "properties": {
                    "codsusp": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "[0-9]{0,14}"
                    },
                    "indsusp": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(01|02|03|04|05|08|09|10|11|12|13|90|92)$"
                    },
                    "dtdecisao": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                    },
                    "inddeposito": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(S|N)$"
                    }
                }
            }
        },
        "dadosprocjud": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "ufvara": {
                    "required": true,
                    "type": "string",
                    "pattern": "^(AC|AL|AM|AP|BA|CE|DF|ES|GO|MA|MG|MS|MT|PA|PB|PE|PI|PR|RJ|RN|RO|RR|RS|SC|SE|SP|TO)$"
                },
                "codmunic": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{7}$"
                },
                "idvara": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{1,4}$"
                }
            }
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
$std->sequencial = 1;
$std->tpproc = 1;
$std->nrproc = '123455678';
$std->inivalid = '2017-11';
$std->fimvalid = null;
$std->indautoria = 1;
$std->modo = 'INC';

$std->infosusp[0] = new \stdClass();
$std->infosusp[0]->codsusp = '234567890123';
$std->infosusp[0]->indsusp = '01';
$std->infosusp[0]->dtdecisao = '2017-10-31';
$std->infosusp[0]->inddeposito = 'S';

$std->dadosprocjud = new \stdClass();
$std->dadosprocjud->ufvara = 'SP';
$std->dadosprocjud->codmunic = '3548714';
$std->dadosprocjud->idvara = '0123';

$std->novavalidade = new \stdClass();
$std->novavalidade->inivalid = '2017-11';
$std->novavalidade->fimvalid = null;

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
