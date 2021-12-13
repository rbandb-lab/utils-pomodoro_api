<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTask;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskPresenter;
use Pomodoro\Domain\Planning\UseCase\AddTodoTask\AddTodoTaskRequest;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\SharedKernel\Service\IdGenerator;
use Symfony5\Http\UI\Validation\Dto\AddTodoTaskDto;

class addTodoTaskHandler
{
    private IdGenerator $idGenerator;
    private ActivityInventoryRepository $activityInventoryRepository;

    public function __construct(IdGenerator $idGenerator, ActivityInventoryRepository $activityInventoryRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->activityInventoryRepository = $activityInventoryRepository;
    }

    public function handle(AddTodoTaskDto $dto, AddTodoTaskPresenter $presenter): AddTodoTaskPresenter
    {
        $request = new AddTodoTaskRequest();
        $request->withWorkerId(
            $this->idGenerator->createId(),
            $dto->workerId,
            $dto->taskName
        );

        $addTodoTask = new AddTodoTask(
            $this->idGenerator,
            $this->activityInventoryRepository
        );

        $addTodoTask->execute($request, $presenter);

        return $presenter;
    }
}
