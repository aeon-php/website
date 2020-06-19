<?php

namespace App\Documentation;

use App\Documentation\ClassMethod\Parameter;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;

final class ClassMethod
{
    private ReflectionMethod $reflectionMethod;

    private PHPClass $PHPClass;

    public function __construct(PHPClass $PHPClass, ReflectionMethod $reflectionMethod)
    {
        $this->reflectionMethod = $reflectionMethod;
        $this->PHPClass = $PHPClass;
    }

    public function returnTypeClass() : PHPClass
    {
        return new PHPClass(ReflectionClass::createFromName($this->reflectionMethod->getReturnType()->getName()));
    }

    public function PHPClass(): PHPClass
    {
        return $this->PHPClass;
    }

    public function reflectionClass(): ReflectionClass
    {
        return $this->PHPClass->reflectionClass();
    }

    public function reflectionMethod(): ReflectionMethod
    {
        return $this->reflectionMethod;
    }

    public function name() : string
    {
        return $this->reflectionMethod->getName();
    }

    public function accessType() : string
    {
        if ($this->reflectionMethod->isPrivate()) {
            return 'private';
        }

        if ($this->reflectionMethod->isPublic()) {
            return 'public';
        }

        return 'protected';
    }

    /**
     * @return Parameter[]
     */
    public function parameters() : array
    {
        return \array_map(
            function (ReflectionParameter $reflectionParameter) : Parameter {
                return new Parameter($reflectionParameter);
            },
            $this->reflectionMethod->getParameters()
        );
    }

    public function slug()
    {
        return \str_replace('\\', '-', \mb_strtolower(\ltrim($this->reflectionMethod->getName())));
    }
}