<?php


namespace App\Documentation;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;

final class PHPClass
{
    const AEON_CALENDAR_NAMESPACE = 'Aeon\\Calendar';
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;
    /**
     * @var string
     */
    private $stripNamespace;

    public function __construct(ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
    }

    public function reflectionClass(): ReflectionClass
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
        if ($this->isCalendar()){
            return \ltrim($this->reflectionClass->getName(), self::AEON_CALENDAR_NAMESPACE);
        }

        return $this->reflectionClass->getShortName();
    }

    public function slug() : string
    {
        if ($this->isCalendar()){
            return \str_replace('\\', '-', \mb_strtolower(\ltrim($this->reflectionClass->getName(), self::AEON_CALENDAR_NAMESPACE)));
        }

        return \str_replace('\\', '-', \mb_strtolower(\ltrim($this->reflectionClass->getName())));
    }

    /**
     * @return ClassMethod[]
     */
    public function methods() : array
    {
        return \array_map(
            function(ReflectionMethod $reflectionMethod) : ClassMethod {
                return new ClassMethod($this, $reflectionMethod);
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
            function(ReflectionClass $reflectionClass) : PHPClass {
                return new PHPClass($reflectionClass);
            },
            $this->reflectionClass()->getInterfaces()
        );
    }

    public function isCalendar() : bool
    {
        return \str_starts_with($this->reflectionClass->getNamespaceName(), self::AEON_CALENDAR_NAMESPACE);
    }

    public function isInternal() : bool
    {
        return $this->reflectionClass->isInternal();
    }
}