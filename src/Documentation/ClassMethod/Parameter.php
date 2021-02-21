<?php

declare(strict_types=1);

namespace App\Documentation\ClassMethod;

use App\Documentation\PHPClass;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\Reflector\ClassReflector;

final class Parameter
{
    private ReflectionParameter $reflectionParameter;

    private ClassReflector $reflector;

    public function __construct(ReflectionParameter $reflectionParameter, ClassReflector $reflector)
    {
        $this->reflectionParameter = $reflectionParameter;
        $this->reflector = $reflector;
    }

    public function reflectionParameter() : ReflectionParameter
    {
        return $this->reflectionParameter;
    }

    public function typeClass() : PHPClass
    {
        return new PHPClass($this->reflector->reflect($this->reflectionParameter->getType()->getName()), $this->reflector);
    }

    public function isTypeFromGlobalNamespace() : bool
    {
        return $this->typeClass()->isInternal();
    }

    public function type() : string
    {
        return $this->reflectionParameter->hasType()
            ? $this->reflectionParameter->getType()->getName()
            : 'void';
    }
}
