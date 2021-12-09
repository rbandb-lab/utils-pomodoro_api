<?php

namespace Pomodoro\Domain\Tracking\UseCase\StartTimer;

interface StartTimerPresenter
{
    public function present(StartTimerResponse $response): void;
}
