<?php

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

interface ShowParametersPresenter
{
    public function present(ShowParametersResponse $response);
}
