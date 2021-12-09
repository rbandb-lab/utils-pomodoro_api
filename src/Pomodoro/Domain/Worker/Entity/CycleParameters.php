<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

final class CycleParameters
{
    private int $pomodoroDuration;
    private int $shortBreakDuration;
    private int $longBreakDuration;
    private int $startFirstTaskIn;

    public function __construct(
        int $pomodoroDuration,
        int $shortBreakDuration,
        int $longBreakDuration,
        int $startFirstTaskIn
    ) {
        $this->pomodoroDuration = $pomodoroDuration;
        $this->shortBreakDuration = $shortBreakDuration;
        $this->longBreakDuration = $longBreakDuration;
        $this->startFirstTaskIn = $startFirstTaskIn;
    }

    public function getPomodoroDuration(): int
    {
        return $this->pomodoroDuration;
    }

    public function getShortBreakDuration(): int
    {
        return $this->shortBreakDuration;
    }

    public function getLongBreakDuration(): int
    {
        return $this->longBreakDuration;
    }

    public function getStartFirstTaskIn(): int
    {
        return $this->startFirstTaskIn;
    }

    public function toArray()
    {
        return [
            'pomodoroDuration' => $this->pomodoroDuration,
            'shortBreakDuration' => $this->shortBreakDuration,
            'longBreakDuration' => $this->longBreakDuration,
            'startFirstTaskIn' => $this->startFirstTaskIn,
        ];
    }
}
