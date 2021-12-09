<?php

declare(strict_types=1);

namespace Pomodoro\SharedKernel\Service;

interface EmailValidator
{
    public function isValid(string $email): bool;
}
