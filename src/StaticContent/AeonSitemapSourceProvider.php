<?php

declare(strict_types=1);

namespace App\StaticContent;

use App\Controller\CodeReflectionTrait;
use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AeonSitemapSourceProvider implements SourceProvider
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
     * @return Source[]
     */
    public function all() : array
    {
        return [
            new Source('werkspot_sitemap_section_page', ['section' => 'documentation', 'page' => 1]),
            new Source('werkspot_sitemap_section_page', ['section' => 'landing', 'page' => 1]),
        ];
    }
}
