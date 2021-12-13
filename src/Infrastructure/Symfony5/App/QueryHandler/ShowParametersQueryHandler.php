<?php

declare(strict_types=1);

namespace Symfony5\App\QueryHandler;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParameters;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersPresenter;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersRequest;

class ShowParametersQueryHandler
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function handle(string $workerId, ShowParametersPresenter $presenter): ShowParametersPresenter
    {
        $request = new ShowParametersRequest();
        $request->withWorkerId($workerId);

        $showParameters = new ShowParameters($this->workerRepository);
        $showParameters->execute($request, $presenter);
        return $presenter;
    }
}
