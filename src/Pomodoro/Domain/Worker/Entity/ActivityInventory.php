<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\Entity;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Planning\Model\CalendarTaskList;
use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Pomodoro\Domain\Planning\Model\UnplannedTaskList;

final class ActivityInventory implements ActivityInventoryInterface
{
    private string $id;
    private string $workerId;
    private TodoTaskList $todoTaskList;
    private UnplannedTaskList $unplannedTaskList;
    private CalendarTaskList $calendarTaskList;

    public function __construct(string $id, string $workerId)
    {
        $this->id = $id;
        $this->workerId = $workerId;
        $this->todoTaskList = new TodoTaskList();
        $this->unplannedTaskList = new UnplannedTaskList();
        $this->calendarTaskList = new CalendarTaskList();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addUnplannedTask(UnplannedTask $task): void
    {
        $unplannedList = $this->getUnplannedTaskList();
        $unplannedList->addTask($task);
    }

    public function addTodoTask(TodoTask $task): void
    {
        $todoList = $this->getTodoTaskList();
        $todoList->addTask($task);
    }

    public function getTodoTaskList(): TodoTaskList
    {
        return $this->todoTaskList;
    }

    public function getUnplannedTaskList(): UnplannedTaskList
    {
        return $this->unplannedTaskList;
    }

    public function getCalendarTaskList(): CalendarTaskList
    {
        return $this->calendarTaskList;
    }

    public function addCalendarTask(): void
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'workerId' => $this->workerId,
            'todoTaskList' => $this->getTodoTaskList()->toArray(),
            'unplannedTaskList' => $this->getUnplannedTaskList()->toArray(),
            'calendarTaskList' => $this->getCalendarTaskList()->toArray(),
        ];
    }
}
