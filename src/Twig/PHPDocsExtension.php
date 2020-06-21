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

    public function classUrlFilter(string $className) : string
    {
        if (!\class_exists($className)) {
            return '#';
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return $this->router->generate('docs_calendar_holidays_class', ['classSlug' => SlugGenerator::forClass($className)]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return $this->router->generate('docs_calendar_class', ['classSlug' => SlugGenerator::forClass($className)]);
        }

        return '#';
    }

    public function classMethodUrlFilter(string $className, string $methodName) : string
    {
        if (!\class_exists($className)) {
            return '#';
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\Gregorian\\Holidays')) {
            return $this->router->generate('docs_calendar_holidays_class_method', [
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        if (\str_starts_with(\ltrim($className, '\\'), 'Aeon\\Calendar\\')) {
            return $this->router->generate('docs_calendar_class_method', [
                'classSlug' => SlugGenerator::forClass($className),
                'methodSlug' => SlugGenerator::forMethod($methodName),
            ]);
        }

        return '#';
    }
}
