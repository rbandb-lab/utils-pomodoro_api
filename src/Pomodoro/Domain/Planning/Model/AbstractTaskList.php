<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

use Pomodoro\Domain\Planning\Entity\Task;
use Pomodoro\Domain\Planning\Entity\TodoTask;

abstract class AbstractTaskList implements TaskList
{
    protected string $id;

    protected array $tasks = [];

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function addTask(Task $task): void
    {
        if (!in_array($task->getId(), $this->tasks, true)) {
            $this->tasks[$task->getId()] = $task;
        }
    }

    public function removeTask(TodoTask $task): void
    {
        // TODO: Implement removeTask() method.
    }

    public function toArray(): array
    {
        return [
            'tasks' => $this->getTasks(),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
