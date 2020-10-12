<?php

use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\TimePeriod;
use Aeon\Calendar\TimeUnit;

require_once __DIR__ . '/../vendor/autoload.php';

$now = GregorianCalendar::UTC()->now();

$now->until($now->add(TimeUnit::days(7)))
    ->iterate(TimeUnit::day(), Interval::closed())
    ->each(function(TimePeriod $timePeriod) {
        echo $timePeriod->start()
                ->day()
                ->format('Y-m-d H:i:s.uO') . "\n";
    });