<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

interface WorkerRepository
{
    public function add(Worker $worker): void;

    public function get(string $workerId): ?Worker;

    public function save(Worker $worker): void;

    public function getByUsername(string $username): ?Worker;

    public function getAll(): array;

    public function remove(Worker $worker): void;

    public function findTokenByValue(string $workerId, string $token): ?AbstractToken;
}
