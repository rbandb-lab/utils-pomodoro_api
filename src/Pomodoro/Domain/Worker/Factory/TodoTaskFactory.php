<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskRequest;

class TodoTaskFactory
{
    public static function createFromRequest(AddTodoTaskRequest $request): TodoTask
    {
        return new TodoTask($request->id, $request->name);
    }
}
