<?php

declare(strict_types=1);

namespace App\Sitemap;

use App\StaticContent\AeonDocsSourceProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Werkspot\Bundle\SitemapBundle\Provider\AbstractSinglePageSitemapProvider;
use Werkspot\Bundle\SitemapBundle\Sitemap\SitemapSectionPage;
use Werkspot\Bundle\SitemapBundle\Sitemap\Url;

final class SitemapLandingProvider extends AbstractSinglePageSitemapProvider
{
    private AeonDocsSourceProvider $provider;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        parent::__construct($urlGenerator);
        $this->urlGenerator->setContext(new RequestContext('', 'GET', 'aeon-php.org', 'https'));
    }

    public function getSectionName() : string
    {
        return 'landing';
    }

    public function getSinglePage() : SitemapSectionPage
    {
        $page = new SitemapSectionPage();

        $page->addUrl(new Url($this->generateUrl('landing'), Url::CHANGEFREQ_WEEKLY, 1.0));

        return $page;
    }
}
