<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\TodoTaskList as OrmTodoTaskList;

final class OrmTodoTaskListFactory
{
    public static function toOrm(TodoTaskList $todoTaskList, ActivityInventory $inventory): OrmTodoTaskList
    {
        $tasks = $todoTaskList->getTasks();
        return  new OrmTodoTaskList(
            $todoTaskList->getId(),
            new ArrayCollection($tasks),
            $inventory
        );
    }
}
