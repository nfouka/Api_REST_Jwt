<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RegistrationControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $mock = new MockHandler([new Response(200, [])]);
        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
	}

    public function testRegister()
    {
        $response = $this->client->post('/api/register', [
            'auth' => [
                'tony_admin',
                'admin'
            ],
            'json' => [
                'name'  => 'tony',
                'surname' => 'master',
                'username' => 'tony_admin',
                'email' => 'tony_admin@example.com',
                'password' => '0123456',
                'role' => ['ROLE_ADMIN'],
                'token' => 'ab345566cdfghjikl1234567890',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
