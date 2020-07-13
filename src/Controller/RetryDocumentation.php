<?php

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RetryDocumentation extends AbstractController
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
     * @Route("/docs/retry", name="docs_retry")
     */
    public function retry() : Response
    {
        return $this->render('documentation/retry.html.twig', [
            'activeSection' => 'retry',
            'versions' => $this->parameterBag->get('aeon_php_retry')['versions'],
        ]);
    }

    /**
     * @Route("/docs/retry/{version}", name="docs_retry_version")
     */
    public function retryVersion(string $version) : Response
    {
        return $this->render('documentation/retry_version.html.twig', [
            'activeSection' => 'retry',
            'retryClasses' => $this->retryClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/retry/{version}/{classSlug}", name="docs_retry_class")
     */
    public function retryClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->retryClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'retry',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Retry'
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/retry/{version}/{classSlug}/method/{methodSlug}", name="docs_retry_class_method")
     */
    public function retryClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->retryClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'retry',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Retry'
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }
}