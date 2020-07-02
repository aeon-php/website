<?php

namespace App\Tests\Unit\Documentation;

use Aeon\Calendar\System\Process;
use App\Documentation\PHPClass;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class PHPClassTest extends TestCase
{
    public function test_class_name() : void
    {
        $class = new PHPClass(ReflectionClass::createFromName(Process::class));

        $this->assertSame('Process', $class->reflectionClass()->getName());
    }
}