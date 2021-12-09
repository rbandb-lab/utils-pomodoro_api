<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Entity;

use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;

class InMemoryActivityInventoryRepository implements ActivityInventoryRepository
{
    private array $inventories = [];

    public function get(string $id): ?ActivityInventory
    {
        return array_key_exists($id, $this->inventories) ? $this->inventories[$id] : null;
    }

    public function save(ActivityInventory $inventory): void
    {
        if (!in_array($inventory->getId(), $this->inventories)) {
            $this->inventories[$inventory->getId()] = $inventory;
        }
    }

    public function getByWorkerId(string $workerId): ?ActivityInventory
    {
        $inventories = array_filter($this->inventories, function ($inventory) use ($workerId) {
            return $inventory->getWorkerId() === $workerId;
        });

        if (!empty($inventories)) {
            return array_shift($inventories);
        }

        return null;
    }
}
