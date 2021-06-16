<?php

namespace App\Service\Isbn;

use App\Model\Dto\Isbn\GetBookByIsbnResponse;
use App\Service\Utils\HttpClientInterface;
use Exception;

class GetBookByIsbn 
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function __invoke(string $isbn): GetBookByIsbnResponse
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('https://openlibrary.org/isbn/%s.json', $isbn)
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new Exception('Error recuperando el libro');
        }
        $content = $response->getContent();
        $json = json_decode($content, true);
        return new GetBookByIsbnResponse(
            $json['title'],
            $json['number_of_pages'],
            $json['publish_date']
        );
    }
}