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

    public function getAllHistoryEntries(): array
    {
        $response = $this->sendRequest('history');
        //$response = $this->sendRequest('history?types=VOICEMAIL');

        foreach ($response['items'] as $historyEntry) {
            $historyEntries[] = new HistoryEntry($historyEntry);
        }
        return $historyEntries;
    }

    public function call(string $deviceId, string $number): void
    {
        $options = [
            'json' => [
                'deviceId' => $deviceId,
                'caller' => $deviceId,
                'callee' => $number
            ]
        ];

        $response = $this->sendRequest('sessions/calls', 'POST', $options);
    }
}
