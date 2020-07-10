<?php

declare(strict_types=1);

namespace App\Controller\Documentation;

use App\Controller\CodeReflectionTrait;
use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class CalendarTwigClassController extends AbstractController implements ControllerWithDataProviderInterface
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
     * @Route("/docs/calendar-twig/{version}/{classSlug}", name="docs_calendar_twig_class")
     */
    public function calendarTwigClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->calendarTwigClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar-twig',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Calendar Twig'
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'calendarTwigClass';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->calendarTwigVersions() as $version => $srv) {
            foreach ($this->calendarTwigClasses($version) as $phpClass) {
                $arguments[] = [$version, SlugGenerator::forPHPClass($phpClass)];
            }
        }

        return $arguments;
    }
}
