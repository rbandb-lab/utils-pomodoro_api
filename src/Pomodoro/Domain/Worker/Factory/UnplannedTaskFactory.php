<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskRequest;

final class UnplannedTaskFactory
{
    public static function createFromRequest(AddUnplannedTaskRequest $request): UnplannedTask
    {
        return new UnplannedTask(
            id: $request->id,
            name: $request->name,
            urgent: $request->urgent,
            categoryId: $request->categoryId ?? '',
            deadline: $request->deadline
        );
    }
}
