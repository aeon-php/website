<?php

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RateLimiterController extends AbstractController
{
    use CodeReflectionTrait;

    private ParameterBagInterface  $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    protected function parameterBag(): ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @Route("/docs/rate-limiter", name="docs_rate_limiter")
     */
    public function rateLimiter(): Response
    {
        return $this->render('documentation/rate_limiter.html.twig', [
                'activeSection' => 'rate-limiter',
                'versions' => $this->rateLimiterVersions(),
            ]
        );
    }

    /**
     * @Route("/docs/rate-limiter/{version}", name="docs_rate_limiter_version")
     */
    public function rateLimiterVersion(string $version): Response
    {
        return $this->render('documentation/rate_limiter_version.html.twig', [
            'activeSection' => 'rate-limiter',
            'rateLimiterClasses' => $this->rateLimiterClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/rate-limiter/{version}/{classSlug}", name="docs_rate_limiter_class")
     */
    public function rateLimiterClass(string $version, string $classSlug): Response
    {
        foreach ($classes = $this->rateLimiterClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'rate-limiter',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Rate Limiter',
                ]);
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }

    /**
     * @Route("/docs/rate-limiter/{version}/{classSlug}/method/{methodSlug}", name="docs_rate_limiter_class_method")
     */
    public function rateLimiterClassMethod(string $version, string $classSlug, string $methodSlug): Response
    {
        foreach ($classes = $this->rateLimiterClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'rate-limiter',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Rate Limiter',
                        ]);
                    }
                }

                throw $this->createNotFoundException('Class '.$classSlug.' method '.$methodSlug.' does not exists');
            }
        }

        throw $this->createNotFoundException('Class '.$classSlug.' does not exists');
    }
}
