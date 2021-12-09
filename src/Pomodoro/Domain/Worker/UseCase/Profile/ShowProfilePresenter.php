<?php

namespace Pomodoro\Domain\Worker\UseCase\Profile;

interface ShowProfilePresenter
{
    public function present(ShowProfileResponse $response);
}
