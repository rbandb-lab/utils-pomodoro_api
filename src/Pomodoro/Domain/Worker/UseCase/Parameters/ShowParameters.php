<?php

declare(strict_types=1);

namespace Pomodoro\Domain\Worker\UseCase\Parameters;

use Pomodoro\Domain\Worker\Entity\WorkerRepository;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use Pomodoro\SharedKernel\Error\Error;

final class ShowParameters
{
    private WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function execute(ShowParametersRequest $request, ShowParametersPresenter $presenter)
    {
        $response = new ShowParametersResponse();
        $parameters = $this->getWorkerParameters($request, $response);
        $response->parameters = $parameters->toArray();
        $presenter->present($response);
    }

    private function getWorkerParameters(ShowParametersRequest $request, $response): ?CycleParameters
    {
        $parameters = $this->workerRepository->getWorkerCycleParameters($request->workerId);
        if (!$parameters instanceof CycleParameters) {
            $response->errors[] = new Error('id', 'worker not found');
        }

        return $parameters;
    }
}
