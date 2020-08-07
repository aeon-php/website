<?php

namespace App\Tests\Functional\Web;

use App\Tests\Functional\WebTestCase;

final class DocumentationTest extends WebTestCase
{
    public function test_calendar_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_calendar_holidays_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_holidays'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_calendar_holidays_yasumi_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_holidays_yasumi'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_sleep_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_sleep'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_calendar_doctrine_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_doctrine'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_calendar_twig_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_twig'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_retry_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_retry'));

        $this->assertResponseStatusCodeSame(200);
    }
}