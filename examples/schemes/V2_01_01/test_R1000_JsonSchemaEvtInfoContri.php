<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtInfoContribuinte';
$version = '2_01_01';

$jsonSchema = '{
    "title": "evtInfoContri",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 99999
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
        "modo": {
            "required": true,
            "type": "string",
            "pattern": "^(INC|ALT|EXC)$"
        },
        "infocadastro": {
            "required": true,
            "type": "object",
            "properties": {
                "classtrib": {
                    "required": true,
                    "type": "string",
                    "pattern": "^(00|01|02|03|04|06|07|08|09|10|11|13|14|21|22|60|70|80|85|99)$"
                },
                "indescrituracao": {
                    "required": true,
                    "type": "integer",
                    "minimum": 0,
                    "maximum": 1
                },
                "inddesoneracao": {
                    "required": true,
                    "type": "integer",
                    "minimum": 0,
                    "maximum": 1
                },
                "indacordoisenmulta": {
                    "required": true,
                    "type": "integer",
                    "minimum": 0,
                    "maximum": 1
                },
                "indsitpj": {
                    "required": false,
                    "type": ["integer","null"],
                    "minimum": 0,
                    "maximum": 4
                },
                "induniao": {
                    "required": false,
                    "type": ["integer","null"],
                    "minimum": 0,
                    "maximum": 1
                },
                "dttransffinslucr": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^20[0-9]{2}-[0-1][0-9]-[0-3][0-9]$"
                },
                "dtobito": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^(19|20)[0-9]{2}-(0[1-9]|1[0-2])-[0-3][0-9]$"
                },
                "contato": {
                    "required": true,
                    "type": "object",
                    "properties": {
                        "nmctt": {
                            "required": true,
                            "type": "string",
                            "minLength": 1,
                            "maxLength": 70
                        },
                        "cpfctt": {
                            "required": true,
                            "type": "string",
                            "pattern": "^[0-9]{11}$"
                        },
                        "fonefixo": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^[0-9 ()-]{1,13}$"
                        },
                        "fonecel": {
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
                }
            }
        },
        "softwarehouses": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 99,
            "items": {
                "type": "object",
                "properties": {
                    "cnpjsofthouse": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[0-9]{14}$"
                    },
                    "nmrazao": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 115
                    },
                    "nmcont": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 70
                    },
                    "telefone": {
                        "required": true,
                        "type": "string",
                        "minLength": 10,
                        "maxLength": 13,
                        "pattern": "^[0-9 ()-]{1,13}$"
                    },
                    "email": {
                        "required": false,
                        "type": ["string","null"],
                        "maxLength": 60
                    }
                }
            }
        },
        "infoefr": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "ideefr": {
                    "required": true,
                    "type": "string",
                    "pattern": "^(S|N)$"
                },
                "cnpjefr": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{14}$"
                }
            }
        }
    }
}';


$std = new \stdClass();
//$std->sequencial = 1;
$std->modo = 'INC';
$std->inivalid = '2017-01';
$std->fimvalid = '2017-12';

$std->infocadastro = new \stdClass();
$std->infocadastro->classtrib = '01';
$std->infocadastro->indescrituracao = 0;
$std->infocadastro->inddesoneracao = 0;
$std->infocadastro->indacordoisenmulta = 0;
$std->infocadastro->indsitpj = 0;
$std->infocadastro->induniao = 0;
$std->infocadastro->dttransffinslucr = '2021-10-12';
$std->infocadastro->dtobito = '2021-12-31';

$std->infocadastro->contato = new \stdClass();
$std->infocadastro->contato->nmctt = 'Fulano de Tal';
$std->infocadastro->contato->cpfctt = '12345678901';
$std->infocadastro->contato->fonefixo = '115555555';
$std->infocadastro->contato->fonecel = '1199999999';
$std->infocadastro->contato->email = 'fulano@email.com';

$std->softhouse[0] = new \stdClass();
$std->softhouse[0]->cnpjsofthouse = '12345678901234';
$std->softhouse[0]->nmrazao = 'Razao Social';
$std->softhouse[0]->nmcont = 'Fulano de Tal';
$std->softhouse[0]->telefone = '115555555';
$std->softhouse[0]->email = 'fulano@email.com';

$std->infoefr = new \stdClass();
$std->infoefr->ideefr = 'N';
$std->infoefr->cnpjefr = '12345678901234';


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
