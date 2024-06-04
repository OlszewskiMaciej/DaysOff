<?php

namespace DaysOff;

use DateTime;
use DateInterval;
use DaysOff\Interfaces\HolidayInterface;

class DaysOff
{
    private HolidayInterface $holidayProvider;
    private array $additionalHolidays = [];
    private array $removedHolidays = [];

    public function __construct(HolidayInterface $holidayProvider)
    {
        $this->holidayProvider = $holidayProvider;
    }

    public function addHoliday(DateTime $date, string $name, bool $fixed = false): void
    {
        $key = $fixed ? $date->format('m-d') : $date->format('Y-m-d');
        $this->additionalHolidays[$key] = $name;
    }

    public function removeHoliday(DateTime $date, bool $fixed = false): void
    {
        $key = $fixed ? $date->format('m-d') : $date->format('Y-m-d');
        $this->removedHolidays[$key] = true;
    }

    public function isHolidayOrWeekend(DateTime $date): bool
    {
        return $this->isHoliday($date) || $this->isWeekend($date);
    }

    public function isHoliday(DateTime $date): bool
    {
        $year = (int)$date->format('Y');
        $formattedDate = $date->format('m-d');
        $formattedFullDate = $date->format('Y-m-d');

        if (isset($this->removedHolidays[$formattedDate]) || isset($this->removedHolidays[$formattedFullDate])) {
            return false;
        }

        if (isset($this->additionalHolidays[$formattedDate]) || isset($this->additionalHolidays[$formattedFullDate])) {
            return true;
        }

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

    public function getHolidayName(DateTime $date): ?string
    {
        $formattedDate = $date->format('m-d');
        $formattedFullDate = $date->format('Y-m-d');

        if (isset($this->removedHolidays[$formattedDate]) || isset($this->removedHolidays[$formattedFullDate])) {
            return null;
        }

        if (isset($this->additionalHolidays[$formattedDate])) {
            return $this->additionalHolidays[$formattedDate];
        }

        if (isset($this->additionalHolidays[$formattedFullDate])) {
            return $this->additionalHolidays[$formattedFullDate];
        }

        $year = (int)$date->format('Y');
        $movingHolidays = $this->holidayProvider->getMovingHolidays($year);
        $fixedHolidays = $this->holidayProvider->getFixedHolidays();

        if (isset($movingHolidays[$formattedFullDate])) {
            return $movingHolidays[$formattedFullDate];
        }

        if (isset($fixedHolidays[$formattedDate])) {
            return $fixedHolidays[$formattedDate];
        }

        return null;
    }

    public function getCalendarDaysFromDatePlusWorkingDays(DateTime $date, int $daysToAdd): int
    {
        $date = clone $date;
        $calendarDays = 0;
        $workingDaysAdded = 0;

        while ($workingDaysAdded < $daysToAdd) {
            $calendarDays++;
            $date->add(new DateInterval('P1D'));
            if (!$this->isHolidayOrWeekend($date)) {
                $workingDaysAdded++;
            }
        }

        return $calendarDays;
    }

    public function getDateFromDatePlusWorkingDays(DateTime $date, int $daysToAdd): DateTime
    {
        $date = clone $date;
        $workingDaysAdded = 0;

        while ($workingDaysAdded < $daysToAdd) {
            $date->add(new DateInterval('P1D'));
            if (!$this->isHolidayOrWeekend($date)) {
                $workingDaysAdded++;
            }
        }

        return $date;
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
            $formattedDate = $date->format('m-d');

            if ($date->format('N') >= 6) {
                $result[$formattedFullDate] = $date->format('l');
            }

            if (isset($this->removedHolidays[$formattedDate]) || isset($this->removedHolidays[$formattedFullDate])) {
                $date->add(new DateInterval('P1D'));
                continue;
            }

            if (isset($this->additionalHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $this->additionalHolidays[$formattedFullDate];
            } elseif (isset($this->additionalHolidays[$formattedDate])) {
                $result[$formattedFullDate] = $this->additionalHolidays[$formattedDate];
            } elseif (isset($movingHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $movingHolidays[$formattedFullDate];
            } elseif (isset($fixedHolidays[$formattedDate])) {
                $result[$formattedFullDate] = $fixedHolidays[$formattedDate];
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
            $formattedDate = $date->format('m-d');

            if (isset($this->removedHolidays[$formattedDate]) || isset($this->removedHolidays[$formattedFullDate])) {
                $date->add(new DateInterval('P1D'));
                continue;
            }

            if (isset($this->additionalHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $this->additionalHolidays[$formattedFullDate];
            } elseif (isset($this->additionalHolidays[$formattedDate])) {
                $result[$formattedFullDate] = $this->additionalHolidays[$formattedDate];
            } elseif (isset($movingHolidays[$formattedFullDate])) {
                $result[$formattedFullDate] = $movingHolidays[$formattedFullDate];
            } elseif (isset($fixedHolidays[$formattedDate])) {
                $result[$formattedFullDate] = $fixedHolidays[$formattedDate];
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
