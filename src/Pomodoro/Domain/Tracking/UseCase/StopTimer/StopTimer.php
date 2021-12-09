<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StopTimer;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Planning\Model\TodoTaskList;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

final class StopTimer
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(StopTimerRequest $request, StopTimerPresenter $presenter): void
    {
        $response = new StopTimerResponse();

        $worker = $this->workerRepository->get($request->workerId);
        $inventory = $worker->getActivityInventory();
        $todoTasksList = $inventory->getTodoTaskList();
        $task = $this->findTask($todoTasksList, $request->taskId, $response);

        if ($task instanceof TodoTask) {
            $task->stopTimer();
        }

        $presenter->present($response);
    }

    private function findTask(TodoTaskList $taskList, string $id, StopTimerResponse $response): ?TodoTaskInterface
    {
        $foundTasks = array_filter($taskList->getTasks(), function (TodoTask $task) use ($id) {
            return $task->getId() === $id;
        });

        if (count($foundTasks) === 0) {
            $response->errors[] = new Error('taskId', 'not-found');
        }

        return array_shift($foundTasks) ?? null;
    }
}
