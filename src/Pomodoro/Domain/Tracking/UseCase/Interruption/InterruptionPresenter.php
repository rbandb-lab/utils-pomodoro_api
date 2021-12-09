<?php

namespace Pomodoro\Domain\Tracking\UseCase\Interruption;

interface InterruptionPresenter
{
    public function present(InterruptionResponse $response): void;
}
