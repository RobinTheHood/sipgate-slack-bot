<?php

declare(strict_types = 1);

namespace App\Classes;

class SlackMessageFormater
{
    public function formatHistoryEntry(HistoryEntry $historyEntry): array
    {
        $actionElements = [];

        if ($historyEntry->getRecordingUrl()) {
            $actionElements[] = [
                'type' => 'button',
                'text' => [
                    'type' => 'plain_text',
                    'emoji' => true,
                    'text' => 'Nachricht abhören',
                ],
                'url' => $historyEntry->getRecordingUrl()
            ];
        }

        $actionElements[] = [
            'type' => 'button',
            'action_id' => 'call',
            'text' => [
                'type' => 'plain_text',
                'emoji' => true,
                'text' => 'Rückruf',
            ],
            'style' => 'primary',
            'value' => $historyEntry->getSource(),
        ];

        $actionElements[] = [
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'emoji' => true,
                'text' => 'Ansehen',
            ],
            'url' => 'https://app.sipgate.com/history'
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
                        'text' => "*Von:*\n" . $historyEntry->getSource(),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Von Name:*\n" . $historyEntry->getSourceAlias(),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An:*\n" . $historyEntry->getTarget(),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An Name:*\n" . $historyEntry->getTargetAlias(),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Wann:*\n" . $historyEntry->getCreated()->format('d.m.Y H:i'),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Angenommen von:*\n" . $historyEntry->getResponderAlias(),
                    ],
                ],
            ],
            [
                'type' => 'actions',
                'elements' => $actionElements
            ]
        ];

        return $block;
    }
}
