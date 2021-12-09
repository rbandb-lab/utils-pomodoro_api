<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StartTimer;

final class StartTimerRequest
{
    public string $workerId = '';
    public string $taskId = '';

    public function withTaskId(string $workerId, string $taskId): self
    {
        $this->workerId = $workerId;
        $this->taskId = $taskId;

        return $this;
    }
}
