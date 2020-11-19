<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class BookControllerTest extends WebTestCase
{
    protected function setUp()
	{
		$mock = new MockHandler([new Response(200, [])]);
		$handler = HandlerStack::create($mock);
		$this->client = new Client(['handler' => $handler]);
	}

    public function testGetBooks()
    {
        $response = $this->client->get('/api/books', [
            'auth' => [
                'tony_admin',
                'admin',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetBook()
    {
        $response = $this->client->get('/api/books/1', [
            'auth' => [
                'tony_admin',
                'admin',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostBook()
    {
        $response = $this->client->post('/api/books', [
            'auth' => [
                'tony_admin',
                'admin',
            ],
            'json' => [
                'title' => 'my Book',
                'price' => '29.99'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testPutBook()
    {
        $response = $this->client->put('/api/books/6', [
            'auth' => [
                'tony_admin',
                'admin',
            ],
            'json' => [
                'title' => 'my Book PHP',
                'price' => '19.99'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteBook()
    {
        $response = $this->client->delete('/api/books/8', [
            'auth' => [
                'tony_admin',
                'admin',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
