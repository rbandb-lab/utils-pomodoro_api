<?php

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

interface AddUnplannedTaskPresenter
{
    public function present(AddUnplannedTaskResponse $response): void;
}
