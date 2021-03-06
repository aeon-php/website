<?php

declare(strict_types=1);

namespace Aeon\Sleep;

use Aeon\Calendar\TimeUnit;

/**
 * @codeCoverageIgnore
 */
final class DummyProcess implements Process
{
    public function sleep(TimeUnit $timeUnit) : void
    {
    }
}
