<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evt4010PagtoBeneficiarioPF';
$version = '2_01_02';

$jsonSchema = '{
    "title": "evt4010PagtoBeneficiarioPF",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 99999
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])-(0[1-9]|1[0-2])$"
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
                "cpfbenef": {
                    "required": false,
                    "type": "string",
                    "pattern": "^[0-9]{11}$"
                },
                "nmbenef": {
                    "required": false,
                    "type": ["string","null"],
                    "minLength": 2,
                    "maxLength": 70
                },
                "ideevtadic": {
                    "required": false,
                    "type": ["string","null"],
                    "minLength": 1,
                    "maxLength": 8
                }
            }
        },
        "idedep": {
            "required": false,
            "type": "array",
            "minItems": 0,
            "maxItems": 999,
            "items": {
                "type": "object",
                "properties": {
                    "cpfdep": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[0-9]{11}$"
                    },
                    "reldep": {
                        "required": true,
                        "type": "string",
                        "pattern": "^(1|2|3|6|9|10|11|12|99)$"
                    },
                    "descrdep": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 2,
                        "maxLength": 30
                    }
                }
            }
        },
        "idepgto": {
            "natrend": {
                "required": true,
                "type": "string",
                "pattern": "^[0-9]{5}$"
            },
            "observ": {
                "required": false,
                "type": ["string","null"],
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
                        "compfp": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^20([0-9]{2})-(0[1-9]|1[0-2])$"
                        },
                        "inddecterc": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^(S)$"
                        },
                        "vlrrendbruto": {
                            "required": true,
                            "type": "number"
                        },
                        "vlrrendtrib": {
                            "required": false,
                            "type": ["number","null"]
                        },
                        "vlrir": {
                            "required": false,
                            "type": ["number","null"]
                        },
                        "indrra": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^(S)$"
                        },
                        "indfciscp": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^(1|2)$"
                        },
                        "nrinscfciscp": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^[0-9]{14}$"
                        },
                        "percscp": {
                            "required": false,
                            "type": ["number","null"],
                            "maximum": 100
                        },
                        "indjud": {
                            "required": false,
                            "type": ["string","null"],
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
                        "detded": {
                            "required": false,
                            "type": ["array","null"],
                            "minItems": 0,
                            "maxItems": 25,
                            "items": {
                                "type": "object",
                                "properties": {
                                    "indtpdeducao": {
                                        "required": true,
                                        "type": "string",
                                        "pattern": "^(1|2|3|4|5|7)$"
                                    },
                                    "vlrdeducao": {
                                        "required": true,
                                        "type": "number"
                                    },
                                    "infoentid": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^(S|N)$"
                                    },
                                    "nrinscprevcomp": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^[0-9]{14}$"
                                    },
                                    "vlrpatrocfunp": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "benefpen": {
                                        "required": false,
                                        "type": ["array","null"],
                                        "minItems": 0,
                                        "maxItems": 99,
                                        "items": {
                                            "type": "object",
                                            "properties": {
                                                "cpfpen": {
                                                    "required": true,
                                                    "type": "string",
                                                    "pattern": "^[0-9]{11}$"
                                                },
                                                "vlrdepen": {
                                                    "required": true,
                                                    "type": "number"
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        "rendisento": {
                            "required": false,
                            "type": ["array","null"],
                            "minItems": 0,
                            "maxItems": 25,
                            "items": {
                                "type": "object",
                                "properties": {
                                    "tpisencao": {
                                        "required": true,
                                        "type": "string",
                                        "pattern": "^(1|2|3|4|5|6|7|8|10|11|99)$"
                                    },
                                    "vlrisento": {
                                        "required": true,
                                        "type": "number"
                                    },
                                    "descrendimento": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 2,
                                        "maxLength": 100
                                    },
                                    "dtlaudo": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^(19|20)[0-9]{2}-(0[1-9]|1[0-2])-[0-3][0-9]$"
                                    }
                                }
                            }
                        },
                        "infoprocret": {
                            "required": false,
                            "type": ["array","null"],
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
                                        "type": ["string","null"],
                                        "minLength": 2,
                                        "maxLength": 14
                                    },
                                    "vlrnretido": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "vlrdepjud": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "vlrcmpanocal": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "vlrcmpanoant": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "vlrrendsusp": {
                                        "required": false,
                                        "type": ["number","null"]
                                    },
                                    "dedsusp": {
                                        "required": false,
                                        "type": ["array","null"],
                                        "minItems": 0,
                                        "maxItems": 25,
                                        "items": {
                                            "type": "object",
                                            "properties": {
                                                "indtpdeducao": {
                                                    "required": true,
                                                    "type": "string",
                                                    "pattern": "^(1|2|3|4|5|7|8)$"
                                                },
                                                "vlrdedsusp": {
                                                    "required": false,
                                                    "type": ["number","null"]
                                                },
                                                "benefpen": {
                                                    "required": false,
                                                    "type": ["array","null"],
                                                    "minItems": 0,
                                                    "maxItems": 99,
                                                    "items": {
                                                        "type": "object",
                                                        "properties": {
                                                            "cpfpen": {
                                                                "required": true,
                                                                "type": "string",
                                                                "pattern": "^[0-9]{11}$"
                                                            },
                                                            "vlrdepensusp": {
                                                                "required": true,
                                                                "type": "number"
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        "inforra": {
                            "required": false,
                            "type": ["object","null"],
                            "properties": {
                                "tpprocrra": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(1|2)$"
                                },
                                "nrprocrra": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 3,
                                    "maxLength": 21
                                },
                                "indorigrec": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(1|2)$"
                                },
                                "descrra": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 4,
                                    "maxLength": 50
                                },
                                "qtdmesesrra": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 0,
                                    "maximum": 92
                                },
                                "cnpjorigrecurso": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{14}$"
                                },
                                "despprocjud": {
                                    "required": false,
                                    "type": ["object","null"],
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
                                            "type": ["array","null"],
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
                                                        "pattern": "^[0-9]{11}|[0-9]{14}$"
                                                    },
                                                    "vlradv": {
                                                        "required": false,
                                                        "type": ["number","null"]
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        "infoprocjud": {
                            "required": false,
                            "type": ["object","null"],
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
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{14}$"
                                },
                                "desc": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 2,
                                    "maxLength": 50
                                },
                                "despprocjud": {
                                    "required": false,
                                    "type": ["object","null"],
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
                                            "type": ["array","null"],
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
                                                        "pattern": "^[0-9]{11}|[0-9]{14}$"
                                                    },
                                                    "vlradv": {
                                                        "required": false,
                                                        "type": ["number","null"]
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
                            "type": ["object","null"],
                            "properties": {
                                "indnif": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(1|2|3)$"
                                },
                                "nifbenef": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 2,
                                    "maxLength": 30
                                },
                                "fmrtribut": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(10|11|12|13|30|40|41|42|43|44|50)$"
                                },
                                "endext": {
                                    "required": false,
                                    "type": ["object","null"],
                                    "properties": {
                                        "dsclograd": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 80
                                        },
                                        "nrlograd": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "complem": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 2,
                                            "maxLength": 30
                                        },
                                        "bairro": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 60
                                        },
                                        "cidade": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 2,
                                            "maxLength": 40
                                        },
                                        "estado": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 2,
                                            "maxLength": 40
                                        },
                                        "codpostal": {
                                            "required": false,
                                            "type": ["string","null"],
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
        },
        "ideopsaude": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 99,
            "items": {
                "type": "object",
                "properties": {
                    "nrinsc": {
                        "required": true,
                        "type": "string",
                        "pattern": "^[0-9]{14}$"
                    },
                    "regans": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "^[0-9]{2,6}$"
                    },
                    "vlrsaude": {
                        "required": true,
                        "type": "number"
                    },
                    "inforeemb": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 99,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpinsc": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[1-2]{1}$"
                                },
                                "nrinsc": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{11}|[0-9]{14}$"
                                },
                                "vlrreemb": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "vlrreembant": {
                                    "required": false,
                                    "type": ["number","null"]
                                }
                            }
                        }
                    },
                    "infodependpl": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 99,
                        "items": {
                            "type": "object",
                            "properties": {
                                "cpfdep": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{11}$"
                                },
                                "vlrsaude": {
                                    "required": true,
                                    "type": "number"
                                },
                                "inforeembdep": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 99,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "tpinsc": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "pattern": "^[1-2]{1}$"
                                            },
                                            "nrinsc": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "^[0-9]{11}|[0-9]{14}$"
                                            },
                                            "vlrreemb": {
                                                "required": false,
                                                "type": ["number","null"]
                                            },
                                            "vlrreembant": {
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
$std->idebenef->cpfbenef = '12345678901';
$std->idebenef->nmbenef = 'Fulano de Tal';
$std->idebenef->ideevtadic = 'AB345678';

$std->idedep[0] = new stdclass();
$std->idedep[0]->cpfdep = '12345678901';
$std->idedep[0]->reldep = '1';
$std->idedep[0]->descdep = 'esposa';

$std->idepgto[0] = new stdclass();
$std->idepgto[0]->natrend = '10001';
$std->idepgto[0]->observ = 'bla bla bla';

$std->idepgto[0]->infopgto[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->dtgf = '2022-07-15';
$std->idepgto[0]->infopgto[0]->compfp = '2022';
$std->idepgto[0]->infopgto[0]->inddecterc = 'S';
$std->idepgto[0]->infopgto[0]->vlrrendbruto = 7834.45;
$std->idepgto[0]->infopgto[0]->vlrrendtrib = 4324.56;
$std->idepgto[0]->infopgto[0]->vlrir = 400.33;
$std->idepgto[0]->infopgto[0]->indrra = 'S';
$std->idepgto[0]->infopgto[0]->indfciscp = '1';
$std->idepgto[0]->infopgto[0]->nrinscfciscp = '12345678901234';
$std->idepgto[0]->infopgto[0]->percscp = 20;
$std->idepgto[0]->infopgto[0]->indjud = 'N';
$std->idepgto[0]->infopgto[0]->paisresidext = '169';
$std->idepgto[0]->infopgto[0]->dtescrcont = '2022-03-03';
$std->idepgto[0]->infopgto[0]->observ = 'Observações não sei para o que';

$std->idepgto[0]->infopgto[0]->detded[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detded[0]->indtpdeducao = '1';
$std->idepgto[0]->infopgto[0]->detded[0]->vlrdeducao = 1230.67;
$std->idepgto[0]->infopgto[0]->detded[0]->infoentid = 'S';
$std->idepgto[0]->infopgto[0]->detded[0]->nrinscprevcomp = '12345678901234';
$std->idepgto[0]->infopgto[0]->detded[0]->vlrpatrocfunp = 987.44;

$std->idepgto[0]->infopgto[0]->detded[0]->benefpen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detded[0]->benefpen[0]->cpfdep = '12345678901';
$std->idepgto[0]->infopgto[0]->detded[0]->benefpen[0]->vlrdepen = 874.55;

$std->idepgto[0]->infopgto[0]->rendisento[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->rendisento[0]->tpisencao = '1';
$std->idepgto[0]->infopgto[0]->rendisento[0]->vlrisento = 2345.22;
$std->idepgto[0]->infopgto[0]->rendisento[0]->descrendimento = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->rendisento[0]->dtlaudo = '2021-01-15';

$std->idepgto[0]->infopgto[0]->infoprocret[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocret[0]->tpprocret = '1';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->nrprocret = '22222222';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->codsusp = '12345';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrnretido = 200.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrdepjud = 10.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrCmpanocal = 456.78;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrvmpanoant = 0.00;
$std->idepgto[0]->infopgto[0]->infoprocret[0]->vlrrendsusp = 10.11;

$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0]->indtpdeducao = '5';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0]->vlrdedsusp = 2500.25;

$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0]->benefpen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0]->benefpen[0]->cpfdep = '12345678901';
$std->idepgto[0]->infopgto[0]->infoprocret[0]->dedsusp[0]->benefpen[0]->vlrdepensusp = 2500.25;

$std->idepgto[0]->infopgto[0]->inforra = new stdclass();
$std->idepgto[0]->infopgto[0]->inforra->tpprocrra = '1';
$std->idepgto[0]->infopgto[0]->inforra->nrprocrra = '122344';
$std->idepgto[0]->infopgto[0]->inforra->indorigrec = '1';
$std->idepgto[0]->infopgto[0]->inforra->descrra = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->inforra->qtdmesesrra = 6;
$std->idepgto[0]->infopgto[0]->inforra->cnpjorigrecurso = '12345678901234';

$std->idepgto[0]->infopgto[0]->inforra->despprocjud = new stdclass();
$std->idepgto[0]->infopgto[0]->inforra->despprocjud->vlrdespcustas = 1234.55;
$std->idepgto[0]->infopgto[0]->inforra->despprocjud->vlrdespadvogados = 342.66;

$std->idepgto[0]->infopgto[0]->inforra->despprocjud->ideadv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->inforra->despprocjud->ideadv[0]->tpinscadv = '1';
$std->idepgto[0]->infopgto[0]->inforra->despprocjud->ideadv[0]->nrinscadv = '12345678901234';
$std->idepgto[0]->infopgto[0]->inforra->despprocjud->ideadv[0]->vlradv = 342.66;

$std->idepgto[0]->infopgto[0]->infoprocjud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoprocjud->nrproc = '123456';
$std->idepgto[0]->infopgto[0]->infoprocjud->indorigrec = '1';
$std->idepgto[0]->infopgto[0]->infoprocjud->cnpjorigrecurso = '12345678901234';
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
$std->idepgto[0]->infopgto[0]->infopgtoext->frmtribut = '10';

$std->idepgto[0]->infopgto[0]->infopgtoext->endext = new stdclass();
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->dscLograd = 'logradouro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->nrLograd = '100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->complem = 'SALA 100';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->bairro = 'bairro';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->cidade = 'cidade';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->estado = 'estado';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->codpostal = 'codPostal';
$std->idepgto[0]->infopgto[0]->infopgtoext->endext->telef = '12345678901';


$std->ideopsaude[0] = new stdclass();
$std->ideopsaude[0]->nrinsc = '12345678901234';
$std->ideopsaude[0]->regans = '123456';
$std->ideopsaude[0]->vlrsaude = 1893.22;

$std->ideopsaude[0]->inforeemb[0] = new stdclass();
$std->ideopsaude[0]->inforeemb[0]->tpinsc = '1';
$std->ideopsaude[0]->inforeemb[0]->nrinsc = '12345678901234';
$std->ideopsaude[0]->inforeemb[0]->vlrreemb = 2000.00;
$std->ideopsaude[0]->inforeemb[0]->vlrreembant = 1000.00;

$std->ideopsaude[0]->infodependpl[0] = new stdclass();
$std->ideopsaude[0]->infodependpl[0]->cpfdep = '12345678901';
$std->ideopsaude[0]->infodependpl[0]->vlrsaude = 543.22;

$std->ideopsaude[0]->infodependpl[0]->inforeembdep[0] = new stdclass();
$std->ideopsaude[0]->infodependpl[0]->inforeembdep[0]->tpinsc = '1';
$std->ideopsaude[0]->infodependpl[0]->inforeembdep[0]->nrinsc = '12345678901234';
$std->ideopsaude[0]->infodependpl[0]->inforeembdep[0]->vlrreemb = 222.00;
$std->ideopsaude[0]->infodependpl[0]->inforeembdep[0]->vlrreembant = 10.00;

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
