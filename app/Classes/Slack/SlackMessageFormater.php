<?php

declare(strict_types=1);

namespace App\Classes\Slack;

use App\Classes\Sipgate\HistoryEntry;
use DateTimeZone;

class SlackMessageFormater
{
    private function getSourceName(HistoryEntry $historyEntry): string
    {
        if ($historyEntry->getSourceAlias()) {
            return $historyEntry->getSourceAlias();
        }

        $contact = $historyEntry->getSourceContact();
        if ($contact) {
            return $contact->getName();
        }

        return 'Unbekannt';
    }

    private function getTargetName(HistoryEntry $historyEntry): string
    {
        if ($historyEntry->getTargetAlias()) {
            return $historyEntry->getTargetAlias();
        }

        $contact = $historyEntry->getTargetContact();
        if ($contact) {
            return $contact->getName();
        }

        return 'Unbekannt';
    }

    public function formatHistoryEntry(HistoryEntry $historyEntry): array
    {
        $actionElements = [];

        $dateCreated = $historyEntry->getCreated()->setTimeZone(new DateTimeZone('Europe/Berlin'));
        $formatedDateCreated = $dateCreated->format('d.m.Y H:i');

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

        $sourceName = $this->getSourceName($historyEntry);
        $targetName = $this->getTargetName($historyEntry);

        if ($historyEntry->getDirection() == 'MISSED_INCOMING') {
            $headingText = ':x: Anruf von ' . $historyEntry->getSource() . ' (' . $sourceName . ') verpasst.';
        } elseif ($historyEntry->getDirection() == 'INCOMING') {
            $headingText = ':white_check_mark: Anruf von ' . $historyEntry->getSource() . ' (' . $sourceName . ') angenommen.';
        } else {
            $headingText = ':speech_balloon: Neue Benachrichtigung von sipgate';
        }


        $block = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => $headingText,
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
                        'text' => "*Von Name:*\n" . $this->getSourceName($historyEntry),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An:*\n" . $historyEntry->getTarget(),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*An Name:*\n" . $this->getTargetName($historyEntry),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Wann:*\n" . $formatedDateCreated,
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
