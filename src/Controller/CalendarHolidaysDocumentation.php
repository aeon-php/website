<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarHolidaysDocumentation extends AbstractController
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

    protected function parameterBag() : ParameterBagInterface
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

    /**
     * @Route("/docs/calendar-holidays/{version}/{classSlug}", name="docs_calendar_holidays_class")
     */
    public function calendarHolidaysClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->calendarHolidaysClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar-holidays',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar Holidays',
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/calendar-holidays/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_holidays_class_method")
     */
    public function calendarHolidaysClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->calendarHolidaysClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar-holidays',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Holidays',
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }
}
