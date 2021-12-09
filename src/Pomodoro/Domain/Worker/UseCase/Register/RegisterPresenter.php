<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Register;

interface RegisterPresenter
{
    public function present(RegisterResponse $response): void;
}
