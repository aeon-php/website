<?php

namespace App\Tests\Functional\Web;

use App\Tests\Functional\WebTestCase;

final class DocumentationTest extends WebTestCase
{
    public function test_getting_started_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_getting_started'));

        $this->assertResponseStatusCodeSame(200);
    }

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

    public function test_process_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_process'));

        $this->assertResponseStatusCodeSame(200);
    }

    public function test_retry_page() : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_retry'));

        $this->assertResponseStatusCodeSame(200);
    }
}