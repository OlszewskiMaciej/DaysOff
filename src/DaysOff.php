<?php

namespace DaysOff;

use DateTime;
use DateInterval;
use DaysOff\Interfaces\HolidayInterface;

class DaysOff
{
    private HolidayInterface $holidayProvider;

    public function __construct(HolidayInterface $holidayProvider)
    {
        $this->holidayProvider = $holidayProvider;
    }

    public function isHolidayOrWeekend(DateTime $date): bool
    {
        return $this->isHoliday($date) || $this->isWeekend($date);
    }

    public function isHoliday(DateTime $date): bool
    {
        $formattedDate = $date->format('m-d');
        $year = (int)$date->format('Y');
        $formattedFullDate = $date->format('Y-m-d');

        $fixedHolidays = $this->holidayProvider->getFixedHolidays();
        $movingHolidays = $this->holidayProvider->getMovingHolidays($year);

        return isset($fixedHolidays[$formattedDate]) || isset($movingHolidays[$formattedFullDate]);
    }

    public function isWeekend(DateTime $date): bool
    {
        return $date->format('N') >= 6;
    }

    public function isWorkingDay(DateTime $date): bool
    {
        return !$this->isHolidayOrWeekend($date);
    }

    public function getHolidaysAndWeekendsBetweenDates(DateTime $fromDate, DateTime $toDate): array
    {
        $year = (int)$fromDate->format('Y');
        $movingHolidays = $this->holidayProvider->getMovingHolidays($year);
        $fixedHolidays = $this->holidayProvider->getFixedHolidays();

        $result = [];
        $date = clone $fromDate;
        while ($date <= $toDate) {
            $formattedFullDate = $date->format('Y-m-d');
            if ($date->format('N') >= 6) {
                $result[$formattedFullDate] = $date->format('l');
            }
            if (isset($movingHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $movingHolidays[$formattedFullDate];
            }
            $formattedDate = $date->format('m-d');
            if (isset($fixedHolidays[$formattedDate])) {
                $result[$date->format('Y-m-d')] = $fixedHolidays[$formattedDate];
            }
            $date->add(new DateInterval('P1D'));
        }
        ksort($result);

        return $result;
    }

    public function getHolidaysBetweenDates(DateTime $fromDate, DateTime $toDate): array
    {
        $year = (int)$fromDate->format('Y');
        $movingHolidays = $this->holidayProvider->getMovingHolidays($year);
        $fixedHolidays = $this->holidayProvider->getFixedHolidays();

        $result = [];
        $date = clone $fromDate;
        while ($date <= $toDate) {
            $formattedFullDate = $date->format('Y-m-d');
            if (isset($movingHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $movingHolidays[$formattedFullDate];
            }
            $formattedDate = $date->format('m-d');
            if (isset($fixedHolidays[$formattedDate])) {
                $result[$date->format('Y-m-d')] = $fixedHolidays[$formattedDate];
            }
            $date->add(new DateInterval('P1D'));
        }
        ksort($result);

        return $result;
    }

    public function getWeekendsBetweenDates(DateTime $fromDate, DateTime $toDate): array
    {
        $result = [];
        $date = clone $fromDate;
        while ($date <= $toDate) {
            if ($date->format('N') >= 6) {
                $result[$date->format('Y-m-d')] = $date->format('l');
            }
            $date->add(new DateInterval('P1D'));
        }
        ksort($result);

        return $result;
    }

    public function getWorkingDaysBetweenDates(DateTime $fromDate, DateTime $toDate): array
    {
        $holidaysAndWeekends = $this->getHolidaysAndWeekendsBetweenDates($fromDate, $toDate);

        $result = [];
        $date = clone $fromDate;
        while ($date <= $toDate) {
            $formattedDate = $date->format('Y-m-d');
            if (!isset($holidaysAndWeekends[$formattedDate])) {
                $result[$formattedDate] = $date->format('l');
            }
            $date->add(new DateInterval('P1D'));
        }
        ksort($result);

        return $result;
    }

    public function getHolidaysAndWeekendsFromDatePlusDays(DateTime $date, int $daysToAdd): array
    {
        $newDate = clone $date;
        $newDate->add(new DateInterval("P{$daysToAdd}D"));
        return $this->getHolidaysAndWeekendsBetweenDates($date, $newDate);
    }

    public function getHolidaysFromDatePlusDays(DateTime $date, int $daysToAdd): array
    {
        $newDate = clone $date;
        $newDate->add(new DateInterval("P{$daysToAdd}D"));
        return $this->getHolidaysBetweenDates($date, $newDate);
    }

    public function getWeekendsFromDatePlusDays(DateTime $date, int $daysToAdd): array
    {
        $newDate = clone $date;
        $newDate->add(new DateInterval("P{$daysToAdd}D"));
        return $this->getWeekendsBetweenDates($date, $newDate);
    }

    public function getWorkingDaysFromDatePlusDays(DateTime $date, int $daysToAdd): array
    {
        $newDate = clone $date;
        $newDate->add(new DateInterval("P{$daysToAdd}D"));
        return $this->getWorkingDaysBetweenDates($date, $newDate);
    }

    public function countHolidaysAndWeekendsBetweenDates(DateTime $fromDate, DateTime $toDate): int
    {
        return count($this->getHolidaysAndWeekendsBetweenDates($fromDate, $toDate));
    }

    public function countHolidaysBetweenDates(DateTime $fromDate, DateTime $toDate): int
    {
        return count($this->getHolidaysBetweenDates($fromDate, $toDate));
    }

    public function countWeekendsBetweenDates(DateTime $fromDate, DateTime $toDate): int
    {
        return count($this->getWeekendsBetweenDates($fromDate, $toDate));
    }

    public function countWorkingDaysBetweenDates(DateTime $fromDate, DateTime $toDate): int
    {
        return count($this->getWorkingDaysBetweenDates($fromDate, $toDate));
    }

    public function countHolidaysAndWeekendsFromDatePlusDays(DateTime $date, int $daysToAdd): int
    {
        return count($this->getHolidaysAndWeekendsFromDatePlusDays($date, $daysToAdd));
    }

    public function countHolidaysFromDatePlusDays(DateTime $date, int $daysToAdd): int
    {
        return count($this->getHolidaysFromDatePlusDays($date, $daysToAdd));
    }

    public function countWeekendsFromDatePlusDays(DateTime $date, int $daysToAdd): int
    {
        return count($this->getWeekendsFromDatePlusDays($date, $daysToAdd));
    }

    public function countWorkingDaysFromDatePlusDays(DateTime $date, int $daysToAdd): int
    {
        return count($this->getWorkingDaysFromDatePlusDays($date, $daysToAdd));
    }
}
