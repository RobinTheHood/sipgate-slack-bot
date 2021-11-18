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

        $dateTimeNow = new \DateTime();
        foreach ($response['items'] as $historyEntry) {
            $dateTime = new \DateTime($historyEntry['created']);
            $dateTime->getTimestamp();
            var_dump(($dateTimeNow->getTimeStamp() - $dateTime->getTimestamp()) / 60 / 60);
        }

        var_dump($response);

        echo '<pre>';
        print_r($response);

        // $users = [];
        // foreach ($response['items'] as $user) {
        // }

        // return $users;
    }

    public function call()
    {
        $options = [
            'json' => [
                'deviceId' => "e7",
                'caller' => "e7",
                'callee' => "+491234567"
            ]
        ];

        $response = $this->sendRequest('sessions/calls', 'POST', $options);
    }
}
