<?php

namespace DaysOff\Interfaces;

interface HolidayInterface
{
    public function getFixedHolidays(): array;
    public function getMovingHolidays(int $year): array;
}
