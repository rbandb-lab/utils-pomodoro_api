<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Profile;

final class ShowProfileRequest
{
    public string $workerId;

    public function withWorkerId(string $workerId): self
    {
        $this->workerId = $workerId;

        return $this;
    }
}
