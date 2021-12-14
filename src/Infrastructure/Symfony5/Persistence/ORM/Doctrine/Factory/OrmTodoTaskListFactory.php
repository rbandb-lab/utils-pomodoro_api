<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory;
use Symfony5\Persistence\ORM\Doctrine\Entity\TodoTaskList as OrmTodoTaskList;

final class OrmTodoTaskListFactory
{
    public static function fromOrm(OrmTodoTaskList $ormTaskList): TodoTaskList
    {
        $todoTaskList = new TodoTaskList($ormTaskList->getId());
        $todoTaskList->setTasks($ormTaskList->getTaskArray());
        return $todoTaskList;
    }

    public static function toOrm(TodoTaskList $todoTaskList, ActivityInventory $inventory): OrmTodoTaskList
    {
        return new OrmTodoTaskList(
            $todoTaskList->getId(),
            $inventory
        );
    }
}
