<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProcessDocumentation extends AbstractController
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
     * @Route("/docs/process", name="docs_process")
     */
    public function process() : Response
    {
        return $this->render('documentation/process.html.twig', [
            'activeSection' => 'process',
            'versions' => $this->parameterBag->get('aeon_php_process')['versions'],
        ]);
    }

    /**
     * @Route("/docs/process/{version}", name="docs_process_version")
     */
    public function processVersion(string $version) : Response
    {
        return $this->render('documentation/process_version.html.twig', [
            'activeSection' => 'process',
            'processClasses' => $this->processClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/process/{version}/{classSlug}", name="docs_process_class")
     */
    public function processClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->processClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'process',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Process',
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/process/{version}/{classSlug}/method/{methodSlug}", name="docs_process_class_method")
     */
    public function processClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->processClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'process',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Process',
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }
}
