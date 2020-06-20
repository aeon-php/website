<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    use CalendarReflectionTrait;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->aeonCalendarSrc = $this->parameterBag->get('aeon_php_calendar_src');
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
            'calendarClasses' => $this->calendarClassesReflection(),
        ]);
    }

    /**
     * @Route("/docs/getting-started", name="docs_getting_started")
     */
    public function gettingStarted()
    {
        return $this->render('documentation/getting_started.html.twig', [
            'activeSection' => 'introduction',
        ]);
    }

    /**
     * @Route("/docs/calendar", name="docs_calendar")
     */
    public function calendar()
    {
        return $this->render('documentation/calendar.html.twig', [
            'activeSection' => 'calendar',
            'calendarClasses' => $this->calendarClassesReflection(),
        ]);
    }

    public function navigation(?string $activeSection = null) : Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarClasses' => $this->calendarClassesReflection(),
        ]);
    }
}
