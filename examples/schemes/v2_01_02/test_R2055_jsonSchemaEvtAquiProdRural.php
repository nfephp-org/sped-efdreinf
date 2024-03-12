<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtAquisicaoProdRural';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evtAqProd",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
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
            "pattern": "^[0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{4}[-][0-9]{1,18}$"
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^20([1-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "retifs1250": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^(S)$"
        },
        "tpinscadq": {
            "required": true,
            "type": "string",
            "pattern": "^(1|3)$"
        },
        "nrinscadq": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "tpinscprod": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 2
        },
        "nrinscprod": {
            "required": true,
            "type": "string",
            "pattern": "^([0-9]{11}|[0-9]{14})$"
        },
        "indopccp": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^(S)$"
        },
        "detaquis": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 6,
            "items": {
                "type": "object",
                "properties": {
                    "indaquis": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 7
                    },
                    "vlrbruto": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrcpdescpr": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrratdescpr": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrsenardesc": {
                        "required": true,
                        "type": "number"
                    },
                    "infoprocjud": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 50,
                        "items": {
                            "type": "object",
                            "properties": {
                                "nrprocjud": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{1,21}$"
                                },
                                "codsusp": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{0,14}$"
                                },
                                "vlrcpnret": {
                                    "required": false,
                                    "type": ["number", "null"]
                                },
                                "vlrratnret": {
                                    "required": false,
                                    "type": ["number", "null"]
                                },
                                "vlrsenarnret": {
                                    "required": false,
                                    "type": ["number", "null"]
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
//$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '1-23-4567-8901-2345';
$std->perapur = '2017-11';
$std->retifs1250 = "S"; //null ou "S"
$std->tpinscadq = "1"; //1 cnpj ou 3 CAEPF
$std->nrinscadq = "12345678901234"; //cnpj ou caepf
$std->tpinscprod = 1; //1-CNPJ 2-CPF
$std->nrinscprod = '12345678901234'; //cnpj ou cpf
$std->indopccp = "S"; //null ou "S"

$std->detaquis[0] = new \stdClass();
$std->detaquis[0]->indaquis = 1; //de 1 até 7
$std->detaquis[0]->vlrbruto = 10000.00;
$std->detaquis[0]->vlrcpdescpr = 5000.56;
$std->detaquis[0]->vlrratdescpr = 100.77;
$std->detaquis[0]->vlrsenardesc = 50.88;
$std->detaquis[0]->infoprocjud[0] = new \stdClass();
$std->detaquis[0]->infoprocjud[0]->nrprocjud = '123456';
$std->detaquis[0]->infoprocjud[0]->codsusp = '9292929';
$std->detaquis[0]->infoprocjud[0]->vlrcpnret = 1000.55;
$std->detaquis[0]->infoprocjud[0]->vlrratnre = 101.02;
$std->detaquis[0]->infoprocjud[0]->vlrsenarnret = 852.31;

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
