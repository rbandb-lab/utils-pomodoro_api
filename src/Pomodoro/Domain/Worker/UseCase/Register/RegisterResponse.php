<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Register;

class RegisterResponse
{
    public array $errors = [];
    public string $workerId = '';
    public array $events = [];
    public string $token = '';

    public function withId(string $id, array $events): self
    {
        $this->workerId = $id;
        $this->events = $events;

        return $this;
    }
}
