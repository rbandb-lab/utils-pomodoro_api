<?php

declare(strict_types=1);

namespace PomodoroTests\_Mock\Worker\Entity;

use Pomodoro\Domain\Worker\Entity\AbstractToken;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use function PHPUnit\Framework\assertInstanceOf;

class InMemoryWorkerRepository implements WorkerRepository
{
    private array $workers = [];

    public function save(Worker $worker): void
    {
        if (!in_array($worker->getId(), $this->workers, true)) {
            $this->workers[$worker->getId()] = $worker;
        }
        assertInstanceOf(Worker::class, $this->workers[$worker->getId()]);
    }

    public function getByUsername(string $username): ?Worker
    {
        $result = array_filter($this->workers, function (Worker $worker) use ($username) {
            return $worker->getUsername() === trim($username);
        });

        return count($result) === 0 ? null : array_shift($result);
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
        if (!in_array($worker->getId(), $this->workers, true)) {
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

            if (count($found) > 0) {
                return $worker;
            }
        }

        return null;
    }

    public function updateCycleParametersForWorker(string $workerId, CycleParameters $cycleParameters)
    {
        $worker = $this->workers[$workerId];
        $worker->setPomodoroDuration($cycleParameters->getPomodoroDuration());
        $worker->setShortBreakDuration($cycleParameters->getShortBreakDuration());
        $worker->setLongBreakDuration($cycleParameters->getLongBreakDuration());
        $worker->setStartFirstTaskIn($cycleParameters->getStartFirstTaskIn());

        $this->workers[$worker->getId()] = $worker;
    }

    public function create(Worker $worker): void
    {
        $this->workers[$worker->getId()] = $worker;
    }

    public function getWorkerCycleParameters(string $workerId): ?CycleParameters
    {
        $worker = $this->workers[$workerId];
        return $worker->getParameters();
    }

    public function updateWorkerEmailState(Worker $worker)
    {
        // TODO: Implement updateWorkerEmailState() method.
    }

    /**
     * @return array
     */
    public function getWorkers(): array
    {
        return $this->workers;
    }

    public function findAll()
    {
        return $this->workers;
    }
}
