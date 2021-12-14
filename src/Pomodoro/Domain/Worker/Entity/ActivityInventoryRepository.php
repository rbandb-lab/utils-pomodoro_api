<?php

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;

interface ActivityInventoryRepository
{
    public function get(string $id): ?ActivityInventory;

    public function save(ActivityInventoryInterface $inventory): void;

    public function addTodoTaskToWorker(string $workerId, TodoTask $task): void;

    public function addUnplannedTaskToWorker(string $workerId, UnplannedTask $task): void;
}
