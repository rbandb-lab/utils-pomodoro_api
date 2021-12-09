<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

class ShowParametersRequest
{
    public string $workerId;

    public function withWorkerId(string $workerId): self
    {
        $this->workerId = $workerId;

        return $this;
    }
}
