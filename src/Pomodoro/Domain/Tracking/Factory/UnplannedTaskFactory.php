<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\Factory;

use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Tracking\UseCase\Interruption\InterruptionRequest;

final class UnplannedTaskFactory
{
    public static function createFromInterruptionRequest(InterruptionRequest $request, string $id): UnplannedTask
    {
        $task = new UnplannedTask(
            $id,
            $request->newTaskName,
            $request->urgent
        );
        if ($request->category !== null) {
            $task->setCategoryId($request->category);
        }

        if ($request->deadline !== null) {
            $task->setDeadline($request->deadline);
        }

        return $task;
    }
}
