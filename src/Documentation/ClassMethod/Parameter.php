<?php

declare(strict_types=1);

namespace App\Documentation\ClassMethod;

use App\Documentation\PHPClass;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionParameter;

final class Parameter
{
    private ReflectionParameter $reflectionParameter;

    public function __construct(ReflectionParameter $reflectionParameter)
    {
        $this->reflectionParameter = $reflectionParameter;
    }

    public function reflectionParameter() : ReflectionParameter
    {
        return $this->reflectionParameter;
    }

    public function typeClass() : PHPClass
    {
        return new PHPClass(ReflectionClass::createFromName($this->reflectionParameter->getType()->getName()));
    }

    public function isTypeFromGlobalNamespace() : bool
    {
        return $this->typeReflectionClass()->getLocatedSource()->isInternal();
    }

    public function type() : string
    {
        return $this->reflectionParameter->hasType()
            ? $this->reflectionParameter->getType()->getName()
            : 'void';
    }
}
