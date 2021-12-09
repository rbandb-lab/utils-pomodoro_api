<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Service;

interface SchedulerInterface
{
    public function buildSchedule(): void;

    public function currentTask();
}
