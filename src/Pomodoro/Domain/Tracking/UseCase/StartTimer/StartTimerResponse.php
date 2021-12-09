<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StartTimer;

final class StartTimerResponse
{
    public ?int $startedAt = null;
    public array $errors = [];
}
