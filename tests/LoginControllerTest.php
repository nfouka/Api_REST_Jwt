<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class LoginControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $mock = new MockHandler([new Response(200, [])]);
        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
	}

    public function testLoginUser()
    {
        $response = $this->client->post('/api/token', [
            'json' => [
                'username' => 'tony_admin',
                'password' => '0123456',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
