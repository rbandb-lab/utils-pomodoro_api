<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Worker\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory as OrmInventory;

final class OrmInventoryFactory
{
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
