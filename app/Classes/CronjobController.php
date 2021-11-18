<?php

namespace App\Classes;

class CronjobController
{
    public function run()
    {
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
        $sipgateApi = new SipgateApi();
        $historyEntries = $sipgateApi->getAllHistoryEntries();

        $historyFilter = new HistoryEntryFilter();
        $newHistoryEntries = $historyFilter->filterNewUnsendEntries($historyFilter);

        $messageFormater = new SlackMessageFormater();
        $slackApi = new SlackApi();

        foreach ($newHistoryEntries as $newHistoryEntry) {
            $slackMessage = $messageFormater->formatHistoryEntry($newHistoryEntry);
            $slackApi->sentMessage($this->slackChannel, $slackMessage);

            $historyStatus = new HistoryStatus($newHistoryEntry->getId(), 'SEND_TO_SLACK');
            $historyStatusRepository->save($historyStatus);
        }
    }
}
