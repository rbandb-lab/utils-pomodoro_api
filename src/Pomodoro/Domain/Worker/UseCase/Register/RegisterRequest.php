<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Register;

final class RegisterRequest
{
    public string $email;
    public string $firstName;
    public string $password;

    public ?string $id = null;
    public ?int $pomodoroDuration = null;
    public ?int $shortBreakDuration = null;
    public ?int $longBreakDuration = null;
    public ?int $startFirstTaskAfter = null;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function withFirstNameAndPassword(string $firstName, string $password): self
    {
        $this->firstName = $firstName;
        $this->password = $password;

        return $this;
    }
}
