<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\Model;

use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Tracking\Entity\Interruption;

interface TodoTaskListInterface extends TaskList
{
    public function startTask(TodoTaskInterface $todoTask): void;

    public function recordInterruption(Interruption $interruption): void;
}
