<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
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

    protected function parameterBag() : ParameterBagInterface
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

    /**
     * @Route("/docs/calendar-doctrine/{version}/{classSlug}", name="docs_calendar_doctrine_class")
     */
    public function calendarDoctrineClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->calendarDoctrineClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar-doctrine',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar Doctrine',
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/calendar-doctrine/{version}/{classSlug}/method/{methodSlug}", name="docs_calendar_doctrine_class_method")
     */
    public function calendarDoctrineClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->calendarDoctrineClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar-doctrine',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Doctrine',
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }
}
