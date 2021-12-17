<?php

namespace Pomodoro\Domain\Tracking\UseCase\Interruption;

use Pomodoro\Presentation\PresenterInterface;

interface InterruptionPresenter extends PresenterInterface
{
    public function present(InterruptionResponse $response): void;
}
