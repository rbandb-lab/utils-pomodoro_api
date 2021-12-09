<?php

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

interface UpdateParametersPresenter
{
    public function present(UpdateParametersResponse $response): void;
}
