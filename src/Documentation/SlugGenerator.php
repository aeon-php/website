<?php

declare(strict_types=1);

namespace App\Documentation;

final class SlugGenerator
{
    public static function forPHPClass(PHPClass $PHPClass) : string
    {
        return self::forClass($PHPClass->reflectionClass()->getName());
    }

    public static function forClass(string $className) : string
    {
        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim($className, 'Aeon\\Calendar\\Gregorian')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim($className, 'Aeon\\Calendar')));
        }

        return \str_replace('\\', '-', \mb_strtolower($className));
    }

    public static function forClassMethod(ClassMethod $method) : string
    {
        return self::forMethod($method->name());
    }

    public static function forMethod(string $method) : string
    {
        return \str_replace('\\', '-', \mb_strtolower(\ltrim($method)));
    }
}
