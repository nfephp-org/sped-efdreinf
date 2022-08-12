<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evt4010PagtoBeneficiarioPF';
$version = '2_01_01';

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
            "pattern": "^([0-9]{1,18}[-][0-9]{2}[-][0-9]{4}[-][0-9]{6}[-][0-9]{1,18})$"
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
                        "pattern": "^(1|2|3|6|8|9|10|11|12|99)$"
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
                "pattern": "^[1-2]{1}[0-9]{4}$"
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
                                        "pattern": "^([1-9]{1}|99)$"
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
                                                    "pattern": "^(1|2|3|4|5|7)$"
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
                                            "minLength": 8,
                                            "maxLength": 15
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
$std->nrrecibo = '1-12-1234-123456-123456576';
$std->perapur = '2017-12';

$std->natjur = '1234'; //infoComplContri/natJur
$std->tpinscestab = '1';
$std->nrinscestab = '12345678901234';

$std->idebenef = new stdClass();
$std->idebenef->cpfbenef = '12345678901';
$std->idebenef->nmbenef = 'Fulano de Tal';

$std->idedep[0] = new stdclass();
$std->idedep[0]->cpfdep = '12345678901';
$std->idedep[0]->reldep = '1';
$std->idedep[0]->descdep = 'esposa';

$std->idepgto[0] = new stdclass();
$std->idepgto[0]->natrend = '10001';
$std->idepgto[0]->observ = 'bla bla bla';

$std->idepgto[0]->infopgto[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->dtFG = '2022-07-15';
$std->idepgto[0]->infopgto[0]->compFP = '2022';
$std->idepgto[0]->infopgto[0]->indDecTerc = 'S';
$std->idepgto[0]->infopgto[0]->vlrRendBruto = 7834.45;
$std->idepgto[0]->infopgto[0]->vlrRendTrib = 4324.56;
$std->idepgto[0]->infopgto[0]->vlrIR = 400.33;
$std->idepgto[0]->infopgto[0]->indRRA = 'S';
$std->idepgto[0]->infopgto[0]->indFciScp = '1';
$std->idepgto[0]->infopgto[0]->nrInscFciScp = '12345678901234';
$std->idepgto[0]->infopgto[0]->percSCP = 20;
$std->idepgto[0]->infopgto[0]->indJud = 'N';
$std->idepgto[0]->infopgto[0]->paisResidExt = '169';

$std->idepgto[0]->infopgto[0]->detDed[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detDed[0]->indTpDeducao = '1';
$std->idepgto[0]->infopgto[0]->detDed[0]->vlrDeducao = 1230.67;
$std->idepgto[0]->infopgto[0]->detDed[0]->infoEntid = 'S';
$std->idepgto[0]->infopgto[0]->detDed[0]->nrInscPrevComp = '12345678901234';
$std->idepgto[0]->infopgto[0]->detDed[0]->vlrPatrocFunp = 987.44;

$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0]->cpfDep = '12345678901';
$std->idepgto[0]->infopgto[0]->detDed[0]->benefPen[0]->vlrDepen = 874.55;

$std->idepgto[0]->infopgto[0]->rendIsento[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->rendIsento[0]->tpIsencao = '1';
$std->idepgto[0]->infopgto[0]->rendIsento[0]->vlrIsento = 2345.22;
$std->idepgto[0]->infopgto[0]->rendIsento[0]->descRendimento = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->rendIsento[0]->dtLaudo = '2021-01-15';

$std->idepgto[0]->infopgto[0]->infoProcRet[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->tpProcRet = '1';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->nrProcRet = '22222222';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->codSusp = '12345';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrNRetido = 200.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrDepJud = 10.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrCmpAnoCal = 456.78;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrCmpAnoAnt = 0.00;
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->vlrRendSusp = 10.11;

$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->indTpDeducao = '5';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->vlrDedSusp = 2500.25;

$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0]->cpfDep = '12345678901';
$std->idepgto[0]->infopgto[0]->infoProcRet[0]->dedSusp[0]->benefPen[0]->vlrDepenSusp = 2500.25;

$std->idepgto[0]->infopgto[0]->infoRRA = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->tpProcRRA = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->nrProcRRA = '122344';
$std->idepgto[0]->infopgto[0]->infoRRA->indOrigRec = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->descRRA = 'bla bla bla';
$std->idepgto[0]->infopgto[0]->infoRRA->qtdMesesRRA = 6;
$std->idepgto[0]->infopgto[0]->infoRRA->cnpjOrigRecurso = '12345678901234';

$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->vlrDespCustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->vlrDespAdvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->tpInscAdv = '1';
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->nrInscAdv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoRRA->despProcJud->ideAdv[0]->vlrAdv = 342.66;

$std->idepgto[0]->infopgto[0]->infoProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->nrProc = '123456';
$std->idepgto[0]->infopgto[0]->infoProcJud->indOrigRec = '1';
$std->idepgto[0]->infopgto[0]->infoProcJud->cnpjOrigRecurso = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoProcJud->desc = 'blça bla bla';

$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->vlrDespCustas = 1234.55;
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->vlrDespAdvogados = 342.66;

$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0] = new stdclass();
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->tpInscAdv = '1';
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->nrInscAdv = '12345678901234';
$std->idepgto[0]->infopgto[0]->infoProcJud->despProcJud->ideAdv[0]->vlrAdv = 342.66;


$std->idepgto[0]->infopgto[0]->infoPgtoExt = new stdclass();
$std->idepgto[0]->infopgto[0]->infoPgtoExt->indNIF = '1';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->nifBenef = '123456';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->frmTribut = '10';

$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt = new stdclass();
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->dscLograd = 'logradouro';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->nrLograd = '100';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->complem = 'SALA 100';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->bairro = 'bairro';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->cidade = 'cidade';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->estado = 'estado';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->codPostal = 'codPostal';
$std->idepgto[0]->infopgto[0]->infoPgtoExt->endExt->telef = '12345678901';


$std->ideOpSaude[0] = new stdclass();
$std->ideOpSaude[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->regANS = '123456';
$std->ideOpSaude[0]->vlrSaude = 1893.22;

$std->ideOpSaude[0]->infoReemb[0] = new stdclass();
$std->ideOpSaude[0]->infoReemb[0]->tpInsc = '1';
$std->ideOpSaude[0]->infoReemb[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->infoReemb[0]->vlrReemb = 2000.00;
$std->ideOpSaude[0]->infoReemb[0]->vlrReembAnt = 1000.00;

$std->ideOpSaude[0]->infoDependPl[0] = new stdclass();
$std->ideOpSaude[0]->infoDependPl[0]->cpfDep = '12345678901';
$std->ideOpSaude[0]->infoDependPl[0]->vlrSaude = 543.22;

$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0] = new stdclass();
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->tpInsc = '1';
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->nrInsc = '12345678901234';
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->vlrReemb = 222.00;
$std->ideOpSaude[0]->infoDependPl[0]->infoReembDep[0]->vlrReembAnt = 10.00;




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
