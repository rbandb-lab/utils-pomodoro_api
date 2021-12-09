<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

use Pomodoro\Domain\Planning\Entity\Task;
use Pomodoro\Domain\Planning\Entity\TodoTask;

interface TaskList
{
    public function addTask(Task $task): void;

    public function removeTask(TodoTask $task): void;

    public function toArray(): array;
}
