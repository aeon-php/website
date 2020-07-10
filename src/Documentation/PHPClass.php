<?php

declare(strict_types=1);

namespace App\Documentation;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflector\ClassReflector;

final class PHPClass
{
    private ReflectionClass $reflectionClass;

    private ClassReflector $reflector;

    public function __construct(ReflectionClass $reflectionClass, ClassReflector $reflector)
    {
        $this->reflectionClass = $reflectionClass;
        $this->reflector = $reflector;
    }

    public function reflectionClass() : ReflectionClass
    {
        return $this->reflectionClass;
    }

    public function name() : string
    {
        return $this->reflectionClass->getName();
    }

    public function type() : string
    {
        if ($this->reflectionClass->isInterface()) {
            return 'interface';
        }

        if ($this->reflectionClass->isTrait()) {
            return 'trait';
        }

        if ($this->reflectionClass->isAbstract()) {
            return 'abstract class';
        }

        return 'class';
    }

    public function shortName() : string
    {
        return $this->reflectionClass->getShortName();
    }

    /**
     * @return ClassMethod[]
     */
    public function methods() : array
    {
        return \array_map(
            function (ReflectionMethod $reflectionMethod) : ClassMethod {
                return new ClassMethod($this, $reflectionMethod, $this->reflector);
            },
            $this->reflectionClass->getMethods()
        );
    }

    /**
     * @return PHPClass[]
     */
    public function interfaces() : array
    {
        return \array_map(
            function (ReflectionClass $reflectionClass) : PHPClass {
                return new PHPClass($reflectionClass, $this->reflector);
            },
            $this->reflectionClass()->getInterfaces()
        );
    }

    public function isInternal() : bool
    {
        return $this->reflectionClass->isInternal();
    }

    public function docComment() : DocBlock
    {
        return DocBlockFactory::createInstance()->create($this->reflectionClass->getDocComment());
    }
}
