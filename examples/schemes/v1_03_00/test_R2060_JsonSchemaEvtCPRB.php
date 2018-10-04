<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtCPRB';
$version = '1_03_00';

$jsonSchema = '{
    "title": "evtCPRB",
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
            "pattern": "^[0-9]"
        },
        "vlrrecbrutatotal": {
            "required": true,
            "type": "string",
            "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
        },
        "vlrcpapurtotal": {
            "required": true,
            "type": "string",
            "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
        },
        "vlrcprbsusptotal": {
            "required": true,
            "type": "string",
            "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
        },
        "tipocod": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 500,
            "items": {
                "type": "object",
                "properties": {
                    "codativecon": {
                        "required": true,
                        "type": "string",
                        "maxLength": 8
                    },
                    "vlrrecbrutaativ": {
                        "required": true,
                        "type": "string",
                        "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                    },
                    "vlrexcrecbruta": {
                        "required": true,
                        "type": "string",
                        "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                    },
                    "vlradicrecbruta": {
                        "required": true,
                        "type": "string",
                        "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                    },
                    "vlrbccprb": {
                        "required": true,
                        "type": "string",
                        "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                    },
                    "vlrcprbapur": {
                        "required": false,
                        "type": "string",
                        "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                    },
                    "tipoajuste": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpajuste": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 0,
                                    "maximum": 1
                                },
                                "codajuste": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 11
                                },
                                "vlrajuste": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                                },
                                "descajuste": {
                                    "required": true,
                                    "type": "string",
                                    "maxLength": 20
                                },
                                "dtajuste": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
                                }
                            }
                        }
                    },
                    "infproc": {
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
                                "vlrcprbsusp": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^([0-9]{1,14}[,][0-9]{2})$"
                                }
                            }
                        }    
                    }
                }
            }    
        }
    }
}';


$std = new \stdClass();
$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';
$std->tpinscestab = 1;
$std->nrinscestab = "12345678901";
$std->vlrrecbrutatotal = '10000,00'; 
$std->vlrcpapurtotal = '1020,00';
$std->vlrcprbsusptotal = '200,00';

$std->tipocod[0] = new \stdClass();
$std->tipocod[0]->codativecon = '12345678';
$std->tipocod[0]->vlrrecbrutaativ = '4444,44';
$std->tipocod[0]->vlrexcrecbruta = '3333,33';
$std->tipocod[0]->vlradicrecbruta = '2222,22';
$std->tipocod[0]->vlrbccprb = '1111,11';
$std->tipocod[0]->vlrcprbapur = '2000,00';

$std->tipocod[0]->tipoajuste[0] = new \stdClass();
$std->tipocod[0]->tipoajuste[0]->tpajuste = 0;
$std->tipocod[0]->tipoajuste[0]->codajuste = 11;
$std->tipocod[0]->tipoajuste[0]->vlrajuste = '200,00';
$std->tipocod[0]->tipoajuste[0]->descajuste = 'sei la';
$std->tipocod[0]->tipoajuste[0]->dtajuste = '2017-10';

$std->tipocod[0]->infoproc[0] = new \stdClass();
$std->tipocod[0]->infoproc[0]->vlrcprbsusp = '200,00';
$std->tipocod[0]->infoproc[0]->tpproc = 1;
$std->tipocod[0]->infoproc[0]->nrproc = 'ABC21';
$std->tipocod[0]->infoproc[0]->codsusp = '12345678901234';


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
