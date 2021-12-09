<?php

namespace Pomodoro\Domain\Worker\Entity;

interface ActivityInventoryRepository
{
    public function getByWorkerId(string $workerId): ?ActivityInventory;

    public function get(string $id): ?ActivityInventory;

    public function save(ActivityInventory $inventory): void;
}
