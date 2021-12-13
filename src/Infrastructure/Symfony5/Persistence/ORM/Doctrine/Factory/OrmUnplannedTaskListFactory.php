<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Planning\Model\UnplannedTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTaskList as OrmUnplannedTaskList;

final class OrmUnplannedTaskListFactory
{
    public static function fromOrm(OrmUnplannedTaskList $ormTaskList): UnplannedTaskList
    {
        $tasks = $ormTaskList->getTasksArray();
        $unplannedTaskList = new UnplannedTaskList($ormTaskList->getId());
        $unplannedTaskList->setTasks($tasks);

        return $unplannedTaskList;
    }

    public static function toOrm(UnplannedTaskList $todoTaskList, ActivityInventory $inventory): OrmUnplannedTaskList
    {
        return  new OrmUnplannedTaskList(
            $todoTaskList->getId(),
            $inventory
        );
    }
}
