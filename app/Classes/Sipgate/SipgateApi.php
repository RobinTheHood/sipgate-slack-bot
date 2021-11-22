<?php

declare(strict_types=1);

namespace App\Classes\Sipgate;

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

        $historyEntries = [];
        foreach ($response['items'] as $historyEntry) {
            $historyEntries[] = new HistoryEntry($historyEntry);
        }
        return $historyEntries;
    }

    public function getContactsByNumber(string $number): array
    {
        $response = $this->sendRequest('contacts?phonenumbers=' . $number);
        $contacts = [];
        foreach ($response['items'] as $historyEntry) {
            $contacts[] = new Contact($historyEntry);
        }
        return $contacts;
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
