<?php

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

use Pomodoro\Presentation\PresenterInterface;

interface ValidateEmailPresenter extends PresenterInterface
{
    public function present(ValidateEmailResponse $response): void;
}
