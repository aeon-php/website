<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\SlugGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BusinessHoursDocumentation extends AbstractController
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
     * @Route("/docs/business-hours", name="docs_business_hours")
     */
    public function businessHours() : Response
    {
        return $this->render('documentation/business_hours.html.twig', [
            'activeSection' => 'business-hours',
            'versions' => $this->parameterBag->get('aeon_php_business_hours')['versions'],
        ]);
    }

    /**
     * @Route("/docs/business-hours/{version}", name="docs_business_hours_version")
     */
    public function businessHoursVersion(string $version) : Response
    {
        return $this->render('documentation/business_hours_version.html.twig', [
            'activeSection' => 'business-hours',
            'businessHoursClasses' => $this->businessHoursClasses($version),
            'version' => $version,
        ]);
    }

    /**
     * @Route("/docs/business-hours/{version}/{classSlug}", name="docs_business_hours_class")
     */
    public function businessHoursClass(string $version, string $classSlug) : Response
    {
        foreach ($classes = $this->businessHoursClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                return $this->render('documentation/class.html.twig', [
                    'class' => $phpClass,
                    'activeSection' => 'business-hours',
                    'version' => $version,
                    'classes' => $classes,
                    'library' => 'Business Hours',
                ]);
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }

    /**
     * @Route("/docs/business-hours/{version}/{classSlug}/method/{methodSlug}", name="docs_business_hours_class_method")
     */
    public function businessHoursClassMethod(string $version, string $classSlug, string $methodSlug) : Response
    {
        foreach ($classes = $this->businessHoursClasses($version) as $phpClass) {
            if (SlugGenerator::forPHPClass($phpClass) === $classSlug) {
                foreach ($phpClass->methods() as $method) {
                    if (SlugGenerator::forClassMethod($method) === $methodSlug) {
                        return $this->render('documentation/method.html.twig', [
                            'class' => $phpClass,
                            'method' => $method,
                            'activeSection' => 'business-hours',
                            'version' => $version,
                            'classes' => $classes,
                            'library' => 'Calendar Holidays',
                        ]);
                    }
                }

                throw $this->createNotFoundException("Class ". $classSlug . " method " . $methodSlug ." does not exists");
            }
        }

        throw $this->createNotFoundException("Class ". $classSlug . " does not exists");
    }
}
