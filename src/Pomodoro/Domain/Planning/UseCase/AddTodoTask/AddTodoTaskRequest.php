<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

class AddTodoTaskRequest
{
    public string $id;
    public string $workerId;
    public string $name;
    public string $categoryId;

    public function withWorkerId(string $id, string $workerId, string $name): self
    {
        $this->id = $id;
        $this->workerId = $workerId;
        $this->name = $name;

        return $this;
    }
}
