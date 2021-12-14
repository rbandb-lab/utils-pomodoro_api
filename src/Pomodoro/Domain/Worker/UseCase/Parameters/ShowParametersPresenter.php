<?php

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Presentation\PresenterInterface;

interface ShowParametersPresenter extends PresenterInterface
{
    public function present(ShowParametersResponse $response);
}
