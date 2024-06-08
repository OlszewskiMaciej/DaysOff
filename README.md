# DaysOff library

The DaysOff library is a PHP package that provides functionalities related to calculating holidays, weekends, and working days. It offers methods to determine if a given date is a holiday, weekend, or working day, as well as to calculate dates based on working days, can also return specific days between given dates.

## Installation

You can install the library via Composer:

```bash
composer require olszewskimaciej/daysoff:dev-main
```

## Usage

Here's an example of how to use it:

```php
use DaysOff\DaysOff;
use DaysOff\Providers\PolishHolidayProvider;

// Initialize the holiday provider
$holidayProvider = new PolishHolidayProvider();

// Initialize the DaysOff instance
$daysOff = new DaysOff($holidayProvider);

$fromDate = new DateTime('2024-01-01');
$toDate = new DateTime('2024-12-31');
$daysToAdd = 10;

// Add and remove holidays
$daysOff->addHoliday(new DateTime('2025-05-02'), 'Custom Holiday');
$daysOff->removeHoliday(new DateTime('2025-01-06'));

// Calculate calendar days from date + X working days
echo '<b>getCalendarDaysFromDatePlusWorkingDays</b>: ';
print_r($daysOff->getCalendarDaysFromDatePlusWorkingDays($fromDate, $daysToAdd));

// Calculate date from date + X working days
echo '<b>getDateFromDatePlusWorkingDays</b>: ';
print_r($daysOff->getDateFromDatePlusWorkingDays($fromDate, $daysToAdd));

// Get holiday name
echo '<b>getHolidayName</b>: ';
print_r($daysOff->getHolidayName($fromDate));

// Check if a date is a holiday
echo '<b>isHoliday</b>: ';
var_dump($daysOff->isHoliday($fromDate));

// Count holidays between dates
echo '<b>countHolidaysBetweenDates</b>: ';
var_dump($daysOff->countHolidaysBetweenDates($fromDate, $toDate));

// Get holidays between dates
echo '<b>getHolidaysBetweenDates</b>: ';
print_r($daysOff->getHolidaysBetweenDates($fromDate, $toDate));

// Count holidays and weekends from date + X days
echo '<b>countHolidaysAndWeekendsFromDatePlusDays</b>: ';
var_dump($daysOff->countHolidaysAndWeekendsFromDatePlusDays($fromDate, $daysToAdd));

// Get holidays and weekends from date + X days
echo '<b>getHolidaysAndWeekendsFromDatePlusDays</b>: ';
print_r($daysOff->getHolidaysAndWeekendsFromDatePlusDays($fromDate, $daysToAdd));

// Count working days from date + X days
echo '<b>countWorkingDaysFromDatePlusDays</b>: ';
var_dump($daysOff->countWorkingDaysFromDatePlusDays($fromDate, $daysToAdd));
```

## Use Cases

The DaysOff library can be useful for various scenarios, including:

- **Task Scheduling**: Determine business days for scheduling tasks or events.
- **Delivery Date Calculation**: Calculate delivery dates based on working days.
- **Resource Allocation**: Allocate resources considering holidays and weekends.
- **Leave Management**: Manage employee leave requests based on working days.

---

# Biblioteka DaysOff

DaysOff to biblioteka PHP, która dostarcza funkcje związane z obliczaniem dni świątecznych, weekendów i dni roboczych. Oferuje metody do określania, czy podana data jest świętem, weekendem lub dniem roboczym, a także do obliczania dat na podstawie dni roboczych, potrafi również zwrócić określone dni pomiędzy podanymi datami.

## Instalacja

Bibliotekę można zainstalować za pomocą Composera:

```bash
composer require olszewskimaciej/daysoff:dev-main
```

## Użycie

Oto przykład użycia:

```php
use DaysOff\DaysOff;
use DaysOff\Providers\PolishHolidayProvider;

// Inicjalizacja "dostawcy" świąt
$holidayProvider = new PolishHolidayProvider();

// Inicjalizacja biblioteki
$daysOff = new DaysOff($holidayProvider);

$fromDate = new DateTime('2024-01-01');
$toDate = new DateTime('2024-12-31');
$daysToAdd = 10;

// Dodaj, usuń święto
$daysOff->addHoliday(new DateTime('2025-05-02'), 'Custom Holiday');
$daysOff->removeHoliday(new DateTime('2025-01-06'));

// Oblicz liczbę dni kalendarzowych od daty + X dni roboczych
echo '<b>getCalendarDaysFromDatePlusWorkingDays</b>: ';
print_r($daysOff->getCalendarDaysFromDatePlusWorkingDays($fromDate, $daysToAdd));

// Oblicz datę od daty + X dni roboczych
echo '<b>getDateFromDatePlusWorkingDays</b>: ';
print_r($daysOff->getDateFromDatePlusWorkingDays($fromDate, $daysToAdd));

// Zwróc nazwę święta
echo '<b>getHolidayName</b>: ';
print_r($daysOff->getHolidayName($fromDate));

// Sprawdź czy podana data wypada w święto
echo '<b>isHoliday</b>: ';
var_dump($daysOff->isHoliday($fromDate));

// Policz święta pomiędzy podanymi datami
echo '<b>countHolidaysBetweenDates</b>: ';
var_dump($daysOff->countHolidaysBetweenDates($fromDate, $toDate));

// Zwróć święta pomiędzy podanymi datami
echo '<b>getHolidaysBetweenDates</b>: ';
print_r($daysOff->getHolidaysBetweenDates($fromDate, $toDate));

// Policz święta i weekendy od daty + X dni
echo '<b>countHolidaysAndWeekendsFromDatePlusDays</b>: ';
var_dump($daysOff->countHolidaysAndWeekendsFromDatePlusDays($fromDate, $daysToAdd));

// Zwróc święta i weekendy od daty + X dni
echo '<b>getHolidaysAndWeekendsFromDatePlusDays</b>: ';
print_r($daysOff->getHolidaysAndWeekendsFromDatePlusDays($fromDate, $daysToAdd));

// Policz dni robocze od daty + X dni
echo '<b>countWorkingDaysFromDatePlusDays</b>: ';
var_dump($daysOff->countWorkingDaysFromDatePlusDays($fromDate, $daysToAdd));
```

## Przykłady użycia

Biblioteka DaysOff może być przydatna w różnych scenariuszach, w tym:

- **Planowanie Zadań**: Określanie dni roboczych do planowania zadań lub wydarzeń.
- **Obliczanie Dat Dostawy**: Obliczanie dat dostawy na podstawie dni roboczych.
- **Alokacja Zasobów**: Alokowanie zasobów z uwzględnieniem świąt i weekendów.
- **Zarządzanie Urlopami**: Zarządzanie wnioskami urlopowymi pracowników na podstawie dni roboczych.

---
