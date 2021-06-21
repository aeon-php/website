<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarHolidaysYasumiDocumentation extends AbstractController
{
    use CodeReflectionTrait;

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
     * @Route("/docs/calendar-holidays-yasumi", name="docs_calendar_holidays_yasumi")
     */
    public function calendarHolidaysYasumi() : Response
    {
        return $this->render('documentation/calendar_holidays_yasumi.html.twig', [
            'activeSection' => 'calendar-holidays-yasumi',
            'versions' => $this->calendarHolidaysYasumiVersions(),
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays-yasumi/{version}", name="docs_calendar_holidays_yasumi_version")
     */
    public function calendarHolidaysYasumiVersion(string $version) : Response
    {
        return $this->render('documentation/calendar_holidays_yasumi_version.html.twig', [
            'activeSection' => 'calendar-holidays-yasumi',
            'calendarHolidaysYasumiClasses' => $this->calendarHolidaysYasumiClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays-yasumi/{version}/{classSlug}", name="docs_calendar_holidays_yasumi_class")
     */
    public function calendarHolidaysYasumiClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->calendarHolidaysYasumiClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'githubProjectUrl' => "https://github.com/aeon-php/calendar-holidays-yasumi",
                    'activeSection' => 'calendar-holidays-yasumi',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar Holidays Yasumi',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/calendar-holidays-yasumi/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_holidays_yasumi_class_method")
     */
    public function calendarHolidaysYasumiClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->calendarHolidaysYasumiClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'githubProjectUrl' => "https://github.com/aeon-php/calendar-holidays-yasumi",
                            'activeSection' => 'calendar-holidays-yasumi',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Holidays Yasumi',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
