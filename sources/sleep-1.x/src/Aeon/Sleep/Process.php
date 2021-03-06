<?php

declare(strict_types=1);

namespace Aeon\Sleep;

use Aeon\Calendar\TimeUnit;

interface Process
{
    public function sleep(TimeUnit $timeUnit) : void;
}
