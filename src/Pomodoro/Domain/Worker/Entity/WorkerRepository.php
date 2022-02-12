<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Worker\Model\CycleParameters;

interface WorkerRepository
{
    public function add(Worker $worker): void;

    public function get(string $workerId): ?Worker;

    public function create(Worker $worker): void;

    public function getByUsername(string $username): ?Worker;

    public function remove(Worker $worker): void;

    public function getWorkerCycleParameters(string $workerId): ?CycleParameters;

    public function findTokenByValue(string $token): ?Worker;

    public function updateCycleParametersForWorker(string $workerId, CycleParameters $cycleParameters);

    public function updateWorkerEmailState(Worker $worker);

    public function getWorkers(): array;

    public function findAll();
}
