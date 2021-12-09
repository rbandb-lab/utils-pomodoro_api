<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StopTimer;

final class StopTimerRequest
{
    public ?string $workerId = null;
    public ?string $taskId = null;

    public function withTaskId(string $workerId, string $taskId): self
    {
        $this->workerId = $workerId;
        $this->taskId = $taskId;

        return $this;
    }
}
