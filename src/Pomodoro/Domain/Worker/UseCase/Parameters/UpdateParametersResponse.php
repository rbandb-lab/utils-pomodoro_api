<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

final class UpdateParametersResponse
{
    public string $workerId;
    public array $parameters = [];
}
