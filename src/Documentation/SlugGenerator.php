<?php

declare(strict_types=1);

namespace App\Documentation;

final class SlugGenerator
{
    public static function forPHPClass(PHPClass $PHPClass): string
    {
        return self::forClass($PHPClass->reflectionClass()->getName());
    }

    public static function forClass(string $className): string
    {
        if ('Aeon\\Symfony\\AeonBundle' === \ltrim($className, '\\')) {
            return 'symfony-bundle';
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\HolidaysFactory')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Calendar\\Gregorian', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Calendar\\Gregorian', '', $className), '\\')));
        }

        if ('Aeon\\Calendar\\Gregorian\\BusinessHours' === \ltrim($className, '\\')) {
            return 'business-hours';
        }

        if ('Aeon\\Calendar\\Gregorian\\YasumiHolidays' === \ltrim($className, '\\')) {
            return 'yasumi-holidays';
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\BusinessHours')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Calendar\\Gregorian\\BusinessHours', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Sleep')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Sleep', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Retry')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Retry', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Twig\\')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Twig', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calculator\\')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Calculator', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Collection\\')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Collection', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\RateLimiter')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\RateLimiter', '', $className), '\\')));
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return \str_replace('\\', '-', \mb_strtolower(\ltrim(\str_replace('Aeon\\Calendar', '', $className), '\\')));
        }

        return \str_replace('\\', '-', \mb_strtolower($className));
    }

    public static function forClassMethod(ClassMethod $method): string
    {
        return self::forMethod($method->name());
    }

    public static function forMethod(string $method): string
    {
        return \str_replace(
            '_',
            '',
            \str_replace('\\', '-', \mb_strtolower(\ltrim($method)))
        );
    }
}
