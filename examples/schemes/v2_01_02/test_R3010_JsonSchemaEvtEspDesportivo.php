<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtEspDesportivo';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evtEspDesportivo",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer", "null"],
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
            "pattern": "^[0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{6}[-][0-9]{1,18}$"
        },
        "dtapuracao": {
            "required": true,
            "type": "string",
            "pattern": "^2{1}0{1}[0-9]{2}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}$"
        },
        "nrinscestab": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}$"
        },
        "boletim": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 500,
            "items": {
                "type": "object",
                "properties": {
                    "nrboletim": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[0-9]{4}$"
                    },
                    "tpcompeticao": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 2
                    },
                    "categevento": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 4
                    },
                    "moddesportiva": {
                        "required": true,
                        "type": "string",
                        "maxLength": 100
                    },
                    "nomecompeticao": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 100
                    },
                    "cnpjmandante": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[0-9]{14}$"
                    },
                    "cnpjvisitante": {
                        "required": false,
                        "type": ["string", "null"],
                        "pattern": "^[0-9]{14}$"
                    },
                    "nomevisitante": {
                        "required": true,
                        "type": "string",
                        "maxLength": 80
                    },
                    "pracadesportiva": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 100
                    },
                    "codmunic": {
                        "required": false,
                        "type": ["string", "null"],
                        "pattern": "^[0-9]{7}$"
                    },
                    "uf": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(AC|AL|AM|AP|BA|CE|DF|ES|GO|MA|MG|MS|MT|PA|PB|PE|PI|PR|RJ|RN|RO|RR|RS|SC|SE|SP|TO)$"
                    },
                    "qtdepagantes": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 999999
                    },
                    "qtdenaopagantes": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 999999
                    },
                    "receitaingressos": {
                        "required": true,
                        "type": "array",
                        "minItems": 1,
                        "maxItems": 999,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpingresso": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 4
                                },
                                "descingr": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 30
                                },
                                "qtdeingrvenda": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 999999
                                },
                                "qtdeingrvendidos": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 999999
                                },
                                "qtdeingrdev": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 999999
                                },
                                "precoindiv": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrtotal": {
                                    "required": true,
                                    "type": "number"
                                }
                            }
                        }
                    },
                    "outrasreceitas": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 1,
                        "maxItems": 999,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpreceita": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 5
                                },
                                "vlrreceita": {
                                    "required": true,
                                    "type": "number"
                                },
                                "descreceita": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 20
                                }
                            }
                        }
                    }
                }
            }
        },
        "receitatotal": {
            "required": true,
            "type": "object",
            "properties": {
                "vlrreceitatotal": {
                    "required": true,
                    "type": "number"
                },
                "vlrcp": {
                    "required": true,
                    "type": "number"
                },
                "vlrcpsusptotal": {
                    "required": false,
                    "type": ["number", "null"]
                },
                "vlrreceitaclubes": {
                    "required": true,
                    "type": "number"
                },
                "vlrretparc": {
                    "required": true,
                    "type": "number"
                },
                "infoproc": {
                    "required": false,
                    "type": ["array", "null"],
                    "minItems": 1,
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
                                "minLength": 1,
                                "maxLength": 21
                            },
                            "codsusp": {
                                "required": false,
                                "type": ["string", "null"],
                                "pattern": "^[0-9]{14}$"
                            },
                            "vlrcpsusp": {
                                "required": true,
                                "type": "number"
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
$std->nrrecibo = '1-12-1234-123456-123456576';
$std->dtapuracao = '2017-12-01';
$std->nrinscestab = '12345678901234';

$std->boletim[0] = new \stdClass();
$std->boletim[0]->nrboletim = '1234';
$std->boletim[0]->tpcompeticao = 1;
$std->boletim[0]->categevento = 1;
$std->boletim[0]->moddesportiva = 'corrida de bigas';
$std->boletim[0]->nomecompeticao = 'Torneio tornado';
$std->boletim[0]->cnpjmandante = '12345678901234';
$std->boletim[0]->cnpjvisitante = '12345678901234';
$std->boletim[0]->nomevisitante = 'Quebra Toco FC';
$std->boletim[0]->pracadesportiva = 'Estadio do outro';
$std->boletim[0]->codmunic = '1234567';
$std->boletim[0]->uf = 'PR';
$std->boletim[0]->qtdepagantes = 3200;
$std->boletim[0]->qtdenaopagantes = 200;

$std->boletim[0]->receitaingressos[0] = new \stdClass();
$std->boletim[0]->receitaingressos[0]->tpingresso = 4;
$std->boletim[0]->receitaingressos[0]->descingr = 'entrada';
$std->boletim[0]->receitaingressos[0]->qtdeingrvenda = 34568;
$std->boletim[0]->receitaingressos[0]->qtdeingrvendidos = 24567;
$std->boletim[0]->receitaingressos[0]->qtdeingrdev = 3;
$std->boletim[0]->receitaingressos[0]->precoindiv = 23.76;
$std->boletim[0]->receitaingressos[0]->vlrtotal = 290323.99;

$std->boletim[0]->outrasreceitas[0] = new \stdClass();
$std->boletim[0]->outrasreceitas[0]->tpreceita = 4;
$std->boletim[0]->outrasreceitas[0]->vlrreceita = 392000029.56;
$std->boletim[0]->outrasreceitas[0]->descreceita = 'money money dim dim';

$std->receitatotal = new \stdClass();
$std->receitatotal->vlrreceitatotal = 3456720000.36;
$std->receitatotal->vlrcp = 123450900.0;
$std->receitatotal->vlrcpsusptotal = 3498282.84;
$std->receitatotal->vlrreceitaclubes = 489388.43;
$std->receitatotal->vlrretparc = 123.76;

$std->receitatotal->infoproc[0] = new \stdClass();
$std->receitatotal->infoproc[0]->tpproc = 1;
$std->receitatotal->infoproc[0]->nrproc = '829js,n,sn,n';
$std->receitatotal->infoproc[0]->codsusp = '12345678901234';
$std->receitatotal->infoproc[0]->vlrcpsusp = 2345678.93;

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
