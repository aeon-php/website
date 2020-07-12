<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarDocumentation extends AbstractController
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
     * @Route("/docs/calendar", name="docs_calendar")
     */
    public function calendar() : Response
    {
        return $this->render('documentation/calendar.html.twig', [
            'activeSection' => 'calendar',
            'versions' => $this->parameterBag->get('aeon_php_calendar')['versions'],
        ]);
    }

    /**
     * @Route("/docs/calendar/{version}", name="docs_calendar_version")
     */
    public function calendarVersion(string $version) : Response
    {
        return $this->render('documentation/calendar_version.html.twig', [
            'activeSection' => 'calendar',
            'calendarClasses' => $this->calendarClasses($version),
            'version' => $version,
        ]);
    }

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'calendarVersion';
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