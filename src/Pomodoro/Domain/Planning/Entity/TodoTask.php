<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Entity;

use Pomodoro\Domain\Tracking\Entity\Interruption;
use Pomodoro\Domain\Tracking\Model\Timer\Pomodoro;

final class TodoTask extends Task implements TodoTaskInterface
{
    private ?int $startTask = null;
    private ?int $endTask = null;
    private ?Pomodoro $timer = null;
    private array $pomodoros = [];
    private array $voidTimers = [];
    private array $interruptions = [];

    public function __construct(string $id, string $name, ?string $categoryId = '')
    {
        parent::__construct($id, $name, $categoryId);
    }

    public function start(): void
    {
        $this->timer = new Pomodoro($now = new \DateTime());
        $this->startTask = $this->startTask ?? $now->getTimestamp();
    }

    public function stopTimer(): void
    {
        $now = new \DateTime();
        $timer = $this->timer;
        $timer->setEndTs($now->getTimestamp());
        $this->voidTimers[] = $timer;
        $this->timer = null;
    }

    public function finish(): void
    {
        // TODO: Implement finish() method.
    }

    public function recordInterruption(Interruption $interruption): void
    {
        if (!in_array($interruption->getId(), $this->interruptions, true)) {
            $this->interruptions[$interruption->getId()] = $interruption;
        }
    }

    public function getInterruptions(): array
    {
        return $this->interruptions;
    }

    public function getStartTask(): int
    {
        return $this->startTask;
    }

    public function getEndTask(): int
    {
        return $this->endTask;
    }

    public function getTimer(): ?Pomodoro
    {
        return $this->timer;
    }

    public function getPomodoros(): array
    {
        return $this->pomodoros;
    }
}
