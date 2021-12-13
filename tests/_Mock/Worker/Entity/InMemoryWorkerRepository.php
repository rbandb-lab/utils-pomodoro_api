<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Entity;

use Pomodoro\Domain\Worker\Entity\AbstractToken;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use function PHPUnit\Framework\assertInstanceOf;

class InMemoryWorkerRepository implements WorkerRepository
{
    private array $workers = [];

    private ActivityInventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InMemoryActivityInventoryRepository();
    }

    public function getInventoryRepository(): InMemoryActivityInventoryRepository|ActivityInventoryRepository
    {
        return $this->inventoryRepository;
    }

    public function save(Worker $worker): void
    {
        if (!in_array($worker->getId(), $this->workers, true)) {
            $this->workers[$worker->getId()] = $worker;
            $inventory = $worker->getActivityInventory();
            $this->inventoryRepository->save($inventory);
        }
        assertInstanceOf(Worker::class, $this->workers[$worker->getId()]);
    }

    public function getByUsername(string $username): ?Worker
    {
        $result = array_filter($this->workers, function (Worker $worker) use ($username) {
            return $worker->getUsername() === trim($username);
        });

        return empty($result) ? null : array_shift($result);
    }

    public function getAll(): array
    {
        return $this->workers;
    }

    public function remove(Worker $worker): void
    {
        unset($this->workers[$worker->getId()]);
    }

    public function get(string $workerId): ?Worker
    {
        return $this->workers[$workerId] ?? null;
    }

    public function add(Worker $worker): void
    {
        if (!in_array($worker->getId(), $this->workers)) {
            $this->workers[$worker->getId()] = $worker;
        }
        assertInstanceOf(Worker::class, $this->workers[$worker->getId()]);
    }

    public function findTokenByValue(string $value): ?Worker
    {
        $workers = $this->workers;
        foreach ($workers as $worker) {
            $tokens = $worker->getTokens();
            $found = array_filter($tokens, function (AbstractToken $token) use ($value) {
                return $token->getToken() === $value;
            });

            if (!empty($found)) {
                return $worker;
            }
        }

        return null;
    }
}
