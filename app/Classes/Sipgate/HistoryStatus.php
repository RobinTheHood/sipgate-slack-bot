<?php

declare(strict_types=1);

namespace App\Classes\Sipgate;

class HistoryStatus
{
    private $historyEntryId = '';
    private $status = '';

    public function __construct(string $historyEntryId, string $status)
    {
        $this->historyEntryId = $historyEntryId;
        $this->status = $status;
    }

    public function getHistoryEntryId(): string
    {
        return $this->historyEntryId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
