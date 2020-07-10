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

final class CalendarDoctrineClassController extends AbstractController implements ControllerWithDataProviderInterface
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
                    'library' => 'Calendar Doctrine'
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
        return 'calendarDoctrineClass';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->calendarDoctrineVersions() as $version => $srv) {
            foreach ($this->calendarDoctrineClasses($version) as $phpClass) {
                $arguments[] = [$version, SlugGenerator::forPHPClass($phpClass)];
            }
        }

        return $arguments;
    }
}
