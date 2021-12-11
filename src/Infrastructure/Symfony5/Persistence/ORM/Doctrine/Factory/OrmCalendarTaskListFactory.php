<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Pomodoro\Domain\Planning\Model\CalendarTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\CalendarTaskList as OrmCalendarTaskList;

final class OrmCalendarTaskListFactory
{
    public static function toOrm(CalendarTaskList $calendarTaskList, ActivityInventory $inventory): OrmCalendarTaskList
    {
        $tasks = $calendarTaskList->getTasks();
        return  new OrmCalendarTaskList(
            $calendarTaskList->getId(),
            new ArrayCollection($tasks),
            $inventory
        );
    }
}
