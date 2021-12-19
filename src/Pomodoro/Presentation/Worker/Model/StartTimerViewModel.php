<?php

declare(strict_types=1);

namespace Pomodoro\Presentation\Worker\Model;

final class StartTimerViewModel
{
    public array $errors = [];

    public ?string $id = null;

    public ?int $startedAt = null;
}
