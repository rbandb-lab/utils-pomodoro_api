<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Factory;

use Pomodoro\Domain\Planning\Model\CalendarTaskList;
use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Pomodoro\Domain\Planning\Model\UnplannedTaskList;
use Pomodoro\Domain\Worker\Entity\ActivityInventory;

final class ActivityInventoryFactory
{
    public static function create(
        string $id,
        string $workerId,
        string $unplannedTaskListId,
        string $calendarTaskListId,
        string $todoTaskListId
    ): ActivityInventory {
        $inventory = new ActivityInventory(
            id: $id,
            workerId: $workerId
        );

        $inventory->setUnplannedTaskList(new UnplannedTaskList($unplannedTaskListId));
        $inventory->setCalendarTaskList(new CalendarTaskList($calendarTaskListId));
        $inventory->setTodoTaskList(new TodoTaskList($todoTaskListId));
        return $inventory;
    }
}
