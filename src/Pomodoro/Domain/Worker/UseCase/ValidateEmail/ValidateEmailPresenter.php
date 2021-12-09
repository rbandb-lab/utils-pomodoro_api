<?php

namespace Pomodoro\Domain\Worker\UseCase\ValidateEmail;

interface ValidateEmailPresenter
{
    public function present(ValidateEmailResponse $response): void;
}
