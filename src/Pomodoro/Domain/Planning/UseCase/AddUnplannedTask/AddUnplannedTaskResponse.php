<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

class AddUnplannedTaskResponse
{
    public string $id = '';
    public array $errors = [];
}
