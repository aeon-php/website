<?php

declare(strict_types=1);

namespace App\Twig;

use App\Documentation\SlugGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PHPDocsExtension extends AbstractExtension
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('class_url', [$this, 'classUrlFilter'], ['is_safe' => ['html']]),
            new TwigFilter('class_method_url', [$this, 'classMethodUrlFilter'], ['is_safe' => ['html']]),
        ];
    }

    public function classUrlFilter(string $className, string $version) : string
    {
        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return $this->router->generate('docs_calendar_holidays_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\BusinessHours')) {
            return $this->router->generate('docs_business_hours_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\YasumiHolidays')) {
            return $this->router->generate('docs_calendar_holidays_yasumi_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Retry')) {
            return $this->router->generate('docs_retry_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\System')) {
            return $this->router->generate('docs_sleep_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Twig\\')) {
            return $this->router->generate('docs_calendar_twig_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Doctrine\\')) {
            return $this->router->generate('docs_calendar_doctrine_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calculator\\')) {
            return $this->router->generate('docs_calendar_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return $this->router->generate('docs_calendar_class', ['classSlug' => SlugGenerator::forClass($className), 'version' => $version]);
        }

        return '#';
    }

    public function classMethodUrlFilter(string $className, string $methodName, string $version) : string
    {
        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\YasumiHolidays')) {
            return $this->router->generate('docs_calendar_holidays_yasumi_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return $this->router->generate('docs_calendar_holidays_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\BusinessHours')) {
            return $this->router->generate('docs_business_hours_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Retry')) {
            return $this->router->generate('docs_retry_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Doctrine\\')) {
            return $this->router->generate('docs_calendar_doctrine_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Twig\\')) {
            return $this->router->generate('docs_calendar_twig_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\System')) {
            return $this->router->generate('docs_sleep_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calculator\\')) {
            return $this->router->generate('docs_calendar_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return $this->router->generate('docs_calendar_class_method', [
                'version' => $version,
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        return '#';
    }
}
