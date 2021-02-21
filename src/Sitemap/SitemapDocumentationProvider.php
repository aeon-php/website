<?php

declare(strict_types=1);

namespace App\Sitemap;

use App\StaticContent\AeonDocsSourceProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Werkspot\Bundle\SitemapBundle\Provider\AbstractSinglePageSitemapProvider;
use Werkspot\Bundle\SitemapBundle\Sitemap\SitemapSectionPage;
use Werkspot\Bundle\SitemapBundle\Sitemap\Url;

final class SitemapDocumentationProvider extends AbstractSinglePageSitemapProvider
{
    private AeonDocsSourceProvider $provider;

    public function __construct(UrlGeneratorInterface $urlGenerator, AeonDocsSourceProvider $provider)
    {
        parent::__construct($urlGenerator);
        $this->urlGenerator->setContext(new RequestContext('', 'GET', 'aeon-php.org', 'https'));
        $this->provider = $provider;
    }

    public function getSectionName() : string
    {
        return 'documentation';
    }

    public function getSinglePage() : SitemapSectionPage
    {
        $page = new SitemapSectionPage();

        $page->addUrl(new Url($this->generateUrl('docs_calendar'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_calendar_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_sleep'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_sleep_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_retry'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_retry_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_rate_limiter'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_rate_limiter_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_calendar_holidays'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_calendar_holidays_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_calendar_holidays_yasumi'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_calendar_holidays_yasumi_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_calendar_doctrine'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_calendar_doctrine_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_calendar_twig'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_calendar_twig_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_business_hours'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_business_hours_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        $page->addUrl(new Url($this->generateUrl('docs_symfony_bundle'), Url::CHANGEFREQ_WEEKLY, 1.0));
        $page->addUrl(new Url($this->generateUrl('docs_symfony_bundle_version', ['version' => '1.x']), Url::CHANGEFREQ_WEEKLY, 1.0));

        foreach ($this->provider->all() as $source) {
            $route = $this->generateUrl($source->routerName(), $source->parameters());
            $page->addUrl(new Url($route, Url::CHANGEFREQ_DAILY, 1));
        }

        return $page;
    }
}
