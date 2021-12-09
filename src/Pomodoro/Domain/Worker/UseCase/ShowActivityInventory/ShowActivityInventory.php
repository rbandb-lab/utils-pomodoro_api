<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\ShowActivityInventory;

use Pomodoro\Domain\Worker\Entity\Worker;
use Pomodoro\Domain\Worker\Entity\WorkerRepository;

class ShowActivityInventory
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ShowActivityInventoryRequest $request, ShowActivityInventoryPresenter $presenter): void
    {
        $response = new ShowActivityInventoryResponse();
        $worker = $this->getWorker($request->workerId);
        $response->inventory = $worker->getActivityInventory()->toArray();
        $presenter->present($response);
    }

    public function getWorker(string $workerId): ?Worker
    {
        return $this->workerRepository->get($workerId);
    }
}
