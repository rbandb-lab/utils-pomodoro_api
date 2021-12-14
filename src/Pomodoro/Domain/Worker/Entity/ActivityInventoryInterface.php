<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Planning\Model\CalendarTaskList;
use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Pomodoro\Domain\Planning\Model\UnplannedTaskList;

interface ActivityInventoryInterface
{
    public function addTodoTask(TodoTask $task): void;

    public function addUnplannedTask(UnplannedTask $task): void;

    public function addCalendarTask(): void;

    public function getTodoTaskList(): TodoTaskList;

    public function getUnplannedTaskList(): UnplannedTaskList;

    public function getCalendarTaskList(): CalendarTaskList;

    public function toArray(): array;

    public function getId(): string;
}
