<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StopTimer;

interface StopTimerPresenter
{
    public function present(StopTimerResponse $response): void;
}
