<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtPgtosDivs';
$version = '1_02_00';

$jsonSchema = '{
    "title": "evtPgtosDivs",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": true,
            "type": "integer",
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
            "maxLength": 52
        },
        "perapur": {
            "required": true,
            "type": "string",
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
        },
        "codpgto": {
            "required": true,
            "type": "string",
            "maxLength": 4,
            "pattern": "^[0-9]"
        },
        "tpinscbenef": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 2
        },
        "nrinscbenef": {
            "required": false,
            "type": ["string","null"],
            "maxLength": 14,
            "pattern": "^[0-9]"
        },
        "nmrazaobenef": {
            "required": true,
            "type": "string",
            "maxLength": 150
        },
        "inforesidext": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "paisresid": {
                    "required": true,
                    "type": "string",
                    "maxLength": 3
                },
                "dsclograd": {
                    "required": true,
                    "type": "string",
                    "maxLength": 80
                },
                "nrlograd": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 10
                },
                "complem": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 30
                },
                "bairro": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 60
                },
                "cidade": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 30
                },
                "codpostal": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 12
                },
                "indnif": {
                    "required": true,
                    "type": "integer",
                    "minimum": 1,
                    "maximum": 3
                },
                "nifbenef": {
                    "required": false,
                    "type": ["string","null"],
                    "maxLength": 20
                },
                "relfontepagad": {
                    "required": false,
                    "type": ["string","null"],
                    "minLength": 3,
                    "maxLength": 3,
                    "pattern": "^[0-9]"
                }
            }
        },
        "infomolestia": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "dtlaudo": {
                    "required": true,
                    "type": "string",
                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                }
            }
        },
        "ideestab": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "maxItems": 500,
            "items": {
                "type": "object",
                "properties": {
                    "tpinsc": {
                        "required": true,
                        "type": "integer",
                        "minimum": 1,
                        "maximum": 4
                    },
                    "nrinsc": {
                        "required": true,
                        "type": "string",
                        "maxLength": 14,
                        "pattern": "^[0-9]"
                    },
                    "pgtopf": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 500,
                        "items": {
                            "type": "object",
                            "properties": {
                                "dtpgto": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                                },
                                "indsuspexig": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 1,
                                    "pattern": "S|N"
                                },
                                "inddecterceiro": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 1,
                                    "pattern": "S|N"
                                },
                                "vlrrendtributavel": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrirrf": {
                                    "required": true,
                                    "type": "number"
                                },
                                "detdeduca": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 6,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "indtpdeducao": {
                                                "required": true,
                                                "type": "integer",
                                                "minimum": 1,
                                                "maximum": 6
                                            },
                                            "vlrdeducao": {
                                                "required": true,
                                                "type": "number"
                                            }
                                        }
                                    }    
                                },
                                "rendisento": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 500,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "tpisencao": {
                                                "required": true,
                                                "type": "integer",
                                                "minimum": 1,
                                                "maximum": 11
                                            },
                                            "vlrisento": {
                                                "required": true,
                                                "type": "number"
                                            },
                                            "descrendimento": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 100
                                            }
                                        }
                                    }    
                                },
                                "detcompet": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 500,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "indperreferencia": {
                                                "required": true,
                                                "type": "integer",
                                                "minimum": 1,
                                                "maximum": 2
                                            },
                                            "perrefpagto": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$"
                                            },
                                            "vlrrendtributavel": {
                                                "required": true,
                                                "type": "number"
                                            }
                                        }
                                    }    
                                },
                                "inforra": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 500,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "tpprocrra": {
                                                "required": false,
                                                "type": ["integer","null"],
                                                "minimum": 1,
                                                "maximum": 2
                                            },
                                            "nrprocrra": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 21
                                            },
                                            "codsusp": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 14,
                                                "pattern": "^[0-9]"
                                            },
                                            "natrra": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 50
                                            },
                                            "qtdmesesrra": {
                                                "required": false,
                                                "type": ["integer","null"],
                                                "minimum": 1
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
                                                    "ideadvogado": {
                                                        "required": false,
                                                        "type": ["array","null"],
                                                        "minItems": 0,
                                                        "maxItems": 500,
                                                        "items": {
                                                            "type": "object",
                                                            "properties": {
                                                                "tpinscadvogado": {
                                                                    "required": true,
                                                                    "type": "integer",
                                                                    "minimum": 1,
                                                                    "maximum": 2
                                                                },
                                                                "nrinscadvogado": {
                                                                    "required": true,
                                                                    "type": "string",
                                                                    "maxLength": 14,
                                                                    "pattern": "^[0-9]"
                                                                },
                                                                "vlradvogado": {
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
                                },
                                "infoprocjud": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 500,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "nrprocjud": {
                                                "required": true,
                                                "type": "string",
                                                "maxLength": 21
                                            },
                                            "codsusp": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 14,
                                                "pattern": "^[0-9]"
                                            },
                                            "indorigemrecursos": {
                                                "required": true,
                                                "type": "integer",
                                                "minimum": 1,
                                                "maximum": 2
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
                                                    "ideadvogado": {
                                                        "required": false,
                                                        "type": ["array","null"],
                                                        "minItems": 0,
                                                        "maxItems": 500,
                                                        "items": {
                                                            "type": "object",
                                                            "properties": {
                                                                "tpinscadvogado": {
                                                                    "required": true,
                                                                    "type": "integer",
                                                                    "minimum": 1,
                                                                    "maximum": 2
                                                                },
                                                                "nrinscadvogado": {
                                                                    "required": true,
                                                                    "type": "string",
                                                                    "maxLength": 14,
                                                                    "pattern": "^[0-9]"
                                                                },
                                                                "vlradvogado": {
                                                                    "required": true,
                                                                    "type": "number"
                                                                }
                                                            }
                                                        }    
                                                    }
                                                }
                                            },
                                            "origemrecursos": {
                                                "required": false,
                                                "type": ["object","null"],
                                                "properties": {
                                                    "cnpjorigemrecursos": {
                                                        "required": true,
                                                        "type": "string",
                                                        "maxLength": 14,
                                                        "pattern": "^[0-9]"
                                                    }
                                                }
                                            }
                                        }
                                    }    
                                },
                                "depjudicial": {
                                    "required": false,
                                    "type": ["object","null"],
                                    "properties": {
                                        "vlrdepjudicial": {
                                            "required": true,
                                            "type": "number"
                                        }
                                    }
                                }
                            }
                        }    
                    },
                    "pgtopj": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "maxItems": 500,
                        "items": {
                            "type": "object",
                            "properties": {
                                "dtpagto": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                                },
                                "vlrrendtributavel": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrret": {
                                    "required": true,
                                    "type": "number"
                                },
                                "infoprocjud": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "maxItems": 500,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "nrprocjud": {
                                                "required": true,
                                                "type": "string",
                                                "maxLength": 21
                                            },
                                            "codsusp": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "maxLength": 14,
                                                "pattern": "^[0-9]"
                                            },
                                            "indorigemrecursos": {
                                                "required": true,
                                                "type": "integer",
                                                "minimum": 1,
                                                "maximum": 2
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
                                                    "ideadvogado": {
                                                        "required": false,
                                                        "type": ["array","null"],
                                                        "minItems": 0,
                                                        "maxItems": 500,
                                                        "items": {
                                                            "type": "object",
                                                            "properties": {
                                                                "tpinscadvogado": {
                                                                    "required": true,
                                                                    "type": "integer",
                                                                    "minimum": 1,
                                                                    "maximum": 2
                                                                },
                                                                "nrinscadvogado": {
                                                                    "required": true,
                                                                    "type": "string",
                                                                    "maxLength": 14,
                                                                    "pattern": "^[0-9]"
                                                                },
                                                                "vlradvogado": {
                                                                    "required": true,
                                                                    "type": "number"
                                                                }
                                                            }
                                                        }    
                                                    }
                                                }
                                            },
                                            "origemrecursos": {
                                                "required": false,
                                                "type": ["object","null"],
                                                "properties": {
                                                    "cnpjorigemrecursos": {
                                                        "required": true,
                                                        "type": "string",
                                                        "maxLength": 14,
                                                        "pattern": "^[0-9]"
                                                    }
                                                }
                                            }
                                        }
                                    }    
                                }
                            }
                        }    
                    },
                    "pgtoresidext": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "dtpagto": {
                                "required": true,
                                "type": "string",
                                "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                            },
                            "tprendimento": {
                                "required": true,
                                "type": "integer",
                                "minimum": 100,
                                "maximum": 300
                            },
                            "formatributacao": {
                                "required": true,
                                "type": "integer",
                                "minimum": 10,
                                "maximum": 50
                            },
                            "vlrpgto": {
                                "required": true,
                                "type": "number"
                            },
                            "vlrret": {
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
$std->sequencial = 1;
$std->indretif = 1;
$std->nrrecibo = '737373737373737';
$std->perapur = '2017-11';

$std->codpgto = '0916';
$std->tpinscbenef = 2;
$std->nrinscbenef = '12345678901';
$std->nmrazaobenef = 'Fulano de Tal';

$std->inforesidext = new \stdClass();
$std->inforesidext->paisresid = '123';
$std->inforesidext->dsclograd = 'Av. 5';
$std->inforesidext->nrlograd = '342L';
$std->inforesidext->complem = 'Apto 32';
$std->inforesidext->bairro = 'Soho';
$std->inforesidext->cidade = 'New Jersey';
$std->inforesidext->codpostal = '1234567890';

$std->inforesidext->indnif = 1;
$std->inforesidext->nifbenef = '123456789';
$std->inforesidext->relfontepagad = '500';

$std->infomolestia = new \stdClass();
$std->infomolestia->dtlaudo = '2016-05-22';

$std->ideestab[0] = new \stdClass();
$std->ideestab[0]->tpinsc = 1;
$std->ideestab[0]->nrinsc = '12345678901234';

$std->ideestab[0]->pgtopf[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->dtpgto = '2017-10-10';
$std->ideestab[0]->pgtopf[0]->indsuspexig = 'N';
$std->ideestab[0]->pgtopf[0]->inddecterceiro = 'N';
$std->ideestab[0]->pgtopf[0]->vlrrendtributavel = 2000;
$std->ideestab[0]->pgtopf[0]->vlrirrf = 380;

$std->ideestab[0]->pgtopf[0]->detdeduca[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->detdeduca[0]->indtpdeducao = 4;
$std->ideestab[0]->pgtopf[0]->detdeduca[0]->vlrdeducao = 100;

$std->ideestab[0]->pgtopf[0]->rendisento[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->rendisento[0]->tpisencao = 1;
$std->ideestab[0]->pgtopf[0]->rendisento[0]->vlrisento = 30000;
$std->ideestab[0]->pgtopf[0]->rendisento[0]->descrendimento = 'chega de impostos';

$std->ideestab[0]->pgtopf[0]->detcompet[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->detcompet[0]->indperreferencia = 1;
$std->ideestab[0]->pgtopf[0]->detcompet[0]->perrefpagto = '2017-10';
$std->ideestab[0]->pgtopf[0]->detcompet[0]->vlrrendtributavel = 20;

$std->ideestab[0]->pgtopf[0]->inforra[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->inforra[0]->tpprocrra = 1;
$std->ideestab[0]->pgtopf[0]->inforra[0]->nrprocrra = 'abcdefg';
$std->ideestab[0]->pgtopf[0]->inforra[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopf[0]->inforra[0]->natrra = 'sei la';
$std->ideestab[0]->pgtopf[0]->inforra[0]->qtdmesesrra = 49;

$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud = new \stdClass();  
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->vlrdespcustas = 10;
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->vlrdespadvogados = 1.45;

$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopf[0]->inforra[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->nrprocjud = 'sei la';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->indorigemrecursos = 1;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->vlrdespcustas = 200;
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->vlrdespadvogados = 2.90;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->origemrecursos = new \stdClass();
$std->ideestab[0]->pgtopf[0]->infoprocjud[0]->origemrecursos->cnpjorigemrecursos = '12345678901234';

$std->ideestab[0]->pgtopf[0]->depjudicial = new \stdClass();
$std->ideestab[0]->pgtopf[0]->depjudicial->vlrdepjudicial = 23.97;

$std->ideestab[0]->pgtopj[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->dtpagto = '2017-01-10';
$std->ideestab[0]->pgtopj[0]->vlrrendtributavel = 2000;
$std->ideestab[0]->pgtopj[0]->vlrret = 30.67;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->nrprocjud = 'sei la';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->codsusp = '12345678901234';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->indorigemrecursos = 1;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->vlrdespcustas = 200;
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->vlrdespadvogados = 2.90;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0] = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->tpinscadvogado = 1;
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->nrinscadvogado = '12345678901234';
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->despprocjud->ideadvogado[0]->vlradvogado = 1.45;

$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->origemrecursos = new \stdClass();
$std->ideestab[0]->pgtopj[0]->infoprocjud[0]->origemrecursos->cnpjorigemrecursos = '12345678901234';

$std->ideestab[0]->pgtoresidext = new \stdClass();
$std->ideestab[0]->pgtoresidext->dtpagto = '2017-02-22';
$std->ideestab[0]->pgtoresidext->tprendimento = 140;
$std->ideestab[0]->pgtoresidext->formatributacao = 12;
$std->ideestab[0]->pgtoresidext->vlrpgto = 2000;
$std->ideestab[0]->pgtoresidext->vlrret = 22.95;
        

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
