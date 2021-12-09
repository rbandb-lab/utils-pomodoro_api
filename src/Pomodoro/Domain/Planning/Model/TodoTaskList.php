<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

use Pomodoro\Domain\Planning\Entity\Task;
use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Tracking\Entity\Interruption;

final class TodoTaskList extends AbstractTaskList implements TodoTaskListInterface
{
    private ?TodoTaskInterface $currentTask = null;

    public function recordInterruption(Interruption $interruption): void
    {
        $this->currentTask->recordInterruption($interruption);
    }

    public function addTask(Task $task): void
    {
        if (!in_array($task->getId(), $this->tasks, true)) {
            $this->tasks[$task->getId()] = $task;
        }
    }

    public function startTask(TodoTaskInterface $todoTask): void
    {
        $todoTask->start();
        $this->currentTask = $todoTask;
    }
}
