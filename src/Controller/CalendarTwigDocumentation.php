<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarTwigDocumentation extends AbstractController
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

    /**
     * @Route("/docs/calendar-twig/{version}/{classSlug}", name="docs_calendar_twig_class")
     */
    public function calendarTwigClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->calendarTwigClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'githubProjectUrl' => "https://github.com/aeon-php/calendar-twig",
                    'class' => $phpClass,
                    'activeSection' => 'calendar-twig',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar Twig',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/calendar-twig/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_twig_class_method")
     */
    public function calendarTwigClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->calendarTwigClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'githubProjectUrl' => "https://github.com/aeon-php/calendar-twig",
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar-twig',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Twig',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
