<?php

namespace Pomodoro\Domain\Tracking\UseCase\StartTimer;

use Pomodoro\Presentation\PresenterInterface;

interface StartTimerPresenter extends PresenterInterface
{
    public function present(StartTimerResponse $response): void;
}
