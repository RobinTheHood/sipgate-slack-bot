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

    public function run()
    {
        $this->task();
        die();

        $cronJobInterval = 60 * 5;
        $wantedInterval = 30;
        $iterations = $cronJobInterval / $wantedInterval;

        for ($i = 0; $i < $iterations; $i++) {
            $this->task();
            sleep($wantedInterval);
        }
    }

    private function task(): void
    {
        $sipgateApi = new SipgateApi(
            Config::SIPGATE_API_USERNAME,
            Config::SIPGATE_API_PASSWORD
        );

        $historyEntries = $sipgateApi->getAllHistoryEntries();

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
            //die();
            $historyStatus = new HistoryStatus($historyEntry->getId(), 'SEND_TO_SLACK');
            $historyStatusRepository = new HistoryStatusRepository();
            $historyStatusRepository->save($historyStatus);
        }
    }
}
