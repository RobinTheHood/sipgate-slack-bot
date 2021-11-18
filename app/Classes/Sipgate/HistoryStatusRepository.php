<?php

declare(strict_types = 1);

namespace App\Classes\Sipgate;

class HistoryStatusRepository
{
    private const DB_FILE_PATH = __DIR__ . '/../../../data/HistoryStatusDb.txt';

    public function loadAll(): array
    {
        if (!file_exists(self::DB_FILE_PATH)) {
            return [];
        }

        $data = file_get_contents(self::DB_FILE_PATH);
        return unserialize($data);
    }

    public function save(HistoryStatus $histroyStatus): void
    {
        $entries = $this->loadAll();
        $entries[] = $histroyStatus;
        $this->saveAll($entries);
    }

    public function saveAll(array $histroyStatusEntries): void
    {
        $data = serialize($histroyStatusEntries);
        file_put_contents(self::DB_FILE_PATH, $data);
    }

    public function getAllByHistoryEntryId(string $historyEntryId)
    {
        $entries = $this->loadAll();
        $filteredEntries = [];
        foreach ($entries as $entry) {
            if ($entry->getHistoryEntryId() == $historyEntryId) {
                $filteredEntries[] = $entry;
            }
        }
        return $filteredEntries;
    }
}
