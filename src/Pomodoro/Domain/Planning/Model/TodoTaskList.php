<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

use Pomodoro\Domain\Planning\Entity\Task;
use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Tracking\Entity\Interruption;

final class TodoTaskList extends AbstractTaskList implements TodoTaskListInterface
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

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function recordInterruption(Interruption $interruption): void
    {
        $taskId = $interruption->getTaskId();
        $taskToInterrupt = null;
        foreach ($this->tasks as $task) {
            if ($task->getId() === $taskId) {
                $taskToInterrupt = $task;
            };
        }
        $taskToInterrupt->recordInterruption($interruption);
    }

    public function addTask(Task $task): void
    {
        if (!in_array($task->getId(), $this->tasks, true)) {
            $this->tasks[$task->getId()] = $task;
        }
    }

    public function startTask(TodoTaskInterface $todoTask): void
    {
        $todoTask->start(new \DateTime());
    }

    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }
}
