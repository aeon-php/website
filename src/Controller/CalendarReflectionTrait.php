<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\PHPClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

trait CalendarReflectionTrait
{
    protected string $aeonCalendarSrc;

    /**
     * @return PHPClass[]
     */
    protected function calendarClassesReflection() : array
    {
        $betterReflection = new BetterReflection();
        $astLocator = ($betterReflection)->astLocator();

        $directoriesSourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator([$this->aeonCalendarSrc], $astLocator),
            ($betterReflection)->sourceLocator(),
        ]);

        $reflector = new ClassReflector($directoriesSourceLocator);

        return \array_map(
            function (ReflectionClass $reflectionClass) : PHPClass {
                return new PHPClass($reflectionClass);
            },
            $reflector->getAllClasses()
        );
    }
}
