<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\PHPClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

trait CodeReflectionTrait
{
    /**
     * @return PHPClass[]
     */
    protected function codeClassesReflection(string $srcPath) : array
    {
        $betterReflection = new BetterReflection();
        $astLocator = ($betterReflection)->astLocator();

        $directoriesSourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator([$srcPath], $astLocator),
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
