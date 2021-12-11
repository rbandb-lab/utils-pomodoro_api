<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation\Dto;

final class RegistrationDto
{
    public string $firstName;
    public string $password;
    public string $email;
    public ?int $pomodoroDuration = null;
    public ?int $shortBreakDuration = null;
    public ?int $longBreakDuration = null;
    public ?int $startFirstTaskIn = null;
}
