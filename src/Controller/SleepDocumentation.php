<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SleepDocumentation extends AbstractController
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
     * @Route("/docs/sleep", name="docs_sleep")
     */
    public function sleep() : Response
    {
        return $this->render('documentation/sleep.html.twig', [
            'activeSection' => 'sleep',
            'versions' => $this->sleepVersions(),
        ]);
    }

    /**
     * @Route("/docs/sleep/{version}", name="docs_sleep_version")
     */
    public function sleepVersion(string $version) : Response
    {
        return $this->render('documentation/sleep_version.html.twig', [
            'activeSection' => 'sleep',
            'sleepClasses' => $this->sleepClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/sleep/{version}/{classSlug}", name="docs_sleep_class")
     */
    public function sleepClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->sleepClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'sleep',
                    'githubProjectUrl' => "https://github.com/aeon-php/sleep",
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Sleep',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/sleep/{version}/{classSlug}/method/{methodSlug}", name="docs_sleep_class_method")
     */
    public function sleepClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->sleepClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'sleep',
                            'githubProjectUrl' => "https://github.com/aeon-php/sleep",
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Sleep',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
