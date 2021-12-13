<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory as OrmInventory;

final class OrmInventoryFactory
{
    public static function fromOrm(OrmInventory $ormInventory): ActivityInventory
    {
        $todoTaskList = OrmTodoTaskListFactory::fromOrm($ormInventory->getTodoTaskList());
        $calendarTaskList = OrmCalendarTaskListFactory::fromOrm($ormInventory->getCalendarTaskList());
        $unplannedTaskList = OrmUnplannedTaskListFactory::fromOrm($ormInventory->getUnplannedTaskList());

        $inventory = new ActivityInventory(
            $ormInventory->getId(),
            $ormInventory->getWorkerId(),
        );

        $inventory->setTodoTaskList($todoTaskList);
        $inventory->setCalendarTaskList($calendarTaskList);
        $inventory->setUnplannedTaskList($unplannedTaskList);

        return  $inventory;
    }

    public static function toOrm(ActivityInventory $activityInventory): OrmInventory
    {
        $ormInventory = new OrmInventory(
            $activityInventory->getId()
        );

        $ormCalendarList = OrmCalendarTaskListFactory::toOrm(
            $activityInventory->getCalendarTaskList(),
            $ormInventory
        );

        $ormTodoList = OrmTodoTaskListFactory::toOrm(
            $activityInventory->getTodoTaskList(),
            $ormInventory
        );

        $ormUnplannedList = OrmUnplannedTaskListFactory::toOrm(
            $activityInventory->getUnplannedTaskList(),
            $ormInventory
        );

        $ormInventory->setCalendarTaskList($ormCalendarList);
        $ormInventory->setTodoTaskList($ormTodoList);
        $ormInventory->setUnplannedTaskList($ormUnplannedList);

        return $ormInventory;
    }
}
