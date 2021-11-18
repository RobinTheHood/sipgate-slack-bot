<?php

namespace App\Classes;

use App\Config\Config;

class CronjobController
{
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
        $newHistoryEntries = $historyFilter->filterNewUnsendEntries($historyEntries);

        var_dump($newHistoryEntries);
        //die();

        //$messageFormater = new SlackMessageFormater();
        //$slackApi = new SlackApi();

        foreach ($newHistoryEntries as $newHistoryEntry) {
            //$slackMessage = $messageFormater->formatHistoryEntry($newHistoryEntry);
            //$slackApi->sentMessage($this->slackChannel, $slackMessage);

            $historyStatus = new HistoryStatus($newHistoryEntry->getId(), 'SEND_TO_SLACK');

            $historyStatusRepository = new HistoryStatusRepository();
            $historyStatusRepository->save($historyStatus);

            break;
        }
    }
}
