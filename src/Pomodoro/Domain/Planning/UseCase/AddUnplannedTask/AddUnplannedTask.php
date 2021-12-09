<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\UnplannedTaskFactory;
use Pomodoro\SharedKernel\Error\Error;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class AddUnplannedTask
{
    private IdGenerator $idGenerator;
    private WorkerRepository $workerRepository;

    public function __construct(IdGenerator $idGenerator, WorkerRepository $workerRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    public function execute(AddUnplannedTaskRequest $request, AddUnplannedTaskPresenter $presenter): void
    {
        $response = new AddUnplannedTaskResponse();
        $worker = $this->getWorker($request, $response);
        if ($worker instanceof Worker) {
            $inventory = $worker->getActivityInventory();
            $request->id = $this->idGenerator->createId();
            $task = UnplannedTaskFactory::createFromRequest($request);
            $inventory->addUnplannedTask($task);
            $this->workerRepository->save($worker);
        }
        $presenter->present($response);
    }

    private function getWorker(AddUnplannedTaskRequest $request, AddUnplannedTaskResponse $response): ?Worker
    {
        $worker = $this->workerRepository->get($request->workerId);
        if (!$worker instanceof Worker) {
            $response->errors[] = new Error('id', 'worker-not-found');

            return null;
        }

        return $worker;
    }
}
