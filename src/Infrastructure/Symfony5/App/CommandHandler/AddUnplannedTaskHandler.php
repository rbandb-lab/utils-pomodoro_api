<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTask;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskPresenter;
use Pomodoro\Domain\Planning\UseCase\AddUnplannedTask\AddUnplannedTaskRequest;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\SharedKernel\Service\IdGenerator;
use Symfony5\Http\UI\Validation\Dto\AddUnplannedTaskDto;

final class AddUnplannedTaskHandler
{
    private IdGenerator $idGenerator;
    private ActivityInventoryRepository $activityInventoryRepository;

    public function __construct(IdGenerator $idGenerator, ActivityInventoryRepository $activityInventoryRepository)
    {
        $this->idGenerator = $idGenerator;
        $this->activityInventoryRepository = $activityInventoryRepository;
    }

    public function handle(AddUnplannedTaskDto $dto, AddUnplannedTaskPresenter $presenter): AddUnplannedTaskPresenter
    {
        $request = new AddUnplannedTaskRequest();
        $request->withWorkerId(
            $this->idGenerator->createId(),
            $dto->workerId,
            $dto->taskName,
            $dto->deadline
        );
        $addUnplannedTask = new AddUnplannedTask($this->activityInventoryRepository);
        $addUnplannedTask->execute($request, $presenter);
        return $presenter;
    }
}
