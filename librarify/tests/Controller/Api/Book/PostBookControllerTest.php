<?php

namespace App\Tests\Controller\Api\Book;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostBookControllerTest extends WebTestCase
{

    public function testNotAuthorized()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'X-AUTH-TOKEN' => 'LIBRARIFY'
            ],
            '{"title":""}'
        );
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookInvalidData()
    {
        $client = static::createClient();
        $this->sendRequest($client, ['title' => '']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookEmptyData()
    {
        $client = static::createClient();
        $this->sendRequest($client, []);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $client = static::createClient();
        $this->sendRequest($client, ['title' => 'El imperio final']);
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    private function sendRequest(KernelBrowser $client, array $json)
    {
        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => 'LIBRARIFY'
            ],
            json_encode($json)
        );
    }
}
