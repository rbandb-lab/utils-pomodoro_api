<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddCalendarTask;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Factory\CalendarTaskFactory;
use Pomodoro\SharedKernel\Service\IdGenerator;

final class AddCalendarTask
{
    private IdGenerator $idGenerator;
    private WorkerRepository $workerRepository;

    public function __construct(IdGenerator $idGenerator, WorkerRepository $workerRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->workerRepository = $workerRepository;
    }

    public function execute(AddCalendarTaskRequest $request, AddCalendarTaskPresenter $presenter): void
    {
        $request->id = $this->idGenerator->createId();
        $response = new AddCalendarTaskResponse();
        $task = CalendarTaskFactory::createFromRequest($request);
        $worker = $this->workerRepository->get($request->workerId);
        $inventory = $worker->getActivityInventory();
        $calendarTaskList = $inventory->getCalendarTaskList();
        $calendarTaskList->addTask($task);
        $this->workerRepository->save($worker);
        $presenter->present($response);
    }
}
