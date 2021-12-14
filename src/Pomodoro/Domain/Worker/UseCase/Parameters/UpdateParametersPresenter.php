<?php

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Presentation\PresenterInterface;

interface UpdateParametersPresenter extends PresenterInterface
{
    public function present(UpdateParametersResponse $response): void;
}
