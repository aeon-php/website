<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SymfonyBundleDocumentation extends AbstractController
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
     * @Route("/docs/symfony-bundle", name="docs_symfony_bundle")
     */
    public function symfonyBundle() : Response
    {
        return $this->render('documentation/symfony_bundle.html.twig', [
            'activeSection' => 'symfony-bundle',
            'versions' => $this->symfonyBundleVersions(),
        ]);
    }

    /**
     * @Route("/docs/symfony-bundle/{version}", name="docs_symfony_bundle_version")
     */
    public function symfonyBundleVersion(string $version) : Response
    {
        return $this->render('documentation/symfony_bundle_version.html.twig', [
            'activeSection' => 'symfony-bundle',
            'symfonyBundleClasses' => $this->symfonyBundleClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/symfony-bundle/{version}/{classSlug}", name="docs_symfony_bundle_class")
     */
    public function symfonyBundleClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->symfonyBundleClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'symfony-bundle',
                    'githubProjectUrl' => "https://github.com/aeon-php/symfony-bundle",
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Symfony Bundle',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/symfony-bundle/{version}/{classSlug}/method/{methodSlug}", name="docs_symfony_bundle_class_method")
     */
    public function calendarTwigClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->symfonyBundleClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'symfony-bundle',
                            'githubProjectUrl' => "https://github.com/aeon-php/symfony-bundle",
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Symfony Bundle',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
