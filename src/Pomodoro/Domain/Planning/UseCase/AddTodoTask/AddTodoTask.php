<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\TodoTaskFactory;
use Pomodoro\SharedKernel\Service\IdGenerator;

class AddTodoTask
{
    private WorkerRepository $workerRepository;
    private IdGenerator $idGenerator;

    public function __construct(
        IdGenerator $idGenerator,
        WorkerRepository $workerRepository
    ) {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    public function execute(AddTodoTaskRequest $request, AddTodoTaskPresenter $presenter): void
    {
        $worker = $this->workerRepository->get($request->workerId);
        $inventory = $worker->getActivityInventory();
        $request->id = $request->id ?? $this->idGenerator->createId();
        $task = TodoTaskFactory::createFromRequest($request);
        $inventory->addTodoTask($task);
        $this->workerRepository->save($worker);
        $response = new AddTodoTaskResponse();
        $response->id = $task->getId();
        $presenter->present($response);
    }
}
