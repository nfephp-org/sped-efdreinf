<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evt4080RetencaoRecebimento';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evt4080RetencaoRecebimento",
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
            "type": ["string", "null"],
            "pattern": "^[0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{4,6}[-][0-9]{1,18}$"
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "tpinscestab": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^(1)$"
        },
        "nrinscestab": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "cnpjfont": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "iderend": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 100,
            "items": {
                "type": "object",
                "properties": {
                    "natrend": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[1-2]{1}[0-9]{4}$"
                    },
                    "observ": {
                        "required": false,
                        "type": ["string","null"],
                        "minxLength": 2,
                        "maxLength": 200
                    },
                    "inforec": {
                        "required": true,
                        "type": "array",
                        "minItems": 1,
                        "maxItems": 999,
                        "items": {
                            "type": "object",
                            "properties": {
                                "dtfg": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^2{1}0{1}[0-9]{2}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}$"
                                },
                                "vlrbruto": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrbaseir": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrir": {
                                    "required": true,
                                    "type": "number"
                                },
                                "infoprocret": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 1,
                                    "maxItems": 50,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "tpprocret": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "^[1-2]{1}$"
                                            },
                                            "nrprocret": {
                                                "required": true,
                                                "type": "string",
                                                "minLength": 3,
                                                "maxLength": 21
                                            },
                                            "codsusp": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 2,
                                                "maxLength": 14
                                            },
                                            "vlrbasesusoir": {
                                                "required": false,
                                                "type": ["number", "null"]
                                            },
                                            "vlrnir": {
                                                "required": false,
                                                "type": ["number", "null"]
                                            },
                                            "vlrdepir": {
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
            }
        }
    }
}';


$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 2;
$std->nrrecibo = '1-12-1234-123456-123456576';
$std->perapur = '2017-11';

$std->tpinscestab = "1"; //Opcional FIXO tipo de inscrição do estabelecimento contratante dos serviços: 1 - CNPJ;
$std->nrinscestab = '12345678901234'; //Obrigatório numero de inscrição do estabelecimento contratante dos serviços

$std->cnpjfont = '12345678901234';
$std->iderend[0] = new \stdClass();
$std->iderend[0]->natrend = '10001';
$std->iderend[0]->observ = 'blça bla bla';

$std->iderend[0]->inforec[0] = new \stdClass();
$std->iderend[0]->inforec[0]->dtfg = '2022-08-12';
$std->iderend[0]->inforec[0]->vlrbruto = 120000;
$std->iderend[0]->inforec[0]->vlrbaseir = 10000;
$std->iderend[0]->inforec[0]->vlrir = 2900;

$std->iderend[0]->inforec[0]->infoprocret[0] = new \stdClass();
$std->iderend[0]->inforec[0]->infoprocret[0]->tpprocret = '1';
$std->iderend[0]->inforec[0]->infoprocret[0]->nrprocret = '123455';
$std->iderend[0]->inforec[0]->infoprocret[0]->codsusp = '123';
$std->iderend[0]->inforec[0]->infoprocret[0]->vlrbasesuspir = 20000;
$std->iderend[0]->inforec[0]->infoprocret[0]->vlrnir = 100;
$std->iderend[0]->inforec[0]->infoprocret[0]->vlrdepir = 10000;

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
