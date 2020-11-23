<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CalendarDocumentation extends AbstractController
{
    use CodeReflectionTrait;

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
    public function calendar(): Response
    {
        return $this->render('documentation/calendar.html.twig', [
            'activeSection' => 'calendar',
            'versions' => $this->parameterBag->get('aeon_libraries')['calendar']['versions'],
        ]);
    }

    /**
     * @Route("/docs/calendar/{version}", name="docs_calendar_version")
     */
    public function calendarVersion(string $version): Response
    {
        return $this->render('documentation/calendar_version.html.twig', [
            'activeSection' => 'calendar',
            'calendarClasses' => $this->calendarClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/calendar/{version}/{classSlug}", name="docs_calendar_class")
     */
    public function calendarClass(string $version, string $classSlug): Response
    {
        foreach ($classes = $this->calendarClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/calendar/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_class_method")
     */
    public function calendarClassMethod(string $version, string $classSlug, string $methodSlug): Response
    {
        foreach ($classes = $this->calendarClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
