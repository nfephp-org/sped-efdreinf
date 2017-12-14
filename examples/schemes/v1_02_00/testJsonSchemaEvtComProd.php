<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtComProd';
$version = '1_02_00';

$jsonSchema = '{
    "title": "evtComProd",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 99999
        },
        "indretif": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 2
        },
        "nrrecibo": {
            "required": false,
            "type": ["string","null"],
            "minLength": 16,
            "maxLength": 52,
            "pattern": "^([0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{4}[-][0-9]{1,18})$"
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
        },
        "tpinscestab": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 1
        },
        "nrinscestab": {
            "required": true,
            "type": "string",
            "maxLength": 14,
            "pattern": "^([0-9]{8}|[0-9]{14})$"
        },
        "vlrrecbrutatotal": {
            "required": true,
            "type": "number"
        },
        "vlrcpapur": {
            "required": true,
            "type": "number"
        },
        "vlrratapur": {
            "required": true,
            "type": "number"
        },
        "vlrsenarapur": {
            "required": true,
            "type": "number"
        },
        "vlrcpsusptotal": {
            "required": false,
            "type": ["number","null"]
        },
        "vlrratsusptotal": {
            "required": false,
            "type": ["number","null"]
        },
        "vlrsenarsusptotal": {
            "required": false,
            "type": ["number","null"]
        },
        "tipocom": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 3,
            "items": {
                "type": "object",
                "properties": {
                    "indcom": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 9
                    },
                    "vlrrecbruta": {
                        "required": true,
                        "type": "number"
                    }
                }
            }    
        },
        "infoproc": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 50,
            "items": {
                "type": "object",
                "properties": {
                    "tpproc": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 2
                    },
                    "nrproc": {
                        "required": true,
                        "type": "string",
                        "maxLength": 21
                    },
                    "codsusp": {
                        "required": false,
                        "type": ["string","null"],
                        "maxLength": 14,
                        "pattern": "^[0-9]"
                    },
                    "vlrcpsusp": {
                        "required": false,
                        "type": ["number","null"]
                    },
                    "vlrratsusp": {
                        "required": false,
                        "type": ["number","null"]
                    },
                    "vlrsenarsusp": {
                        "required": false,
                        "type": ["number","null"]
                    }
                }
            }    
        }
    }
}';


$std = new \stdClass();
$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '1-23-4567-8901-2345';
$std->perapur = '2017-11';
$std->tpinscestab = 1;
$std->nrinscestab = "12345678901234";
$std->vlrrecbrutatotal = 10000.00; 
$std->vlrcpapur = 1020;
$std->vlrratapur = 200;
$std->vlrsenarapur = 200;
$std->vlrcpsusptotal = 1000;
$std->vlrratsusptotal = 200;
$std->vlrsenarsusptotal = 300;

$std->tipocom[0] = new \stdClass();
$std->tipocom[0]->indcom = 1;
$std->tipocom[0]->vlrrecbruta = 200;

$std->infoproc[0] = new \stdClass();
$std->infoproc[0]->tpproc = 1;
$std->infoproc[0]->nrproc = 'ABC21';
$std->infoproc[0]->codsusp = '12345678901234';
$std->infoproc[0]->vlrcpsusp = 100;
$std->infoproc[0]->vlrratsusp = 200;
$std->infoproc[0]->vlrsenarsusp = 300;



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
