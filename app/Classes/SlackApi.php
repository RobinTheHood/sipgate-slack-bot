<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SlackApi
{
    private $token;
    private $client;

    public function __construct(?string $token = null)
    {
        $this->token = $token;

        $this->client = new Client(['base_uri' => 'https://slack.com/api/']);
    }

    private function sendRequest(string $url, string $method = 'GET', array $options = []): void
    {
        $response = $this->client->request($method, $url, array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ],
            ],
            $options
        ));
    }

    public function sentMessage(string $channel, array $message): void
    {
        $options = [
            'json' => [
                'channel' => $channel,
                'blocks' => $message
            ]
        ];

        $this->sendRequest('chat.postMessage', 'POST', $options);
    }
}
