<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SipgateApi
{
    private $username;
    private $password;
    private $client;

    public function __construct(?string $username = null, ?string $password = null)
    {
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client(['base_uri' => 'https://api.sipgate.com/v2/']);
    }

    private function sendRequest(string $url, string $method = 'GET', array $options = []): ?array
    {
        $response = $this->client->request($method, $url, array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'auth' => [$this->username, $this->password],
            ],
            $options
        ));

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAllHistoryEntries()
    {
        $response = $this->sendRequest('history');

        var_dump($response);

        echo '<pre>';
        print_r($response);

        // $users = [];
        // foreach ($response['items'] as $user) {
            
        // }

        // return $users;
    }
}