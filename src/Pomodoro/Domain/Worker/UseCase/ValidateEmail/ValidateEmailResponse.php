<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

class ValidateEmailResponse
{
    public ?string $id = null;

    public bool $emailValid = false;

    public array $errors = [];

    public array $events = [];
}
