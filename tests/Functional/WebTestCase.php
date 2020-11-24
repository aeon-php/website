<?php

namespace App\Tests\Functional;

use App\Kernel;

abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function generateUrl(string $route, array $parameters = []): string
    {
        return self::$container->get('router')->generate($route, $parameters);
    }

    public function calendarSrcPath(): string
    {
        return self::$container->getParameter('aeon_php_calendar')['versions']['1.x'];
    }

    public function calendarHolidaySrcPath(): string
    {
        return self::$container->getParameter('aeon_php_calendar_holidays_src');
    }
}
