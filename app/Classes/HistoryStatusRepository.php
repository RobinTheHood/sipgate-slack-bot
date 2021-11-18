<?php

namespace App\Classes;

class HistoryEntryRepository
{
    public function loadAll(): array
    {
        $data = file_get_contents('../../data/HistoryStatusDb.txt');
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
        file_put_contents('../../data/HistoryStatusDb.txt', $data);
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