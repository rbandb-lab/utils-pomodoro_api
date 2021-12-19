<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddCalendarTask;

use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Factory\CalendarTaskFactory;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class AddCalendarTask
{
    private IdGenerator $idGenerator;
    private ActivityInventoryRepository $inventoryRepository;

    public function __construct(IdGenerator $idGenerator, ActivityInventoryRepository $inventoryRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->inventoryRepository = $inventoryRepository;
    }

    public function execute(AddCalendarTaskRequest $request, AddCalendarTaskPresenter $presenter): void
    {
        $request->id = $this->idGenerator->createId();
        $response = new AddCalendarTaskResponse();
        $task = CalendarTaskFactory::createFromRequest($request);
        $inventory = $this->inventoryRepository->getByWorkerId($request->workerId);
        $inventory->addCalendarTaskToWorker($request->workerId, $task);
        $this->inventoryRepository->save($inventory);
        $presenter->present($response);
    }
}
