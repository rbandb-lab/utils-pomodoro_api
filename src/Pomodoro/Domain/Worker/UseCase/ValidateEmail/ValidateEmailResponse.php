<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

class ValidateEmailResponse
{
    public bool $emailValid = false;

    public array $errors;
}
