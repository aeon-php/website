<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
            'calendarClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_src')),
            'calendarHolidaysClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_holidays_src')),
            'processClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_process_src')),
            'retryClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_retry_src')),
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
            'calendarClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_src')),
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays", name="docs_calendar_holidays")
     */
    public function calendarHolidays()
    {
        return $this->render('documentation/calendar_holidays.html.twig', [
            'activeSection' => 'calendar-holidays',
            'calendarHolidaysClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_holidays_src')),
        ]);
    }

    /**
     * @Route("/docs/process", name="docs_process")
     */
    public function process()
    {
        return $this->render('documentation/process.html.twig', [
            'activeSection' => 'process',
            'processClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_process_src')),
        ]);
    }

    /**
     * @Route("/docs/retry", name="docs_retry")
     */
    public function retry()
    {
        return $this->render('documentation/retry.html.twig', [
            'activeSection' => 'process',
            'retryClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_retry_src')),
        ]);
    }

    public function navigation(?string $activeSection = null) : Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_src')),
            'calendarHolidaysClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_calendar_holidays_src')),
            'processClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_process_src')),
            'retryClasses' => $this->codeClassesReflection($this->parameterBag->get('aeon_php_retry_src')),
        ]);
    }
}
