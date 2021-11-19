<?php

declare(strict_types=1);

namespace App\Classes\Sipgate;

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

    public function getSource(): string
    {
        return $this->historyEntryDataArray['source'] ?? '';
    }

    public function getSourceAlias(): string
    {
        return $this->historyEntryDataArray['sourceAlias'] ?? '';
    }

    public function getTarget(): string
    {
        return $this->historyEntryDataArray['target'] ?? '';
    }

    public function getTargetAlias(): string
    {
        return $this->historyEntryDataArray['targetAlias'] ?? '';
    }

    public function getResponderAlias(): string
    {
        return $this->historyEntryDataArray['responderAlias'] ?? '';
    }
}
