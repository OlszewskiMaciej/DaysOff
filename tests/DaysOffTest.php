<?php

use PHPUnit\Framework\TestCase;
use DaysOff\DaysOff;
use DaysOff\Providers\PolishHolidayProvider;

class DaysOffTest extends TestCase
{
    private $daysOff;

    protected function setUp(): void
    {
        $holidayProvider = new PolishHolidayProvider();
        $this->daysOff = new DaysOff($holidayProvider);
    }

    public function testAddHoliday()
    {
        $date = new DateTime('2024-12-31');
        $this->daysOff->addHoliday($date, 'New Year’s Eve');
        $this->assertTrue($this->daysOff->isHoliday($date));
    }

    public function testRemoveHoliday()
    {
        $date = new DateTime('2024-12-25');
        $this->daysOff->removeHoliday($date);
        $this->assertFalse($this->daysOff->isHoliday($date));
    }

    public function testIsHolidayOrWeekend()
    {
        $date = new DateTime('2024-12-25');
        $this->assertTrue($this->daysOff->isHolidayOrWeekend($date));
        $date = new DateTime('2024-06-09');
        $this->assertTrue($this->daysOff->isHolidayOrWeekend($date));
        $date = new DateTime('2024-06-10');
        $this->assertFalse($this->daysOff->isHolidayOrWeekend($date));
    }

    public function testIsHoliday()
    {
        $date = new DateTime('2024-12-25');
        $this->assertTrue($this->daysOff->isHoliday($date));
        $date = new DateTime('2024-06-10');
        $this->assertFalse($this->daysOff->isHoliday($date));
    }

    public function testIsWeekend()
    {
        $date = new DateTime('2024-06-08');
        $this->assertTrue($this->daysOff->isWeekend($date));
        $date = new DateTime('2024-06-10');
        $this->assertFalse($this->daysOff->isWeekend($date));
    }

    public function testIsWorkingDay()
    {
        $date = new DateTime('2024-06-10');
        $this->assertTrue($this->daysOff->isWorkingDay($date));
        $date = new DateTime('2024-12-25');
        $this->assertFalse($this->daysOff->isWorkingDay($date));
    }

    public function testGetHolidayName()
    {
        $date = new DateTime('2024-12-25');
        $this->assertEquals('Christmas Day', $this->daysOff->getHolidayName($date));
        $date = new DateTime('2024-06-10');
        $this->assertNull($this->daysOff->getHolidayName($date));
    }

    public function testGetCalendarDaysFromDatePlusWorkingDays()
    {
        $date = new DateTime('2024-06-10');
        $calendarDays = $this->daysOff->getCalendarDaysFromDatePlusWorkingDays($date, 5);
        $this->assertEquals(7, $calendarDays);
    }

    public function testGetDateFromDatePlusWorkingDays()
    {
        $date = new DateTime('2024-06-10');
        $newDate = $this->daysOff->getDateFromDatePlusWorkingDays($date, 5);
        $this->assertEquals('2024-06-17', $newDate->format('Y-m-d'));
    }

    public function testGetHolidaysAndWeekendsBetweenDates()
    {
        $fromDate = new DateTime('2024-12-24');
        $toDate = new DateTime('2024-12-26');
        $holidays = $this->daysOff->getHolidaysAndWeekendsBetweenDates($fromDate, $toDate);
        $expected = [
            '2024-12-25' => 'Christmas Day',
            '2024-12-26' => 'Boxing Day',
        ];
        $this->assertEquals($expected, $holidays);
    }

    public function testGetHolidaysBetweenDates()
    {
        $fromDate = new DateTime('2024-12-24');
        $toDate = new DateTime('2024-12-26');
        $holidays = $this->daysOff->getHolidaysBetweenDates($fromDate, $toDate);
        $expected = [
            '2024-12-25' => 'Christmas Day',
            '2024-12-26' => 'Boxing Day',
        ];
        $this->assertEquals($expected, $holidays);
    }

    public function testGetWeekendsBetweenDates()
    {
        $fromDate = new DateTime('2024-06-01');
        $toDate = new DateTime('2024-06-10');
        $weekends = $this->daysOff->getWeekendsBetweenDates($fromDate, $toDate);
        $expected = [
            '2024-06-01' => 'Saturday',
            '2024-06-02' => 'Sunday',
            '2024-06-08' => 'Saturday',
            '2024-06-09' => 'Sunday',
        ];
        $this->assertEquals($expected, $weekends);
    }

