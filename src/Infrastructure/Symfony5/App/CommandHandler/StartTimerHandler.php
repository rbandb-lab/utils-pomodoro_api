<?php

declare(strict_types=1);

namespace Symfony5\App\CommandHandler;

use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimer;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerPresenter;
use Pomodoro\Domain\Tracking\UseCase\StartTimer\StartTimerRequest;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Symfony5\Http\UI\Validation\Dto\StartTimerDto;

final class StartTimerHandler
{
    private ActivityInventoryRepository $inventoryRepository;
    private WorkerRepository $workerRepository;

    public function __construct(
        ActivityInventoryRepository $inventoryRepository,
        WorkerRepository $workerRepository
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->workerRepository = $workerRepository;
    }

    public function handle(StartTimerDto $dto, StartTimerPresenter $presenter): StartTimerPresenter
    {
        $request = new StartTimerRequest();
        $request->withTaskId($dto->workerId, $dto->taskId);
        $starTimer = new StartTimer(
            inventoryRepository: $this->inventoryRepository,
            workerRepository: $this->workerRepository
        );
        $starTimer->execute($request, $presenter);
        return $presenter;
    }
}
