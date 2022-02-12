<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Planning\Model\CalendarTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\CalendarTaskList as OrmCalendarTaskList;

final class OrmCalendarTaskListFactory
{
    public static function fromOrm(OrmCalendarTaskList $ormCalendarList): CalendarTaskList
    {
        $tasks = $ormCalendarList->getTasksArray();
        $calendarList = new CalendarTaskList($ormCalendarList->getId());
        $calendarList->setTasks($tasks);
        return $calendarList;
    }

    public static function toOrm(CalendarTaskList $calendarTaskList, ActivityInventory $inventory): OrmCalendarTaskList
    {
        return new OrmCalendarTaskList(
            $calendarTaskList->getId(),
            $inventory
        );
    }
}
