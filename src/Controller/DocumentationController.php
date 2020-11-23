<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    protected function parameterBag(): ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function index()
    {
        return $this->render('documentation/introduction.html.twig', [
            'activeSection' => 'introduction',
            'calendarClasses' => $this->calendarClasses('1.x'),
            'calendarVersion' => '1.x',
            'calendarTwigClasses' => $this->calendarTwigClasses('1.x'),
            'calendarTwigVersion' => '1.x',
            'calendarDoctrineClasses' => $this->calendarDoctrineClasses('1.x'),
            'calendarDoctrineVersion' => '1.x',
            'calendarHolidaysClasses' => $this->calendarHolidaysClasses('1.x'),
            'calendarHolidaysVersion' => '1.x',
            'calendarHolidaysYasumiClasses' => $this->calendarHolidaysYasumiClasses('1.x'),
            'calendarHolidaysYasumiVersion' => '1.x',
            'sleepClasses' => $this->sleepClasses('1.x'),
            'sleepVersion' => '1.x',
            'retryClasses' => $this->retryClasses('1.x'),
            'retryVersion' => '1.x',
            'rateLimiterClasses' => $this->rateLimiterClasses('1.x'),
            'rateLimiterVersion' => '1.x',
            'symfonyBundle' => $this->symfonyBundleClasses('1.x'),
            'symfonyBundleVersion' => '1.x',
        ]);
    }

    public function navigation(?string $activeSection = null): Response
    {
        return $this->render('documentation/_navigation.html.twig', [
            'activeSection' => $activeSection,
            'calendarVersions' => $this->calendarVersions(),
            'calendarDoctrineVersions' => $this->calendarDoctrineVersions(),
            'calendarTwigVersions' => $this->calendarTwigVersions(),
            'calendarHolidaysVersions' => $this->calendarHolidaysVersions(),
            'calendarHolidaysYasumiVersions' => $this->calendarHolidaysYasumiVersions(),
            'businessHoursVersions' => $this->businessHoursVersions(),
            'sleepVersions' => $this->sleepVersions(),
            'retryVersions' => $this->retryVersions(),
            'rateLimiterVersions' => $this->rateLimiterVersions(),
            'symfonyBundleVersions' => $this->symfonyBundleVersions(),
        ]);
    }
}
