<?php

declare(strict_types=1);

namespace App\Tests\Action;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LoginActionTest extends WebTestCase
{
    public function testLoginPage()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/login');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Please sign in")')->count());
    }

    public function testLogin()
    {
        $this->client->request(Request::METHOD_GET, '/login');

        $this->client->submitForm('Sign in', [
            '_username' => 'user1@example.com',
            '_password' => 'Secret@1',
        ]);

        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Welcome, Foo")')->count());
    }
}
