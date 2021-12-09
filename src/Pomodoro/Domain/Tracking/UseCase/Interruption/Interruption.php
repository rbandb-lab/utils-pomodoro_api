<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Tracking\UseCase\Interruption;

use Pomodoro\Domain\Tracking\Entity\ExternalInterruption;
use Pomodoro\Domain\Tracking\Entity\InternalInterruption;
use Pomodoro\Domain\Tracking\Factory\UnplannedTaskFactory;
use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Error\Error;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class Interruption
{
    private IdGenerator $idGenerator;
    private WorkerRepository $workerRepository;

    public function __construct(IdGenerator $idGenerator, WorkerRepository $workerRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    public function execute(InterruptionRequest $request, InterruptionPresenter $presenter): void
    {
        $response = new InterruptionResponse();
        $worker = $this->getWorker($request->workerId, $response);
        $inventory = $worker->getActivityInventory();

        $todoTaskList = $inventory->getTodoTaskList();
        $interruption = $request->type === 'internal'
            ? new InternalInterruption($this->idGenerator->createId(), $request->taskId)
            : new ExternalInterruption($this->idGenerator->createId(), $request->taskId)
        ;
        $todoTaskList->recordInterruption($interruption);

        if (strlen($request->newTaskName) > 0) {
            $unplannedTaskList = $inventory->getUnplannedTaskList();
            $unplannedTaskList->addTask(
                UnplannedTaskFactory::createFromInterruptionRequest(
                    $request,
                    $this->idGenerator->createId()
                )
            );
        }
        $presenter->present($response);
    }

    private function getWorker(string $workerId, InterruptionResponse $response): ?Worker
    {
        $worker = $this->workerRepository->get($workerId);
        if (!$worker instanceof Worker) {
            $response->errors[] = new Error('user-id', 'not-found');
            return null;
        }
        return $worker;
    }
}
