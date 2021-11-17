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

    private function sendRequest(string $url, string $method = 'GET', array $options = []): ?array
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

        var_dump($response);
        die('test2');
    }

    public function sentMessage()
    {
        $options = [
            'json' => [
                'channel' => "#sipgate",
                'text' => "I hope the tour went well, Mr. Wonka."
            ]
        ];

        $block = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => 'Neue Benachrichtigung von sipgate',
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Von:*\n+491234567",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Von Name:*\nMax Mustermann",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An:*\n+497654321",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An Name:*\nZentrale",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Wann:*\n18:40:21 - 17.11.2021",
                    ],
                ],
            ],
            [
                'type' => 'actions',
                'elements' => [
                    [
                        'type' => 'button',
                        'text' => [
                            'type' => 'plain_text',
                            'emoji' => true,
                            'text' => 'Rückruf',
                        ],
                        'style' => 'primary',
                        'value' => 'click_me_123',
                    ],
                    [
                        'type' => 'button',
                        'text' => [
                            'type' => 'plain_text',
                            'emoji' => true,
                            'text' => 'Ansehen',
                        ],
                        'url' => 'https://app.sipgate.com/history'
                    ],
                ]
            ]
        ];

        $options = [
            'json' => [
                'channel' => "#sipgate",
                //'text' => $block
                'blocks' => $block
            ]
        ];

        // {"channel":"C061EG9SL","text":"I hope the tour went well, Mr. Wonka.","attachments":[{"text":"Who wins the lifetime supply of chocolate?","fallback":"You could be telling the computer exactly what it can do with a lifetime supply of chocolate.","color":"#3AA3E3","attachment_type":"default","callback_id":"select_simple_1234","actions":[{"name":"winners_list","text":"Who should win?","type":"select","data_source":"users"}]}]}

        $this->sendRequest('chat.postMessage', 'POST', $options);
    }
}
