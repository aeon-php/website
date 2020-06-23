<?php

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Month;
use Aeon\Calendar\Gregorian\Time;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\Gregorian\Year;

require_once __DIR__ . '/../vendor/autoload.php';

$dateTime = GregorianCalendar::UTC()->now();
$dateTime = DateTime::fromString('2020-01-01 00:00:00 UTC');
$dateTime = DateTime::fromDateTime(
    new DateTimeImmutable('2020-01-01 00:00:00', new DateTimeZone('UTC'))
);
$dateTime = new DateTime(
    new Day(new Month(new Year(2020), 01), 01),
    new Time(00, 00, 00, 0),
    TimeZone::UTC()
);
$dateTime = DateTime::create(2020, 01, 01, 00, 00, 00, 0, 'UTC');
