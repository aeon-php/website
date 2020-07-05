<?php

namespace App\Tests\Functional\Web;

use App\Controller\CodeReflectionTrait;
use App\Documentation\SlugGenerator;
use App\Tests\Functional\WebTestCase;

final class DocumentationTest extends WebTestCase
{
    use CodeReflectionTrait;

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

    /**
     * @dataProvider calendar_class_slug_data_provider
     */
    public function test_calendar_class_docs_pages(string $classSlug) : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_class', ['classSlug' => $classSlug]));

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider calendar_holidays_class_slug_data_provider
     */
    public function test_calendar_holidays_class_docs_pages(string $classSlug) : void
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('docs_calendar_holidays_class', ['classSlug' => $classSlug]));

        $this->assertResponseStatusCodeSame(200);
    }

    public function calendar_class_slug_data_provider() : \Generator
    {
        self::bootKernel();

        foreach ($this->codeClassesReflection($this->calendarSrcPath()) as $phpClass) {
            yield [SlugGenerator::forPHPClass($phpClass)];
        }
    }

    public function calendar_holidays_class_slug_data_provider() : \Generator
    {
        self::bootKernel();

        foreach ($this->codeClassesReflection($this->calendarHolidaySrcPath()) as $phpClass) {
            yield [SlugGenerator::forPHPClass($phpClass)];
        }
    }
}