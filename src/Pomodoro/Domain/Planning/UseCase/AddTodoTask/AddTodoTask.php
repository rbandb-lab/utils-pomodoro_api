<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddTodoTask;

use Pomodoro\Domain\Planning\Entity\TodoTask;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class AddTodoTask
{
    private IdGenerator $idGenerator;
    private ActivityInventoryRepository $inventoryRepository;

    public function __construct(
        IdGenerator $idGenerator,
        ActivityInventoryRepository $inventoryRepository
    ) {
        $this->idGenerator = $idGenerator;
        $this->inventoryRepository = $inventoryRepository;
    }

    public function execute(AddTodoTaskRequest $request, AddTodoTaskPresenter $presenter): void
    {
        $request->id = $request->id ?? $this->idGenerator->createId();
        $task = new TodoTask($request->id, $request->name);
        $this->inventoryRepository->addTodoTaskToWorker($request->workerId, $task);
        $response = new AddTodoTaskResponse();
        $response->id = $task->getId();
        $presenter->present($response);
    }
}
