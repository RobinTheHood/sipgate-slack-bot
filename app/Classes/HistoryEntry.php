<?php

namespace App\Classes;

use DateTime;

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

    public function getCreated(): \DateTime
    {
        return new \DateTime($this->historyEntryDataArray['created']);
    }

    public function getLastModified(): \DateTime
    {
        return new \DateTime($this->historyEntryDataArray['created']);
    }

    public function getRecordingUrl(): string
    {
        return $this->historyEntryDataArray['recordingUrl'];
    }

    public function getAgeAfterRing(): int
    {
        $dateTimeNow = new \DateTime();
        $dateTimeCreated = $this->getCreated();
        $seconds = $dateTimeNow->getTimeStamp() - $dateTimeCreated->getTimestamp();
        return $seconds;
    }

    public function getAgeAfterHangUp(): int
    {
        $dateTimeNow = new \DateTime();
        $dateTimeModifed = $this->getLastModified();
        $seconds = $dateTimeNow->getTimeStamp() - $dateTimeModifed->getTimestamp();
        return $seconds;
    }

    public function getDuration(): int
    {
        $dateTimeCreated = $this->getCreated();
        $dateTimeModifed = $this->getLastModified();
        $seconds = $dateTimeModifed->getTimeStamp() - $dateTimeCreated->getTimestamp();
        return $seconds;
    }

    public function getSource()
    {
        return $this->historyEntryDataArray['source'];
    }

    public function getSourceAlias()
    {
        return $this->historyEntryDataArray['sourceAlias'];
    }

    public function getTarget()
    {
        return $this->historyEntryDataArray['target'];
    }

    public function getTargetAlias()
    {
        return $this->historyEntryDataArray['targetAlias'];
    }

    public function getResponderAlias()
    {
        return $this->historyEntryDataArray['responderAlias'];
    }
}
