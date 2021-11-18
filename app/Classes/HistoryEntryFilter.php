<?php

namespace App\Classes;

class HistoryEntryFilter
{
    public function filterNewUnsendEntries(array $historyEntries): array
    {
        // Younger than 5 minutes
        $filteredHisotryEntires = $this->filterAgeYoungerThan($historyEntries, 60 * 5);
        $filteredHisotryEntires = $this->filterStatusHasNot($historyEntries, 'SEND_TO_SLACK');
        return $filteredHisotryEntires;
    }


    public function filterAgeYoungerThan(array $historyEntries, int $seconds): array
    {
        $filteredHisotryEntires = [];
        foreach ($historyEntries as $historyEntry) {
            $ageInSeconds = $historyEntry->getAge();
            if ($ageInSeconds > $seconds) { // 5 Minutes
                continue;
            }
            $filteredHisotryEntires[] = $historyEntry;
        }
        return $filteredHisotryEntires;
    }

    public function filterStatusHasNot(array $historyEntries, string $status): array
    {
        $historyEntryRepository = new HistoryEntryRepository();

        $filteredHisotryEntires = [];
        foreach ($historyEntries as $historyEntriy) {
            $historyEntryId = 0; // TODO
            $historyStatusEntries = $historyEntryRepository->getAllStatusByHistoryEntryId($historyEntryId);
            if (in_array($status, $historyStatusEntries)) {
                continue;
            }
            $filteredHisotryEntires[] = $historyEntry;
        }
        return $filteredHisotryEntires;
    }
}