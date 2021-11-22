<?php

declare(strict_types=1);

namespace App\Classes\Controller;

use App\Config\Config;
use App\Classes\Sipgate\SipgateApi;
use App\Classes\Sipgate\HistoryEntryFilter;
use App\Classes\Sipgate\HistoryStatus;
use App\Classes\Sipgate\HistoryStatusRepository;
use App\Classes\Slack\SlackMessageFormater;
use App\Classes\Slack\SlackApi;

class CronjobController
{
    private $slackChannel = '#sipgate';
    private $waitTime = 30;

    public function runOnce(): void
    {
        echo "Start runOnce\n";
        $this->task();
        echo "Finished runOnce\n";
    }

    public function runEndless(): void
    {
        echo "Start runEndless\n";
        while (true) {
            $this->task();
            sleep($this->waitTime);
        }
        echo "Finished runEndless\n";
    }

    public function runAsCron(): void
    {
        echo "Start runAsCron\n";

        $cronJobInterval = 60 * 5;
        $iterations = $cronJobInterval / $this->waitTime;

        for ($i = 0; $i < $iterations; $i++) {
            $this->task();
            sleep($this->waitTime);
        }

        echo "Finished runAsCron\n";
    }

    private function task(): void
    {
        echo "Start task\n";
        $sipgateApi = new SipgateApi(
            Config::SIPGATE_API_USERNAME,
            Config::SIPGATE_API_PASSWORD
        );

        $historyEntries = $sipgateApi->getAllHistoryEntries();

        array_reverse($historyEntries);

        $historyFilter = new HistoryEntryFilter();
        $newHistoryEntries = $historyEntries;
        $newHistoryEntries = $historyFilter->filterNewUnsendEntries($newHistoryEntries);

        // echo '<pre>';
        // print_r($newHistoryEntries);
        // die();

        $messageFormater = new SlackMessageFormater();
        $slackApi = new SlackApi(
            Config::SLACK_API_TOKEN
        );

        foreach ($newHistoryEntries as $historyEntry) {
            $slackMessage = $messageFormater->formatHistoryEntry($historyEntry);
            $slackApi->sentMessage($this->slackChannel, $slackMessage);
            $historyStatus = new HistoryStatus($historyEntry->getId(), 'SEND_TO_SLACK');
            $historyStatusRepository = new HistoryStatusRepository();
            $historyStatusRepository->save($historyStatus);
        }
        echo "Finished task\n";
    }
}
