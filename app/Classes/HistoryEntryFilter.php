<?php

declare(strict_types = 1);

namespace App\Classes;

class HistoryEntryFilter
{
    public function filterNewUnsendEntries(array $historyEntries): array
    {
        $filteredHisotryEntires = $historyEntries;
        $filteredHisotryEntires = $this->filterAgeYoungerThan($historyEntries, 60 * 5); // Younger than 5 minutes
        $filteredHisotryEntires = $this->filterStatusHasNot($filteredHisotryEntires, 'SEND_TO_SLACK');
        return $filteredHisotryEntires;
    }


    public function filterAgeYoungerThan(array $historyEntries, int $seconds): array
    {
        $filteredHisotryEntires = [];
        foreach ($historyEntries as $historyEntry) {
            $ageInSeconds = $historyEntry->getAgeAfterHangUp();
            if ($ageInSeconds > $seconds) {
                continue;
            }
            $filteredHisotryEntires[] = $historyEntry;
        }
        return $filteredHisotryEntires;
    }

    public function filterStatusHasNot(array $historyEntries, string $status): array
    {
        $filteredHisotryEntires = [];
        foreach ($historyEntries as $historyEntry) {
            if ($this->hasHistoryStatus($historyEntry, $status)) {
                continue;
            }
            $filteredHisotryEntires[] = $historyEntry;
        }
        return $filteredHisotryEntires;
    }

    private function hasHistoryStatus(HistoryEntry $historyEntry, string $status): bool
    {
        $historyEntryRepository = new HistoryStatusRepository();
        $historyEntryId = $historyEntry->getId();
        $historyStatusEntries = $historyEntryRepository->getAllByHistoryEntryId($historyEntryId);
        foreach ($historyStatusEntries as $historyStatusEntry) {
            if ($historyStatusEntry->getStatus() == $status) {
                return true;
            }
        }
        return false;
    }
}
