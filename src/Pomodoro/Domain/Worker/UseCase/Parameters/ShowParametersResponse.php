<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

class ShowParametersResponse
{
    public array $errors = [];
    public array $parameters = [];
}
