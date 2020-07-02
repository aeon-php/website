<?php

declare(strict_types=1);

namespace App\Controller\Documentation;

use App\Controller\CodeReflectionTrait;
use App\Documentation\SlugGenerator;
use PackageVersions\Versions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class ProcessClassController extends AbstractController implements ControllerWithDataProviderInterface
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/docs/process/{classSlug}", name="docs_process_class")
     */
    public function processClass(string $classSlug) : Response
    {
        foreach ($this->codeClassesReflection($this->parameterBag->get('aeon_php_process_src')) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'process',
                    'version' => '1.0@dev', //Versions::getVersion('aeon-php/process'),
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
        return 'processClass';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->codeClassesReflection($this->parameterBag->get('aeon_php_process_src')) as $phpClass) {
            $arguments[] = [SlugGenerator::forPHPClass($phpClass)];
        }

        return $arguments;
    }
}
