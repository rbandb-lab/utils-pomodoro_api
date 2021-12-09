<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddCalendarTask;

interface AddCalendarTaskPresenter
{
    public function present(AddCalendarTaskResponse $response): void;
}
