<?php

namespace App\Tests\Action;

use App\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HomeActionTest extends WebTestCase
{
    public function testHomepage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Symfony Core App")')->count());
    }
}
