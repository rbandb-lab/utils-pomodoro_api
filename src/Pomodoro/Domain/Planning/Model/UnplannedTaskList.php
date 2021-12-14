<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

final class UnplannedTaskList extends AbstractTaskList implements UnplannedTaskListInterface
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setTasks(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
