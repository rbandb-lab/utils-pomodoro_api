<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\Interruption;

final class InterruptionRequest
{
    public string $workerId = '';
    public string $taskId = '';
    public bool $urgent = false;
    public string $type = 'internal';
    public string $newTaskName = '';
    public ?\DateTime $deadline = null;
    public ?string $category = null;

    public function withWorkerIdAsUnplanned(
        string  $workerId,
        bool    $urgent,
        string  $type,
        ?string $newTaskName = ''
    ): void {
        $this->workerId = $workerId;
        $this->urgent = $urgent;
        $this->type = $type;
        $this->newTaskName = $newTaskName;
    }
}
