<?php

namespace App\Classes;

class HistoryEntry
{
    private $historyEntryDataArray;

    public function __construct(array $historyEntryDataArray)
    {
        $this->historyEntryDataArray = $historyEntryDataArray;
    }

    public function getId(): string
    {
        return $this->historyEntryDataArray['id'];
    }

    public function getAge(): int
    {
        $dateTimeNow = new \DateTime();
        $dateTime = new \DateTime($this->historyEntryDataArray['created']);
        $seconds = $dateTimeNow->getTimeStamp() - $dateTime->getTimestamp();
        return $seconds;
    }
}