<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Dto;

class UpdateParametersDto
{
    public string $workerId;
    public ?int $pomodoroDuration = null;
    public ?int $shortBreakDuration = null;
    public ?int $longBreakDuration = null;
    public ?int $startFirstTaskIn = null;
}
