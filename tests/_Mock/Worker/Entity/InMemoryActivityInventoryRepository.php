<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Entity;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;

class InMemoryActivityInventoryRepository implements ActivityInventoryRepository
{
    private array $inventories = [];
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
        $this->refresh();
    }

    public function refresh()
    {
        foreach ($this->workerRepository->getWorkers() as $worker) {
            $inventory = $worker->getActivityInventory();
            $this->save($inventory);
        }
    }

    public function get(string $id): ?ActivityInventory
    {
        return array_key_exists($id, $this->inventories) ? $this->inventories[$id] : null;
    }

    public function save(ActivityInventoryInterface $inventory): void
    {
        if (!in_array($inventory->getId(), $this->inventories, true)) {
            $this->inventories[$inventory->getId()] = $inventory;
        }
    }

    public function getByWorkerId(string $workerId): ?ActivityInventory
    {
        $this->refresh();
        $inventories = array_filter($this->inventories, function ($inventory) use ($workerId) {
            return $inventory->getWorkerId() === $workerId;
        });

        if (count($inventories) > 0) {
            return array_shift($inventories);
        }

        return null;
    }

    public function addTodoTaskToWorker(string $workerId, TodoTask $task): void
    {
        $inventory = $this->getByWorkerId($workerId);
        $todoTaskList = $inventory->getTodoTaskList();
        $todoTaskList->addTask($task);
    }

    public function addUnplannedTaskToWorker(string $workerId, UnplannedTask $task): void
    {
        $inventory = $this->getByWorkerId($workerId);
        $unplannedList = $inventory->getUnplannedTaskList();
        $unplannedList->addTask($task);
    }
}
