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

    protected function parameterBag() : ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
            'calendarClasses' => $this->calendarClasses('1.x'),
            'calendarVersion' => '1.x',
            'calendarHolidaysClasses' => $this->calendarHolidaysClasses('1.x'),
            'calendarHolidaysVersion' => '1.x',
            'processClasses' => $this->processClasses('1.x'),
            'processVersion' => '1.x',
            'retryClasses' => $this->retryClasses('1.x'),
            'retryVersion' => '1.x',
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
            'versions' => $this->parameterBag->get('aeon_php_calendar')['versions'],
        ]);
    }

    /**
     * @Route("/docs/calendar/{version}", name="docs_calendar_version")
     */
    public function calendarVersion(string $version)
    {
        return $this->render('documentation/calendar_version.html.twig', [
            'activeSection' => 'calendar',
            'calendarClasses' => $this->calendarClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays", name="docs_calendar_holidays")
     */
    public function calendarHolidays()
    {
        return $this->render('documentation/calendar_holidays.html.twig', [
            'activeSection' => 'calendar-holidays',
            'versions' => $this->parameterBag->get('aeon_php_calendar_holidays')['versions'],
        ]);
    }

    /**
     * @Route("/docs/calendar-holidays/{version}", name="docs_calendar_holidays_version")
     */
    public function calendarHolidaysVersion(string $version)
    {
        return $this->render('documentation/calendar_holidays_version.html.twig', [
            'activeSection' => 'calendar-holidays',
            'calendarHolidaysClasses' => $this->calendarHolidaysClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/process", name="docs_process")
     */
    public function process()
    {
        return $this->render('documentation/process.html.twig', [
            'activeSection' => 'process',
            'versions' => $this->parameterBag->get('aeon_php_process')['versions'],
        ]);
    }

    /**
     * @Route("/docs/process/{version}", name="docs_process_version")
     */
    public function processVersion(string $version)
    {
        return $this->render('documentation/process_version.html.twig', [
            'activeSection' => 'process',
            'processClasses' => $this->processClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/retry", name="docs_retry")
     */
    public function retry()
    {
        return $this->render('documentation/retry.html.twig', [
            'activeSection' => 'retry',
            'versions' => $this->parameterBag->get('aeon_php_retry')['versions'],
        ]);
    }

    /**
     * @Route("/docs/retry/{version}", name="docs_retry_version")
     */
    public function retryVersion(string $version)
    {
        return $this->render('documentation/retry_version.html.twig', [
            'activeSection' => 'retry',
            'retryClasses' => $this->retryClasses($version),
            'version' => $version,
        ]);
    }

    public function navigation(?string $activeSection = null) : Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarVersions' => $this->parameterBag->get('aeon_php_calendar')['versions'],
            'calendarHolidaysVersions' => $this->parameterBag->get('aeon_php_calendar_holidays')['versions'],
            'processVersions' => $this->parameterBag->get('aeon_php_process')['versions'],
            'retryVersions' => $this->parameterBag->get('aeon_php_retry')['versions'],
        ]);
    }
}
