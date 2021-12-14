<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Planning\UseCase\AddUnplannedTask;

use Pomodoro\Domain\Planning\Entity\UnplannedTask;
use Pomodoro\Domain\Worker\Entity\ActivityInventoryRepository;

final class AddUnplannedTask
{
    private ActivityInventoryRepository $activityInventoryRepository;

    public function __construct(ActivityInventoryRepository $activityInventoryRepository)
    {
        $this->activityInventoryRepository = $activityInventoryRepository;
    }

    public function execute(AddUnplannedTaskRequest $request, AddUnplannedTaskPresenter $presenter): void
    {
        $response = new AddUnplannedTaskResponse();
        $task = new UnplannedTask(
            $request->id,
            $request->name,
            $request->urgent,
            $request->categoryId,
            $request->deadline
        );
        $this->activityInventoryRepository->addUnplannedTaskToWorker($request->workerId, $task);
        $response->id = $request->id;
        $presenter->present($response);
    }
}
