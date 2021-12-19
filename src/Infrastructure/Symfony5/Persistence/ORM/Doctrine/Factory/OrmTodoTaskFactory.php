<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Planning\Entity\TodoTask;

final class OrmTodoTaskFactory
{
    public static function fromDto(array $data): ?TodoTask
    {
        try {
            $task = array_shift($data);
            $todoTask = new TodoTask(
                $task->getId(),
                $task->getName(),
                $task->getCategoryId()
            );

            $todoTask->setStatus($task->getState());

            return $todoTask;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
