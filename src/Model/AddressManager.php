<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;

class AddressManager extends AbstractManager
{
    public const TABLE = '';

    public function search($housenumber, $street, $postcode)
    {
        $content = [];
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search',
            [
                'query' => [
                    'q' => $housenumber . ' ' . $street,
                    'postcode' => $postcode,
                    'limit' => 1,
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        // $type = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        if ($statusCode === 200) {
            $content = $response->toArray();
            // convert the response (here in JSON) to an PHP array

            return $content;
        }
    }
}
