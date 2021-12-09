<?php

declare(strict_types=1);

namespace Pomodoro\SharedKernel\Service;

interface PasswordHasher
{
    public function hash(string $password): string;

    public function verify(string $hash, string $password): bool;
}
