<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtTomadorServicos';
$version = '1_05_01';

$jsonSchema = '{
    "title": "evtServTom",
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
            "pattern": "^([0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{4}[-][0-9]{1,18})$"
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "tpinscestab": {
            "required": true,
            "type": "string",
            "pattern": "^(1|4)$"
        },
        "nrinscestab": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{12}|[0-9]{14}$"
        },
        "indobra": {
            "required": true,
            "type": "integer",
            "minimum": 0,
            "maximum": 2
        },
        "cnpjprestador": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "vlrtotalbruto": {
            "required": true,
            "type": "number",
            "multipleOf": 0.01
        },
        "vlrtotalbaseret": {
           "required": true,
           "type": "number"
        },
        "vlrtotalretprinc": {
            "required": true,
            "type": "number"
        },
        "vlrtotalretadic": {
            "required": false,
            "type": ["number","null"]
        },
        "vlrtotalnretprinc": {
             "required": false,
             "type": ["number","null"]
        },
        "vlrtotalnretadic": {
             "required": false,
             "type": ["number","null"]
        },
        "indcprb": {
            "required": true,
            "type": "integer",
            "minimum": 0,
            "maximum": 1
        },    
        "nfs": {
           "required": true,
           "type": "array",
           "minItems": 1,
           "maxItems": 500,
           "items": {
                "type": "object",
                "properties": {
                    "serie": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 5
                    },
                    "numdocto": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 15
                    },
                    "dtemissaonf": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                    },
                    "vlrbruto": {
                        "required": true,
                        "type": "number",
                        "multipleOf": 0.01
                    },
                    "obs": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 250
                    },
                    "infotpserv": {
                        "required": true,
                        "type": "array",
                        "minItems": 1,
                        "maxItems": 9,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpservico": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{9}$"
                                },
                                "vlrbaseret": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrretencao": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrretsub": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrnretprinc": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrservicos15": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrservicos20": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrservicos25": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlradicional": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrnretadic": {
                                    "required": false,
                                    "type": ["number","null"]
                                }                            
                            }
                        }
                    }    
                }
            }
        },
        "infoprocretpr": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 50,
            "items": {
                "type": "object",
                "properties": {
                    "tpprocretprinc": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 2
                    },
                    "nrprocretprinc": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 21
                    },
                    "codsuspprinc": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "^[0-9]{0,14}$"
                    },
                    "valorprinc": {
                        "required": true,
                        "type": "number",
                        "multipleOf": 0.01
                    }                
                }
            }
        },
        "infoprocretad": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 50,
            "items": {
                "type": "object",
                "properties": {
                    "tpprocretadic": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 2
                    },
                    "nrprocretadic": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 21
                    },
                    "codsuspadic": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "^[0-9]{0,14}$"
                    },
                    "valoradic": {
                        "required": true,
                        "type": "number",
                        "multipleOf": 0.01
                    }                
                }
            }    
        }    
    }
}'; 

$std = new \stdClass();
//$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '1-00-1234-1234-1234556789012345';
$std->perapur = '2017-11';
$std->tpinscestab = "1";
$std->nrinscestab = '12345678901234';
$std->indobra = '0';

$std->cnpjprestador = '12345678901234';
$std->vlrtotalbruto = '456.93';
$std->vlrtotalbaseret = 45.80;
$std->vlrtotalretprinc = 5;
$std->vlrtotalretadic = 54.35;
$std->vlrtotalnretprinc = 345.66;
$std->vlrtotalnretadic = 345.90;
$std->codanacont = 'skljslksjksj';
$std->indcprb = 0;

$std->nfs[0] = new \stdClass();
$std->nfs[0]->serie = '001';
$std->nfs[0]->numdocto = '265465';
$std->nfs[0]->dtemissaonf = '2017-01-22';
$std->nfs[0]->vlrbruto = 200.00;
$std->nfs[0]->obs = 'observacao pode ser nula';

$std->nfs[0]->infotpserv[0] = new \stdClass();
$std->nfs[0]->infotpserv[0]->tpservico = '123456789';
$std->nfs[0]->infotpserv[0]->vlrbaseret = 234.90;
$std->nfs[0]->infotpserv[0]->vlrretencao = 12.75;
$std->nfs[0]->infotpserv[0]->vlrretsub = 34.55;
$std->nfs[0]->infotpserv[0]->vlrnretprinc = 2345.75;
$std->nfs[0]->infotpserv[0]->vlrservicos15 = 22;
$std->nfs[0]->infotpserv[0]->vlrservicos20 = 33;
$std->nfs[0]->infotpserv[0]->vlrservicos25 = 44;
$std->nfs[0]->infotpserv[0]->vlradicional = 5;
$std->nfs[0]->infotpserv[0]->vlrnretadic = 1.55;

$std->infoprocretpr[0] = new \stdClass();
$std->infoprocretpr[0]->tpprocretprinc = 1;
$std->infoprocretpr[0]->nrprocretprinc = 'ZYX987';
$std->infoprocretpr[0]->codsuspprinc = '12345678901234';
$std->infoprocretpr[0]->valorprinc = 200.98;

$std->infoprocretad[0] = new \stdClass();
$std->infoprocretad[0]->tpprocretadic = 1;
$std->infoprocretad[0]->nrprocretadic = 'ACB21';
$std->infoprocretad[0]->codsuspadic = '12345678901234';
$std->infoprocretad[0]->valoradic = 1000.23;



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
