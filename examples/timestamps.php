<?php

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimeEpoch;

require_once __DIR__ . '/../vendor/autoload.php';

$dateTime = DateTime::fromString('2020-01-01 00:00:00 UTC');
$timestampUNIX = $dateTime->timestamp(TimeEpoch::UNIX());
$timestampUTC = $dateTime->timestamp(TimeEpoch::UTC());
$timestampGPS = $dateTime->timestamp(TimeEpoch::GPS());
$timestampTAI = $dateTime->timestamp(TimeEpoch::TAI());
