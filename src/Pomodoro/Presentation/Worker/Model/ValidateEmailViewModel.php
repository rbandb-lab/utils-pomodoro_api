<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

class ValidateEmailViewModel
{
    public array $errors = [];
    public bool $emailValid = false;
}
