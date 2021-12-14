<?php

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

use Pomodoro\Presentation\PresenterInterface;

interface AddUnplannedTaskPresenter extends PresenterInterface
{
    public function present(AddUnplannedTaskResponse $response): void;
}
