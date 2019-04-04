<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtTotalContrib';
$version = '2_00_00';

$jsonSchema = '{
    "title": "evtTotalContrib",
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
            "type": ["array","null"],
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
        "nrprotentr": {
            "required": true,
            "type": "string"
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
        "infototalcontrib": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
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
                                "infocrtom": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 2,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "crtom": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "114106|116201"
                                            },
                                            "vlrcrtom": {
                                                "required": false,
                                                "type": ["number","null"],
                                                "minimum": 0.01
                                            },
                                            "vlrcrtomsusp": {
                                                "required": false,
                                                "type": ["number","null"],
                                                "minimum": 0.01
                                            }
                                        }
                                    }
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
                                    "pattern": "^[0-9]{14}"
                                },
                                "vlrtotalrep": {
                                    "required": true,
                                    "type": "number"
                                },
                                "crrecrepad": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{6}"
                                },
                                "vlrcrrecrepad": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrcrrecrepadsusp": {
                                    "required": false,
                                    "type": ["number","null"]
                                }
                            }
                        }
                    },
                    "rcoml": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 3,
                        "items": {
                            "type": "object",
                            "properties": {
                                "crcoml": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "165701|165702|164605|164606|121302|121304"
                                },
                                "vlrcrcoml": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrcrcomlsusp": {
                                    "required": false,
                                    "type": ["number","null"]
                                }
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
                                "crcprb": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "299101|298501|298504|298506"
                                },
                                "vlrcrcprb": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrcrcprbsusp": {
                                    "required": false,
                                    "type": ["number","null"]
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
$std->perapur = '2017-11';
$std->cdretorno = 'asdfer';
$std->descretorno = 'Retorno descrição sei la alguma coisa';

$std->regocorrs[0] = new \stdClass();
$std->regocorrs[0]->tpocorr = 2;
$std->regocorrs[0]->localerroaviso = 'este é o local onde aconteceu';
$std->regocorrs[0]->codresp = 'ksksk';
$std->regocorrs[0]->dscresp = 'Este campo lorem ipsum';

$std->nrprotentr = '1101010101010101010';
$std->dhprocess = '2017-11-22T12:34:00';
$std->tpev = '123456';
$std->idev = 'lslslslslslslslsl';
$std->hash = 'akhld wy slksndsjhslslkjsk';

$std->infototalcontrib[0] = new \stdClass();
$std->infototalcontrib[0]->nrrecarqbase = 'ksksksksk';
$std->infototalcontrib[0]->indexistinfo = 1;

$std->infototalcontrib[0]->rtom[0] = new \stdClass();
$std->infototalcontrib[0]->rtom[0]->cnpjprestador = '12345678901234';
$std->infototalcontrib[0]->rtom[0]->vlrtotalbaseret = 11111.11;

$std->infototalcontrib[0]->rtom[0]->infocrtom[0] = new \stdClass();
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->crtom = '114106'; //1162-01'
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->vlrcrtom = '20.00';
$std->infototalcontrib[0]->rtom[0]->infocrtom[0]->vlrcrtomsusp = '12.00';

$std->infototalcontrib[0]->rprest[0] = new \stdClass();
$std->infototalcontrib[0]->rprest[0]->tpinsctomador = 1; //4
$std->infototalcontrib[0]->rprest[0]->nrinsctomador = '12345678901';
$std->infototalcontrib[0]->rprest[0]->vlrtotalbaseret = 2000.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalretprinc = 10000.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalretadic = 100.00;
$std->infototalcontrib[0]->rprest[0]->vlrtotalnretprinc = 200.22;
$std->infototalcontrib[0]->rprest[0]->vlrtotalnretadic = 33.03;

$std->infototalcontrib[0]->rrecrepad[0] = new \stdClass();
$std->infototalcontrib[0]->rrecrepad[0]->cnpjassocdesp = '12345678901234';
$std->infototalcontrib[0]->rrecrepad[0]->vlrtotalrep = 200.22;
$std->infototalcontrib[0]->rrecrepad[0]->crrecrepad = '123456';
$std->infototalcontrib[0]->rrecrepad[0]->vlrcrrecrepad = 1000.11;
$std->infototalcontrib[0]->rrecrepad[0]->vlrcrrecrepadsusp = 511.55;

$std->infototalcontrib[0]->rcoml[0] = new \stdClass();
$std->infototalcontrib[0]->rcoml[0]->crcoml = '165701';
$std->infototalcontrib[0]->rcoml[0]->vlrcrcoml = 222.22;
$std->infototalcontrib[0]->rcoml[0]->vlrcrcomlsusp = 33.33;

$std->infototalcontrib[0]->rcprb[0] = new \stdClass();
$std->infototalcontrib[0]->rcprb[0]->crcprb = '299101';
$std->infototalcontrib[0]->rcprb[0]->vlrcrcprb = 1111.01;
$std->infototalcontrib[0]->rcprb[0]->vlrcrcprbsusp = 44.04;


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
