<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\PHPClass;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    use CalendarTrait;

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
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
        ]);
    }

    public function navigation(?string $activeSection = null) : Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarClasses' => \array_map(
                function (ReflectionClass $reflectionClass) : PHPClass {
                    return new PHPClass($reflectionClass);
                },
                $this->calendarClassesReflection()
            ),
        ]);
    }
}
