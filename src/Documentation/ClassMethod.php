<?php

declare(strict_types=1);

namespace App\Documentation;

use App\Documentation\ClassMethod\Parameter;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\Reflector\ClassReflector;

final class ClassMethod
{
    private ReflectionMethod $reflectionMethod;

    private PHPClass $PHPClass;

    private ClassReflector $reflector;

    public function __construct(PHPClass $PHPClass, ReflectionMethod $reflectionMethod, ClassReflector $reflector)
    {
        $this->reflectionMethod = $reflectionMethod;
        $this->PHPClass = $PHPClass;
        $this->reflector = $reflector;
    }

    public function returnTypeClass(): PHPClass
    {
        return new PHPClass($this->reflector->reflect($this->reflectionMethod->getReturnType()->getName()), $this->reflector);
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

    public function name(): string
    {
        return $this->reflectionMethod->getName();
    }

    public function accessType(): string
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
    public function parameters(): array
    {
        return \array_map(
            function (ReflectionParameter $reflectionParameter): Parameter {
                return new Parameter($reflectionParameter, $this->reflector);
            },
            $this->reflectionMethod->getParameters()
        );
    }

    public function docComment(): ?DocBlock
    {
        if ($this->reflectionMethod->getDocComment()) {
            return DocBlockFactory::createInstance()->create($this->reflectionMethod->getDocComment());
        }

        return null;
    }
}
