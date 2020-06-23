<?php

use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\TimeUnit;

require_once __DIR__ . '/../vendor/autoload.php';

$calendar = GregorianCalendar::UTC();
$calendar->currentYear()
    ->january()
    ->lastDay()
    ->noon()
    ->sub(TimeUnit::days(3));