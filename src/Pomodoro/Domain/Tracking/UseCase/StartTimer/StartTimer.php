<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\StartTimer;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Planning\Entity\TodoTaskInterface;
use Pomodoro\Domain\Tracking\Model\Timer\Pomodoro;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;

final class StartTimer
{
    private ActivityInventoryRepository $inventoryRepository;
    private WorkerRepository $workerRepository;

    public function __construct(
        ActivityInventoryRepository $inventoryRepository,
        WorkerRepository            $workerRepository
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->workerRepository = $workerRepository;
    }

    public function execute(StartTimerRequest $request, StartTimerPresenter $presenter): void
    {
        $response = new StartTimerResponse();
        $task = $this->findTask($request, $response);
        $cycleParameters = $this->workerRepository->getWorkerCycleParameters($request->workerId);

        if ($task instanceof TodoTask) {
            $now = new \DateTime();

            if ($task->getTimer() instanceof Pomodoro) {
                $response->errors[] = new Error('timer', 'already-started');
            }

            if ($task->getTimer() === null) {
                if (!$task->isStarted()) {
                    $task->start($now);
                };

                $task->setTimer(new Pomodoro($now));
                $task->timerRingsAt($now->getTimestamp() + $cycleParameters->getPomodoroDuration());
                $this->inventoryRepository->saveTodoTask($task);
            }

            $response->startedAt = $task->getStartTask();
            $response->id = $task->getId();
        }

        $presenter->present($response);
    }

    private function findTask(StartTimerRequest $request, StartTimerResponse $response): ?TodoTaskInterface
    {
        $task = $this->inventoryRepository->getTodoTaskById($request->taskId);

        if (!$task instanceof TodoTask) {
            $response->errors[] = new Error('taskId', 'not-found');
            return null;
        }

        return $task;
    }
}
