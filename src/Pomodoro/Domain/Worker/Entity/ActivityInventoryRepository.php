<?php

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Planning\Model\TodoTaskListInterface;

interface ActivityInventoryRepository
{
    public function get(string $id): ?ActivityInventory;

    public function save(ActivityInventoryInterface $inventory): void;

    public function addTodoTaskToWorker(string $workerId, TodoTask $task): void;

    public function getTodoTaskById(string $taskId): ?TodoTask;

    public function saveTodoTask(TodoTask $todoTask): void;

    public function addUnplannedTaskToWorker(string $workerId, UnplannedTask $task): void;

    public function getTodoTaskList(string $inventoryId): TodoTaskListInterface;

    public function getByWorkerId(string $workerId): ?ActivityInventory;
}
