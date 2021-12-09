<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

class ValidateEmailRequest
{
    public string $token;
    public string $workerId;
}
