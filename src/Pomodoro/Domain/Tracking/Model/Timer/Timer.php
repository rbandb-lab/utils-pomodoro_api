<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\Model\Timer;

interface Timer
{
    public function addPomodoro(Pomodoro $pomodoro);

    public function getBreak(): int;

    public function getRemainingTime(): int;

    public function getCurrentState();

    public function setVoid(): void;
}
