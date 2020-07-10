<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class CalendarTwigDocumentation extends AbstractController implements ControllerWithDataProviderInterface
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
     * @Route("/docs/calendar-twig", name="docs_calendar_twig")
     */
    public function calendar() : Response
    {
        return $this->render('documentation/calendar_twig.html.twig', [
            'activeSection' => 'calendar-twig',
            'versions' => $this->calendarTwigVersions(),
        ]);
    }

    /**
     * @Route("/docs/calendar-twig/{version}", name="docs_calendar_twig_version")
     */
    public function calendarTwigVersion(string $version) : Response
    {
        return $this->render('documentation/calendar_twig_version.html.twig', [
            'activeSection' => 'calendar-twig',
            'calendarTwigClasses' => $this->calendarTwigClasses($version),
            'version' => $version,
        ]);
    }

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'calendarTwigVersion';
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