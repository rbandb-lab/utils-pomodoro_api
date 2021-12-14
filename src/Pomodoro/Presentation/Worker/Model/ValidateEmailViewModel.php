<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

final class ValidateEmailViewModel
{
    public ?string $id = null;
    public array $errors = [];
    public bool $emailValidated = false;
}
