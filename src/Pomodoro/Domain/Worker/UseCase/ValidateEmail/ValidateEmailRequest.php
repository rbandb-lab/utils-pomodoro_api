<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

class ValidateEmailRequest
{
    public string $token;
    public ?string $workerId;

    public function withTokenString(string $token): self
    {
        $this->token = $token;
        $this->workerId = null;
        return $this;
    }
}
