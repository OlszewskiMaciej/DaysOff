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
use DateTime;

// Initialize the holiday provider
$holidayProvider = new PolishHolidayProvider();

// Initialize the DaysOff instance
$daysOff = new DaysOff($holidayProvider);

// Check if a date is a holiday
$date = new DateTime('2023-12-25'); // Christmas
$isHoliday = $daysOff->isHoliday($date); // true

// Check if a date is a weekend
$date = new DateTime('2023-12-24'); // Sunday
$isWeekend = $daysOff->isWeekend($date); // true

// Check if a date is a working day
$date = new DateTime('2023-12-26'); // Boxing Day
$isWorkingDay = $daysOff->isWorkingDay($date); // false

// Calculate a date based on working days
$date = new DateTime('2023-12-24'); // Christmas Eve
$deliveryDate = $daysOff->getDateFromDatePlusWorkingDays($date, 2); // 2023-12-28
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
use DateTime;

// Inicjalizacja "dostawcy" świąt
$holidayProvider = new PolishHolidayProvider();

// Inicjalizacja DaysOff
$daysOff = new DaysOff($holidayProvider);

// Sprawdzenie, czy data jest świętem
$date = new DateTime('2023-12-25'); // Boże Narodzenie
$isHoliday = $daysOff->isHoliday($date); // true

// Sprawdzenie, czy data jest weekendem
$date = new DateTime('2023-12-24'); // Niedziela
$isWeekend = $daysOff->isWeekend($date); // true

// Sprawdzenie, czy data jest dniem roboczym
$date = new DateTime('2023-12-26'); // Drugi dzień Bożego Narodzenia
$isWorkingDay = $daysOff->isWorkingDay($date); // false

// Obliczenie daty na podstawie dni roboczych
$date = new DateTime('2023-12-24'); // Wigilia
$deliveryDate = $daysOff->getDateFromDatePlusWorkingDays($date, 2); // 2023-12-28
```

## Przykłady użycia

Biblioteka DaysOff może być przydatna w różnych scenariuszach, w tym:

- **Planowanie Zadań**: Określanie dni roboczych do planowania zadań lub wydarzeń.
- **Obliczanie Dat Dostawy**: Obliczanie dat dostawy na podstawie dni roboczych.
- **Alokacja Zasobów**: Alokowanie zasobów z uwzględnieniem świąt i weekendów.
- **Zarządzanie Urlopami**: Zarządzanie wnioskami urlopowymi pracowników na podstawie dni roboczych.

---