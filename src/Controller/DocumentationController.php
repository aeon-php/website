<?php

namespace App\Controller;

use App\Documentation\PHPClass;
use PackageVersions\Versions;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use PhpParser\ParserFactory;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Ast\Parser\MemoizingParser;
use Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction'
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
            )
        ]);
    }

    /**
     * @Route("/docs/calendar/{classSlug}", name="docs_calendar_class")
     */
    public function calendarClass(string $classSlug) : Response
    {
        foreach ($this->calendarClassesReflection()  as $reflectionClass) {
            $phpClass = new PHPClass($reflectionClass);

            if ($phpClass->slug() === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'calendar',
                    'version' => Versions::getVersion('aeon-php/calendar')
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/calendar/{classSlug}/method/{methodSlug}", name="docs_calendar_class_method")
     */
    public function calendarClassMethod(string $classSlug, string $methodSlug) : Response
    {
        foreach ($this->calendarClassesReflection()  as $reflectionClass) {
            $phpClass = new PHPClass($reflectionClass);

            if ($phpClass->slug() === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if ($method->slug() === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'calendar',
                            'version' => Versions::getVersion('aeon-php/calendar')
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @return ReflectionClass[]
     */
    private function calendarClassesReflection() : array
    {
        $betterReflection = new BetterReflection();
        $astLocator = ($betterReflection)->astLocator();

        $directoriesSourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator([$this->parameterBag->get('aeon_php_calendar_src')], $astLocator),
            ($betterReflection)->sourceLocator()
        ]);

        $reflector = new ClassReflector($directoriesSourceLocator);

        return $reflector->getAllClasses();
    }
}