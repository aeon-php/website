<?php

declare(strict_types=1);

namespace App\Controller\Documentation;

use App\Controller\CalendarReflectionTrait;
use PackageVersions\Versions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class CalendarClassController extends AbstractController implements ControllerWithDataProviderInterface
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
     * @Route("/docs/calendar/{classSlug}", name="docs_calendar_class")
     */
    public function calendarClass(string $classSlug) : Response
    {
        foreach ($this->calendarClassesReflection()  as $phpClass) {
            if ($phpClass->slug() === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar',
                    'version' => Versions::getVersion('aeon-php/calendar'),
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
        return 'calendarClass';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->calendarClassesReflection()  as $phpClass) {
            $arguments[] = [$phpClass->slug()];
        }

        return $arguments;
    }
}
