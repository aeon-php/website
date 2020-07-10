<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symplify\SymfonyStaticDumper\Contract\ControllerWithDataProviderInterface;

final class RetryDocumentation extends AbstractController implements ControllerWithDataProviderInterface
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

    public function getControllerClass() : string
    {
        return __CLASS__;
    }

    public function getControllerMethod() : string
    {
        return 'retryVersion';
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