<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarDoctrineDocumentation extends AbstractController
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
     * @Route("/docs/calendar-doctrine", name="docs_calendar_doctrine")
     */
    public function calendar() : Response
    {
        return $this->render('documentation/calendar_doctrine.html.twig', [
            'activeSection' => 'calendar-doctrine',
            'versions' => $this->calendarDoctrineVersions(),
        ]);
    }

    /**
     * @Route("/docs/calendar-doctrine/{version}", name="docs_calendar_doctrine_version")
     */
    public function calendarDoctrineVersion(string $version) : Response
    {
        return $this->render('documentation/calendar_doctrine_version.html.twig', [
            'activeSection' => 'calendar-doctrine',
            'calendarDoctrineClasses' => $this->calendarDoctrineClasses($version),
            'version' => $version,
        ]);
    }
}