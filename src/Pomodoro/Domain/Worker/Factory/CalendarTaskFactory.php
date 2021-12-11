<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Planning\Entity\CalendarTask;
use Pomodoro\Domain\Planning\UseCase\AddCalendarTask\AddCalendarTaskRequest;

final class CalendarTaskFactory
{
    public static function createFromRequest(AddCalendarTaskRequest $request): CalendarTask
    {
        return new CalendarTask(
            $request->id,
            $request->name,
            $request->startTs,
            $request->endTs,
        );
    }
}
