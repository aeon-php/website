<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class ProcessDocumentation extends AbstractController implements ControllerWithDataProviderInterface
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

    protected function parameterBag(): ParameterBagInterface
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

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'processVersion';
    }

    public function getArguments() : array
    {
        $arguments = [];
        foreach ($this->retryVersions() as $version => $srv) {
                $arguments[] = [$version];
        }

        return $arguments;
    }
}