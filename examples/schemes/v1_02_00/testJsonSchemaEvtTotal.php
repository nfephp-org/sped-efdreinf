<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtTotal';
$version = '1_02_00';

$jsonSchema = '{
    "title": "evtTotal",
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
        "cdretorno": {
            "required": true,
            "type": "string",
            "maxLength": 6
        },
        "descretorno": {
            "required": true,
            "type": "string",
            "maxLength": 255
        },
        "regocorrs": {
            "required": false,
            "type": "array",
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "tpocorr": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 2
                    },
                    "localerroaviso": {
                        "required": true,
                        "type": "string",
                        "maxLength": 100
                    },
                    "codresp": {
                        "required": true,
                        "type": "string",
                        "maxLength": 6
                    },
                    "dscresp": {
                        "required": true,
                        "type": "string",
                        "maxLength": 255
                    }
                }
            }
        },
        "dhprocess": {
            "required": true,
            "type": "string"
        },
        "tpev": {
            "required": true,
            "type": "string",
            "maxLength": 6
        },
        "idev": {
            "required": true,
            "type": "string",
            "maxLength": 20
        },
        "hash": {
            "required": true,
            "type": "string",
            "maxLength": 60
        },
        "nrrecarqbase": {
            "required": false,
            "type": ["string","null"],
            "maxLength": 52
        },
        "indexistinfo": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 3
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
        "rtom": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "cnpjprestador": {
                        "required": true,
                        "type": "string",
                        "maxLength": 14,
                        "pattern": "^[0-9]"
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
                    }
                }
            }    
        },
        "rprest": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "tpinsctomador": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 4
                    },
                    "nrinsctomador": {
                        "required": true,
                        "type": "string",
                        "maxLength": 14,
                        "pattern": "^[0-9]"
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
                    }
                }
            }    
        },
        "rrecrepad": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "cnpjassocdesp": {
                        "required": true,
                        "type": "string",
                        "maxLength": 14,
                        "pattern": "^[0-9]"
                    },
                    "vlrtotalrep": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrtotalret": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrtotalnret": {
                        "required": false,
                        "type": ["number","null"]
                    }
                }
            }    
        },
        "rcoml": {
            "required": false,
            "type": ["object","null"],
            "properties": {
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
        },
        "rcprb": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 4,
            "items": {
                "type": "object",
                "properties": {
                    "codrec": {
                        "required": true,
                        "type": "string",
                        "maxLength": 6,
                        "pattern": "^[0-9]"
                    },
                    "vlrcpapurtotal": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrcprbsusp": {
                        "required": true,
                        "type": "number"
                    }
                }
            }
        }
    }
}';


$std = new \stdClass();
$std->sequencial = 1;
$std->perapur = '2017-11';
$std->cdretorno = 'asdfer';
$std->descretorno = 'Retorno descrição sei la alguma coisa';

$std->regocorrs[0] = new \stdClass();
$std->regocorrs[0]->tpocorr = 2;
$std->regocorrs[0]->localerroaviso = 'este é o local onde aconteceu';
$std->regocorrs[0]->codresp = 'ksksk';
$std->regocorrs[0]->dscresp = 'Este campo lorem ipsum';

$std->dhprocess = '2017-11-22 12:34:00';
$std->tpev = '123456';
$std->idev = 'lslslslslslslslsl';
$std->hash = 'akhld wy slksndsjhslslkjsk';
$std->nrrecarqbase = 'ksksksksksksks';
$std->indexistinfo = 2;
$std->indescrituracao = 0;
$std->inddesoneracao = 1;
$std->indacordoisenmulta = 1;

$std->rtom[0] = new \stdClass();
$std->rtom[0]->cnpjprestador = '12345678901234';
$std->rtom[0]->vlrtotalbaseret = 11111.11;
$std->rtom[0]->vlrtotalretprinc = 2222.22;
$std->rtom[0]->vlrtotalretadic = 3333.33;
$std->rtom[0]->vlrtotalnretprinc = 4444.44;
$std->rtom[0]->vlrtotalnretadic = 5555.55;

$std->rprest[0] = new \stdClass();
$std->rprest[0]->tpinsctomador = 4;
$std->rprest[0]->nrinsctomador = '12345678901234';
$std->rprest[0]->vlrtotalbaseret = 2903030.92;
$std->rprest[0]->vlrtotalretprinc = 9292929;
$std->rprest[0]->vlrtotalretadic = 1111111;
$std->rprest[0]->vlrtotalnretprinc = 29982818.92;
$std->rprest[0]->vlrtotalnretadic = 1772717.88;

$std->rrecrepad[0] = new \stdClass();
$std->rrecrepad[0]->cnpjassocdesp = '12345678901234';
$std->rrecrepad[0]->vlrtotalrep = 20020.37;
$std->rrecrepad[0]->vlrtotalret = 292929.22;
$std->rrecrepad[0]->vlrtotalnret = 8748383.00;

$std->rcoml = new \stdClass();
$std->rcoml->vlrcpapur = 12345.98;
$std->rcoml->vlrratapur = 12345.98;
$std->rcoml->vlrsenarapur = 12345.98;
$std->rcoml->vlrcpsusp = 12345.98;
$std->rcoml->vlrratsusp = 12345.98;
$std->rcoml->vlrsenarsusp = 12345.98;

$std->rcprb[0] = new \stdClass();
$std->rcprb[0]->codrec = '123456';
$std->rcprb[0]->vlrcpapurtotal = 2345675.93;
$std->rcprb[0]->vlrcprbsusp = 2311.87;


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
