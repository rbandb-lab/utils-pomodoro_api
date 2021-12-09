<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\Model\Timer;

final class PomodoroTimer implements Timer
{
    private array $pomodoros;

    private string $taskId;

    private ?Pomodoro $currentPomodoro;

    public function __construct()
    {
        $this->pomodoros = [];
    }

    public function setPomodoros(array $pomodoros): void
    {
        $this->pomodoros = $pomodoros;
    }

    public function setTaskId(string $taskId): void
    {
        $this->taskId = $taskId;
    }

    public function setCurrentPomodoro(?Pomodoro $currentPomodoro): void
    {
        $this->currentPomodoro = $currentPomodoro;
    }

    public function addPomodoro(Pomodoro $pomodoro)
    {
    }

    public function getBreak(): int
    {
        return 0 === count($this->pomodoros) % 4 ? 25 : 5;
    }

    public function getRemainingTime(): int
    {
        return 0;
    }

    public function setVoid(): void
    {
        // TODO: Implement setVoid() method.
    }

    public function getCurrentState()
    {
        // TODO: Implement getCurrentState() method.
    }

    public function getPomodoros(): array
    {
        return $this->pomodoros;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getCurrentPomodoro(): ?Pomodoro
    {
        return $this->currentPomodoro;
    }
}
