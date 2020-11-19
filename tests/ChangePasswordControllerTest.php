<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ChangePasswordControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $mock = new MockHandler([new Response(200, [])]);
        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
	}

    public function testChangePassword()
    {
        $response = $this->client->post('/api/changePassword', [
            'auth' => [
                'tony_admin',
                'admin'
            ],
            'json' => [
                'email' => 'tony_admin@example.com',
                'password' => '0123456789',
                'token' => 'ab345566cdfghjikl1234567890',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
