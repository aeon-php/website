<?php

use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Algorithm\LeakyBucketAlgorithm;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\RateLimiter\RateLimiter;
use Aeon\RateLimiter\Storage\PSRCacheStorage;
use Aeon\Sleep\SystemProcess;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

require_once __DIR__.'/../vendor/autoload.php';

$calendar = GregorianCalendar::UTC();

$rateLimiter = new RateLimiter(
    new LeakyBucketAlgorithm(
        $calendar,
        $bucketSize = 5,
        $leakSize = 1,
        $leakTime = TimeUnit::minute()
    ),
    new PSRCacheStorage(
        new ArrayAdapter(),
        $calendar
    )
);

$rateLimiter->hit('operation.id');
$rateLimiter->hit('operation.id');
$rateLimiter->hit('operation.id');
$rateLimiter->hit('operation.id');
$rateLimiter->hit('operation.id');
try {
    $rateLimiter->hit('operation.id');
} catch (RateLimitException $exception) {
    echo "Rate limit exceeded, please wait {$exception->retryIn()->inSecondsPrecise()} seconds";
}

// this will put process into sleep for next ~59 seconds
$rateLimiter->throttle('operation.id', SystemProcess::current());
