<?php

namespace NFePHP\EFDReinf;

/**
 * Class efdReinf Event constructor
 *
 * @category  Library
 * @package   NFePHP\EFDReinf
 * @copyright NFePHP Copyright (c) 2017 - 2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efdreinf for the canonical source repository
 */

use NFePHP\EFDReinf\Exception\EventsException;

class Event
{
    /**
     * Relationship between the name of the event and its respective class
     * @var array
     */
    private static $available = [
        'evtinfocontri' => Factories\EvtInfoContri::class,
        'evttablig' => Factories\EvtTabLig::class,
        'evttabprocesso' => Factories\EvtTabProcesso::class,
        'evtservtom' => Factories\EvtServTom::class,
        'evtservprest' => Factories\EvtServPrest::class,
        'evtassocdesprec' => Factories\EvtAssocDespRec::class,
        'evtassocdesprep' => Factories\EvtAssocDespRep::class,
        'evtcomprod' => Factories\EvtComProd::class,
        'evtaqprod' => Factories\EvtAqProd::class,
        'evtcprb' => Factories\EvtCPRB::class,
        'evtpgtosdivs' => Factories\EvtPgtosDivs::class,
        'evtreabreevper' => Factories\EvtReabreEvPer::class,
        'evtfechaevper' => Factories\EvtFechaEvPer::class,
        'evtespdesportivo' => Factories\EvtEspDesportivo::class,
        'evtexclusao' => Factories\EvtExclusao::class,
        'evtretpf' => Factories\EvtRetPF::class,
        'evtretpj' => Factories\EvtRetPJ::class,
        'evtbenefnid' => Factories\EvtBenefNId::class,
        'evtretrec' => Factories\EvtRetRec::class,
        'evtfech4000' => Factories\EvtFech4000::class,
        'evtretcons' => Factories\EvtRetCons::class,
    ];

    /**
     * Relationship between the code of the event and its respective name
     * @var array
     */
    private static $aliases = [
        'r1000' => 'evtinfocontri',
        'r1050' => 'evttablig',
        'r1070' => 'evttabprocesso',
        'r2010' => 'evtservtom',
        'r2020' => 'evtservprest',
        'r2030' => 'evtassocdesprec',
        'r2040' => 'evtassocdesprep',
        'r2050' => 'evtcomprod',
        'r2055' => 'evtaqprod',
        'r2060' => 'evtcprb',
        'r2070' => 'evtpgtosdivs',
        'r2098' => 'evtreabreevper',
        'r2099' => 'evtfechaevper',
        'r3010' => 'evtespdesportivo',
        'r4010' => 'evtretpf',
        'r4020' => 'evtretpj',
        'r4040' => 'evtbenefnid',
        'r4080' => 'evtretrec',
        'r4099' => 'evtfech4000',
        'r9000' => 'evtexclusao',
        'r9001' => 'evttotal',
        'r9011' => 'evttotalcontrib',
        'r9012' => 'evtretCons',
    ];

    /**
     * Call classes to build XML EFDReinf Event
     * @param string $name
     * @param array $arguments [config, std, certificate, $date]
     * @return object
     * @throws NFePHP\EFDReinf\Exception\EventsException
     */
    public static function __callStatic($name, $arguments)
    {
        $name = str_replace('-', '', strtolower($name));
        $realname = $name;
        if (substr($name, 0, 1) == 'r') {
            if (!array_key_exists($name, self::$aliases)) {
                //este evento não foi localizado
                throw EventsException::wrongArgument(1000, $name);
            }
            $realname = self::$aliases[$name];
        }
        if (!array_key_exists($realname, self::$available)) {
            //este evento não foi localizado
            throw EventsException::wrongArgument(1000, $name);
        }
        $className = self::$available[$realname];
        if (empty($arguments[0])) {
            throw EventsException::wrongArgument(1001);
        }
        if (empty($arguments[1])) {
            throw EventsException::wrongArgument(1002, $name);
        }
        if (count($arguments) > 2 && count($arguments) < 4) {
            return new $className($arguments[0], $arguments[1], $arguments[2]);
        }
        if (count($arguments) > 3) {
            return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
        }
        return new $className($arguments[0], $arguments[1]);
    }
}
