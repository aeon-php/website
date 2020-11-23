<?php

namespace App\Tests\Unit\Documentation;

use Aeon\Calendar\System\DummyProcess;
use Aeon\Calendar\System\Process;
use Aeon\Calendar\System\SystemProcess;
use Aeon\Calendar\TimeUnit;
use App\Documentation\SlugGenerator;
use PHPUnit\Framework\TestCase;

final class SlugGeneratorTest extends TestCase
{
    /**
     * @dataProvider generate_slug_for_classes_data_provider
     */
    public function test_generate_slug_for_classes(string $className, string $slug): void
    {
        $this->assertSame($slug, SlugGenerator::forClass($className));
    }

    public function generate_slug_for_classes_data_provider(): \Generator
    {
        yield ['Aeon\\Calendar\\Gregorian\\BusinessHours', 'business-hours'];
        yield [Process::class, 'process'];
        yield [DummyProcess::class, 'dummyprocess'];
        yield [SystemProcess::class, 'systemprocess'];
        yield [TimeUnit::class, 'timeunit'];
    }
}
