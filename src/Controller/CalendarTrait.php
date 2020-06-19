<?php


namespace App\Controller;


use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

trait CalendarTrait
{
    protected string $aeonCalendarSrc;

    /**
     * @return ReflectionClass[]
     */
    protected function calendarClassesReflection() : array
    {
        $betterReflection = new BetterReflection();
        $astLocator = ($betterReflection)->astLocator();

        $directoriesSourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator([$this->aeonCalendarSrc], $astLocator),
            ($betterReflection)->sourceLocator()
        ]);

        $reflector = new ClassReflector($directoriesSourceLocator);

        return $reflector->getAllClasses();
    }
}