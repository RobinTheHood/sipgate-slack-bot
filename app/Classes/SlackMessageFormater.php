<?php

namespace App\Classes;

class SlackMessageFormater
{
    public function formatHistoryEntry(HistoryEntry $historyEntry)
    {
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

        return $block;
    }
}