<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

final class AddUnplannedTaskRequest
{
    public string $id;
    public string $workerId;
    public string $name;
    public ?\DateTime $deadline = null;
    public ?string $categoryId = '';
    public bool $urgent = false;

    public function withWorkerId(string $id, string $workerId, string $name, ?\DateTime $deadline = null): self
    {
        $this->id = $id;
        $this->workerId = $workerId;
        $this->name = $name;
        $this->deadline = $deadline;

        return $this;
    }
}
