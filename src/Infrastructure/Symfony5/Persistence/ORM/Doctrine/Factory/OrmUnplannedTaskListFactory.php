<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Pomodoro\Domain\Planning\Model\UnplannedTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\UnplannedTaskList as OrmUnplannedTaskList;

final class OrmUnplannedTaskListFactory
{
    public static function toOrm(UnplannedTaskList $todoTaskList, ActivityInventory $inventory): OrmUnplannedTaskList
    {
        $tasks = $todoTaskList->getTasks();
        return  new OrmUnplannedTaskList(
            $todoTaskList->getId(),
            new ArrayCollection($tasks),
            $inventory
        );
    }
}
