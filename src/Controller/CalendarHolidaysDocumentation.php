<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class CalendarHolidaysDocumentation extends AbstractController implements ControllerWithDataProviderInterface
{
    use CodeReflectionTrait;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    protected function parameterBag(): ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @Route("/docs/calendar-holidays", name="docs_calendar_holidays")
     */
    public function calendarHolidays() : Response
    {
        return $this->render('documentation/calendar_holidays.html.twig', [
            'activeSection' => 'calendar-holidays',
            'versions' => $this->parameterBag->get('aeon_php_calendar_holidays')['versions'],
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays/{version}", name="docs_calendar_holidays_version")
     */
    public function calendarHolidaysVersion(string $version) : Response
    {
        return $this->render('documentation/calendar_holidays_version.html.twig', [
            'activeSection' => 'calendar-holidays',
            'calendarHolidaysClasses' => $this->calendarHolidaysClasses($version),
            'version' => $version,
        ]);
    }

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'calendarHolidaysVersion';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->calendarVersions() as $version => $srv) {
                $arguments[] = [$version];
        }

        return $arguments;
    }
}