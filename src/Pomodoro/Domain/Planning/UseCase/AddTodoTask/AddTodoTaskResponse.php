<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

class AddTodoTaskResponse
{
    public ?string $id = '';
    public array $errors = [];
}