    public function testGetWorkingDaysBetweenDates()
    {
        $fromDate = new DateTime('2024-06-01');
        $toDate = new DateTime('2024-06-10');
        $workingDays = $this->daysOff->getWorkingDaysBetweenDates($fromDate, $toDate);
        $expected = [
            '2024-06-03' => 'Monday',
            '2024-06-04' => 'Tuesday',
            '2024-06-05' => 'Wednesday',
            '2024-06-06' => 'Thursday',
            '2024-06-07' => 'Friday',
            '2024-06-10' => 'Monday',
        ];
        $this->assertEquals($expected, $workingDays);
    }

    public function testGetHolidaysAndWeekendsFromDatePlusDays()
    {
        $date = new DateTime('2024-12-24');
        $holidays = $this->daysOff->getHolidaysAndWeekendsFromDatePlusDays($date, 3);
        $expected = [
            '2024-12-25' => 'Christmas Day',
            '2024-12-26' => 'Boxing Day',
        ];
        $this->assertEquals($expected, $holidays);
    }

    public function testGetHolidaysFromDatePlusDays()
    {
        $date = new DateTime('2024-12-24');
        $holidays = $this->daysOff->getHolidaysFromDatePlusDays($date, 3);
        $expected = [
            '2024-12-25' => 'Christmas Day',
            '2024-12-26' => 'Boxing Day',
        ];
        $this->assertEquals($expected, $holidays);
    }

    public function testGetWeekendsFromDatePlusDays()
    {
        $date = new DateTime('2024-06-01');
        $weekends = $this->daysOff->getWeekendsFromDatePlusDays($date, 10);
        $expected = [
            '2024-06-01' => 'Saturday',
            '2024-06-02' => 'Sunday',
            '2024-06-08' => 'Saturday',
            '2024-06-09' => 'Sunday',
        ];
        $this->assertEquals($expected, $weekends);
    }

    public function testGetWorkingDaysFromDatePlusDays()
    {
        $date = new DateTime('2024-06-01');
        $workingDays = $this->daysOff->getWorkingDaysFromDatePlusDays($date, 10);
        $expected = [
            '2024-06-03' => 'Monday',
            '2024-06-04' => 'Tuesday',
            '2024-06-05' => 'Wednesday',
            '2024-06-06' => 'Thursday',
            '2024-06-07' => 'Friday',
            '2024-06-10' => 'Monday',
            '2024-06-11' => 'Tuesday',
        ];
        $this->assertEquals($expected, $workingDays);
    }

    public function testCountHolidaysAndWeekendsBetweenDates()
    {
        $fromDate = new DateTime('2024-12-24');
        $toDate = new DateTime('2024-12-26');
        $count = $this->daysOff->countHolidaysAndWeekendsBetweenDates($fromDate, $toDate);
        $this->assertEquals(2, $count);
    }

    public function testCountHolidaysBetweenDates()
    {
        $fromDate = new DateTime('2024-12-24');
        $toDate = new DateTime('2024-12-26');
        $count = $this->daysOff->countHolidaysBetweenDates($fromDate, $toDate);
        $this->assertEquals(2, $count);
    }

    public function testCountWeekendsBetweenDates()
    {
        $fromDate = new DateTime('2024-06-01');
        $toDate = new DateTime('2024-06-10');
        $count = $this->daysOff->countWeekendsBetweenDates($fromDate, $toDate);
        $this->assertEquals(4, $count);
    }

    public function testCountWorkingDaysBetweenDates()
    {
        $fromDate = new DateTime('2024-06-01');
        $toDate = new DateTime('2024-06-10');
        $count = $this->daysOff->countWorkingDaysBetweenDates($fromDate, $toDate);
        $this->assertEquals(6, $count);
    }

    public function testCountHolidaysAndWeekendsFromDatePlusDays()
    {
        $date = new DateTime('2024-12-24');
        $count = $this->daysOff->countHolidaysAndWeekendsFromDatePlusDays($date, 3);
        $this->assertEquals(2, $count);
    }

    public function testCountHolidaysFromDatePlusDays()
    {
        $date = new DateTime('2024-12-24');
        $count = $this->daysOff->countHolidaysFromDatePlusDays($date, 3);
        $this->assertEquals(2, $count);
    }

    public function testCountWeekendsFromDatePlusDays()
    {
        $date = new DateTime('2024-06-01');
        $count = $this->daysOff->countWeekendsFromDatePlusDays($date, 10);
        $this->assertEquals(4, $count);
    }

    public function testCountWorkingDaysFromDatePlusDays()
    {
        $date = new DateTime('2024-06-01');
        $count = $this->daysOff->countWorkingDaysFromDatePlusDays($date, 10);
        $this->assertEquals(7, $count);
    }
}
