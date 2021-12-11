<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Register;

use Pomodoro\Presentation\PresenterInterface;

interface RegisterPresenter extends PresenterInterface
{
    public function present(RegisterResponse $response): void;

    public function handleEvents(array $events);
}
