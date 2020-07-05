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

final class RetryClassMethodController extends AbstractController implements ControllerWithDataProviderInterface
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/docs/retry/{classSlug}/method/{methodSlug}", name="docs_retry_class_method")
     */
    public function retryClassMethod(string $classSlug, string $methodSlug) : Response
    {
        foreach ($this->codeClassesReflection($this->parameterBag->get('aeon_php_retry_src')) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if ($method->slug() === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'retry',
                            'version' => Versions::getVersion('aeon-php/retry'),
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
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
        return 'retryClassMethod';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->codeClassesReflection($this->parameterBag->get('aeon_php_retry_src')) as $phpClass) {
            foreach ($phpClass->methods() as $method) {
                $arguments[] = [SlugGenerator::forPHPClass($phpClass), $method->slug()];
            }
        }

        return $arguments;
    }
}
