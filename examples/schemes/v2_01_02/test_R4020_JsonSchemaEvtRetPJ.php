<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evt4020PagtoBeneficiarioPJ';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evt4020PagtoBeneficiarioPJ",
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
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
        },
        "natjur": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^[0-9]{4}$"
        },
        "tpinscestab": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^[1-3]{1}$"
        },
        "nrinscestab": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{8}|[0-9]{11}|[0-9]{14}$"
        },
        "idebenef": {
            "required": true,
            "type": "object",
            "properties": {
                "cnpjbenef": {
                    "required": false,
                    "type": "string",
                    "pattern": "^[0-9]{14}$"
                },
                "nmbenef": {
                    "required": false,
                    "type": [
                        "string",
                        "null"
                    ],
                    "minLength": 2,
                    "maxLength": 70
                },
                "isenimun": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^(2|3)$"
                },
                "ideevtadic": {
                    "required": false,
                    "type": ["string","null"],
                    "minLength": 1,
                    "maxLength": 8
                }
            }
        },
        "idepgto": {
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
                        "pattern": "^[0-9]{5}$"
                    },
                    "observ": {
                        "required": false,
                        "type": [
                            "string",
                            "null"
                        ],
                        "minLength": 3,
                        "maxLength": 200
                    },
                    "infopgto": {
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
                                    "pattern": "^20[0-9]{2}-[0-1][0-9]-[0-3][0-9]$"
                                },
                                "vlrbruto": {
                                    "required": true,
                                    "type": "number"
                                },
                                "indfciscp": {
                                    "required": false,
                                    "type": [
                                        "string",
                                        "null"
                                    ],
                                    "pattern": "^(1|2)$"
                                },
                                "nrinscfciscp": {
                                    "required": false,
                                    "type": [
                                        "string",
                                        "null"
                                    ],
                                    "pattern": "^[0-9]{14}$"
                                },
                                "percscp": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "indjud": {
                                    "required": false,
                                    "type": [
                                        "string",
                                        "null"
                                    ],
                                    "pattern": "^(S|N)$"
                                },
                                "paisresidext": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{3}$"
                                },
                                "dtescrcont": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^(20[0-9]{2}-[0-1][0-9]-[0-3][0-9])$"
                                },
                                "observ": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 3,
                                    "maxLength": 200
                                },
                                "retencoes": {
                                    "required": false,
                                    "type": [
                                        "object",
                                        "null"
                                    ],
                                    "properties": {
                                        "vlrbaseir": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrir": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrbaseagreg": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlragreg": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrbasecsll": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrcsll": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrbasecofins": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrcofins": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrbasepp": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        },
                                        "vlrpp": {
                                            "required": false,
                                            "type": [
                                                "number",
                                                "null"
                                            ]
                                        }
                                    }
                                },
                                "infoprocret": {
                                    "required": false,
                                    "type": [
                                        "array",
                                        "null"
                                    ],
                                    "minItems": 0,
                                    "maxItems": 50,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "tpprocret": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "^(1|2)$"
                                            },
                                            "nrprocret": {
                                                "required": true,
                                                "type": "string",
                                                "minLength": 3,
                                                "maxLength": 21
                                            },
                                            "codsusp": {
                                                "required": false,
                                                "type": [
                                                    "string",
                                                    "null"
                                                ],
                                                "minLength": 2,
                                                "maxLength": 14
                                            },
                                            "vlrbasesuspir": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrnir": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrdepir": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrbasesuspcsll": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrncsll": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrdepcsll": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrbasesuspcofins": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrncofins": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrndepcofins": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrbaseSusppp": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrnpp": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            },
                                            "vlrdeppp": {
                                                "required": false,
                                                "type": [
                                                    "number",
                                                    "null"
                                                ]
                                            }
                                        }
                                    }
                                },
                                "infoprocjud": {
                                    "required": false,
                                    "type": [
                                        "object",
                                        "null"
                                    ],
                                    "properties": {
                                        "nrproc": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 3,
                                            "maxLength": 21
                                        },
                                        "indorigrec": {
                                            "required": true,
                                            "type": "string",
                                            "pattern": "^(1|2)$"
                                        },
                                        "cnpjorigrecurso": {
                                            "required": false,
                                            "type": [
                                                "string",
                                                "null"
                                            ],
                                            "pattern": "^[0-9]{14}$"
                                        },
                                        "desc": {
                                            "required": false,
                                            "type": [
                                                "string",
                                                "null"
                                            ],
                                            "minLength": 2,
                                            "maxLength": 30
                                        },
                                        "despprocjud": {
                                            "required": false,
                                            "type": [
                                                "object",
                                                "null"
                                            ],
                                            "properties": {
                                                "vlrdespcustas": {
                                                    "required": true,
                                                    "type": "number"
                                                },
                                                "vlrdespadvogados": {
                                                    "required": true,
                                                    "type": "number"
                                                },
                                                "ideadv": {
                                                    "required": false,
                                                    "type": [
                                                        "array",
                                                        "null"
                                                    ],
                                                    "minItems": 0,
                                                    "maxItems": 99,
                                                    "items": {
                                                        "type": "object",
                                                        "properties": {
                                                            "tpinscadv": {
                                                                "required": true,
                                                                "type": "string",
                                                                "pattern": "^(1|2)$"
                                                            },
                                                            "nrinscadv": {
                                                                "required": true,
                                                                "type": "string",
                                                                "pattern": "^[0-9]{8}|[0-9]{11}|[0-9]{14}$"
                                                            },
                                                            "vlradv": {
                                                                "required": false,
                                                                "type": [
                                                                    "number",
                                                                    "null"
                                                                ]
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                },
                                "infopgtoext": {
                                    "required": false,
                                    "type": [
                                        "object",
                                        "null"
                                    ],
                                    "properties": {
                                        "indnif": {
                                            "required": true,
                                            "type": "string",
                                            "pattern": "^(1|2|3)$"
                                        },
                                        "nifbenef": {
                                            "required": false,
                                            "type": [
                                                "string",
                                                "null"
                                            ],
                                            "minLength": 2,
                                            "maxLength": 30
                                        },
                                        "relfontpg": {
                                            "required": true,
                                            "type": "string",
                                            "pattern": "^[0-9]{3}$"
                                        },
                                        "frmtribut": {
                                            "required": true,
                                            "type": "string",
                                            "pattern": "^(10|11|12|13|30|40|41|42|43|44|50)$"
                                        },
                                        "endext": {
                                            "required": false,
                                            "type": [
                                                "object",
                                                "null"
                                            ],
                                            "properties": {
                                                "dsclograd": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 1,
                                                    "maxLength": 80
                                                },
                                                "nrlograd": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 1,
                                                    "maxLength": 10
                                                },
                                                "complem": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 2,
                                                    "maxLength": 30
                                                },
                                                "bairro": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 1,
                                                    "maxLength": 60
                                                },
                                                "cidade": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 2,
                                                    "maxLength": 40
                                                },
                                                "estado": {
                                                    "required": false,
                                                    "type": [
                                                        "string",
                                                        "null"
                                                    ],
                                                    "minLength": 2,
                                                    "maxLength": 40
                                                },
                                                "codpostal": {
                                                    "required": false,
                                                    "type": ["string", "null"],
                                                    "pattern": "^[0-9]{4,12}$"
                                                },
                                                "telef": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "pattern": "^[0-9]{0,15}$"
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
    }
}';

$std = new \stdClass();
//$std->sequencial = 1; //Opcional se não informado será gerado automaticamente
$std->indretif = 1;
$std->nrrecibo = '1-12-1234-1234-123456576';
$std->perapur = '2017-12';

$std->natjur = '1234'; //infoComplContri/natJur
$std->tpinscestab = '1';
$std->nrinscestab = '12345678901234';

$std->idebenef = new stdClass();
$std->idebenef->cnpjbenef = '12345678901234';
$std->idebenef->nmbenef = 'Fulano de Tal Ltda';
$std->idebenef->isenimun = '2';
$std->idebenef->ideevtadic = "AB123456";

$std->idepgto[0] = new stdclass();
$std->idepgto[0]->natrend = '10001';
$std->idepgto[0]->observ = 'bla bla bla';

$std->idepgto[0]->infopgto[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->dtfg = '2022-07-15';
$std->idepgto[0]->infopgto[0]->vlrbruto = 7834.45;
$std->idepgto[0]->infopgto[0]->indfciscp = '1';
$std->idepgto[0]->infopgto[0]->nrinscfciscp = '12345678901234';
$std->idepgto[0]->infopgto[0]->percscp = 20;
$std->idepgto[0]->infopgto[0]->indjud = 'N';
$std->idepgto[0]->infopgto[0]->paisresidext = '169';
$std->idepgto[0]->infopgto[0]->dtescrcont = '2022-03-03';
$std->idepgto[0]->infopgto[0]->observ = 'Observações não sei para o que';

$std->idepgto[0]->infopgto[0]->retencoes = new stdclass();
$std->idepgto[0]->infopgto[0]->retencoes->vlrbaseir = 4324.56;
$std->idepgto[0]->infopgto[0]->retencoes->vlrir = 400.33;
$std->idepgto[0]->infopgto[0]->retencoes->vlrbaseagreg = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlragreg = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrbasecsll = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrcsll = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrbasecofins = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrcofins = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrbasepp = 1000.00;
$std->idepgto[0]->infopgto[0]->retencoes->vlrpp = 1000.00;

$std->idepgto[0]->infopgto[0]->infoprocret[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocret[0]->tpprocret = '1';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->nrprocret = '22222222';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->codsusp = '12345';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrbasesuspir = 200.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrnir = 10.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrdepir = 456.78;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrbasesuspcsll = 0.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrncsll = 10.11;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrdepcsll = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrbasesuspcofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrncofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrdepcofins = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrbasesuspPP = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrnpp = 20.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrdeppp = 20.00;

$std->idepgto[0]->infopgto[0]->infoprocjud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->nrproc = '123456';
$std->idepgto[0]->infopgto[0]->infoprocjud->indorigrec = '1';
$std->idepgto[0]->infopgto[0]->infoprocjud->cnpjorigcecurso = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoprocjud->desc = 'blça bla bla';

$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->vlrdespcustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->vlrdespadvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->ideadv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->ideadv[0]->tpinscadv = '1';
$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->ideadv[0]->nrinscadv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoprocjud->despprocjud->ideadv[0]->vlradv = 342.66;

$std->idepgto[0]->infopgto[0]->infopgtoext = new stdclass();
$std->idepgto[0]->infopgto[0]->infopgtoext->indnif = '1';
$std->idepgto[0]->infopgto[0]->infopgtoext->nifbenef = '123456';
$std->idepgto[0]->infopgto[0]->infopgtoext->relfontpg = '500';
$std->idepgto[0]->infopgto[0]->infopgtoext->frmtribut = '10';

$std->idepgto[0]->infopgto[0]->infopgtoext->endext = new stdclass();
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->dsclograd = 'logradouro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->nrlograd = '100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->complem = 'SALA 100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->bairro = 'bairro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->cidade = 'cidade';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->estado = 'estado';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->codpostal = '9999';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->telef = '12345678901';



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
