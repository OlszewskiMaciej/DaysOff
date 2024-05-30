<?php

namespace DaysOff\Providers;

use DateTime;
use DaysOff\Interfaces\HolidayInterface;

class PolishHolidayProvider implements HolidayInterface
{
    private const FIXED_HOLIDAYS = [
        '01-01' => 'New Year’s Day', // Nowy rok
        '01-06' => 'Epiphany', // Święto Trzech Króli
        '05-01' => 'Labor Day', // Święto pracy
        '05-03' => 'Constitution Day', // Święto kontytucji
        '08-15' => 'Assumption of the Blessed Virgin Mary', // Wniebowzięcie Najświętszej Maryi Panny
        '11-01' => 'All Saints’ Day', // Wszystkich Świętych
        '11-11' => 'Independence day', // Dzień Niepodległości
        '12-25' => 'Christmas Day', // Boże Narodzenie
        '12-26' => 'Boxing Day', // Drugi dzień świąt Bożego Narodzenia
    ];

    public function getFixedHolidays(): array
    {
        return self::FIXED_HOLIDAYS;
    }

    public function getMovingHolidays(int $year): array
    {
        if ($year <= 1900 || $year >= 2499) {
            throw new \Exception('Unsupported year range for Easter calculation');
        }

        $easterDay = $this->getEasterDay($year);
        $easterMonday = new DateTime($easterDay->format('Y-m-d') . ' +1 day');
        $pentecost = new DateTime($easterDay->format('Y-m-d') . ' +49 days');
        $corpusChristi = new DateTime($easterDay->format('Y-m-d') . ' +60 days');

        return [
            $easterDay->format('Y-m-d') => 'Easter Sunday', // Wielkanoc
            $easterMonday->format('Y-m-d') => 'Easter Monday', // Poniedziałek Wielkanocny
            $pentecost->format('Y-m-d') => 'Pentecost Sunday', // Zielone świątki
            $corpusChristi->format('Y-m-d') => 'Corpus Christi', // Boże ciało
        ];
    }

    private static function getEasterDay(int $year): DateTime
    {
        if ($year >= 1900 && $year <= 2099) {
            $factorA = 24;
            $factorB = 5;
        } elseif ($year >= 2100 && $year <= 2199) {
            $factorA = 24;
            $factorB = 6;
        } elseif ($year >= 2200 && $year <= 2299) {
            $factorA = 25;
            $factorB = 0;
        } elseif ($year >= 2300 && $year <= 2399) {
            $factorA = 26;
            $factorB = 1;
        } elseif ($year >= 2400 && $year <= 2499) {
            $factorA = 25;
            $factorB = 1;
        } else {
            throw new \Exception('Unsupported year range for Easter calculation');
        }

        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $d = ($a * 19 + $factorA) % 30;
        $e = (2 * $b + 4 * $c + 6 * $d + $factorB) % 7;

        if (($d === 29 || $d === 28) && $e === 6) {
            $d -= 7;
        }
        return new DateTime('22-03-' . $year . ' + ' . ($d + $e) . ' days');
    }
}
